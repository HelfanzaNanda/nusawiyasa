<?php

namespace App\Http\Models\Purchase;

use App\Http\Models\Inventory\Inventories;
use App\Http\Models\Inventory\InventoryHistories;
use App\Http\Models\Purchase\PurchaseOrderDeliveryItems;
use App\Http\Models\Purchase\PurchaseOrderItems;
use App\Http\Models\Purchase\PurchaseOrders;
use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Date       $date
 * @property int        $purchase_order_item_id
 * @property int        $delivered_qty
 * @property int        $created_at
 * @property int        $updated_at
 */
class PurchaseOrderDeliveries extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_order_deliveries';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date', 'purchase_order_id', 'bpb_number', 'invoice_number', 'created_at', 'updated_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date', 'purchase_order_id' => 'int', 'delivered_qty' => 'int', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'date', 'created_at', 'updated_at'
    ];

    public function items()
    {
        return $this->hasMany('App\Http\Models\Purchase\PurchaseOrderDeliveryItems', 'purchase_order_delivery_id');
    }

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;

    // Map Schema...
    public static function mapSchema($params = [], $user = [])
    {
        $model = new self;

        return [
            'id' => ['alias' => $model->table.'.id', 'type' => 'int'],
            'date' => ['alias' => $model->table.'.date', 'type' => 'string'],
            'purchase_order_id' => ['alias' => $model->table.'.purchase_order_id', 'type' => 'int'],
            'bpb_number' => ['alias' => $model->table.'.bpb_number', 'type' => 'string'],
            'invoice_number' => ['alias' => $model->table.'.invoice_number', 'type' => 'string']
        ];
    }
    // Scopes...

    // Functions ...

    // Relations ...

    public static function datatables($start, $length, $order, $dir, $search, $filter = '')
    {
        $totalData = self::count();

        $_select = [];
        foreach(array_values(self::mapSchema()) as $select) {
            $_select[] = $select['alias'];
        }

        $qry = self::select($_select)->addSelect('suppliers.name as supplier_name')->addSelect('purchase_orders.number as po_number')
                    ->join('purchase_orders', 'purchase_orders.id', '=', 'purchase_order_deliveries.purchase_order_id')
                    ->leftjoin('suppliers', 'suppliers.id', '=', 'purchase_orders.supplier_id');
        
        $totalFiltered = $qry->count();
        
        if (empty($search)) {
            
            if ($length > 0) {
                $qry->skip($start)
                    ->take($length);
            }

            foreach ($order as $row) {
                $qry->orderBy($row['column'], $row['dir']);
            }

        } else {
            foreach (array_values(self::mapSchema()) as $key => $val) {
                if ($key < 1) {
                    $qry->whereRaw('('.$val['alias'].' LIKE \'%'.$search.'%\'');
                } else if (count(array_values(self::mapSchema())) == ($key + 1)) {
                    $qry->orWhereRaw($val['alias'].' LIKE \'%'.$search.'%\')');
                } else {
                    $qry->orWhereRaw($val['alias'].' LIKE \'%'.$search.'%\'');
                }
            }

            $totalFiltered = $qry->count();

            if ($length > 0) {
                $qry->skip($start)
                    ->take($length);
            }

            foreach ($order as $row) {
                $qry->orderBy($row['column'], $row['dir']);
            }
        }

        return [
            'data' => $qry->get(),
            'totalData' => $totalData,
            'totalFiltered' => $totalFiltered
        ];
    }

    public static function createOrUpdate($params, $method, $request)
    {
        DB::beginTransaction();
        $filename = null;

        if (isset($params['_token']) && $params['_token']) {
            unset($params['_token']);
        }

        $receipt_of_goods['date'] = $params['date'];
        $receipt_of_goods['purchase_order_id'] = $params['purchase_order_id'];
        $receipt_of_goods['bpb_number'] = $params['bpb_number'];
        $receipt_of_goods['invoice_number'] = $params['invoice_number'];

        if (isset($params['id']) && $params['id']) {
            $id = $params['id'];
            unset($params['id']);

            $update = self::where('id', $id)->update($receipt_of_goods);

            PurchaseOrderDeliveryItems::where('purchase_order_delivery_id', $id)->delete();

            foreach ($params['inventory_id'] as $key => $val) {
                PurchaseOrderDeliveryItems::create([
                    'purchase_order_delivery_id' => $id,
                    'inventory_id' => $val,
                    'delivered_qty' => $params['delivered_qty'][$key],
                    'note' => $params['note'][$key],
                ]);

                $adjust['inventory_id'] = $val;
                $adjust['delivered_qty'] = $params['delivered_qty'][$key];
                $adjust['purchase_order_id'] = $params['purchase_order_id'];
                $adjust_qty = PurchaseOrderItems::adjustPurchaseOrderDeliveredItems($adjust);
                
                if (isset($adjust_qty['status']) && $adjust_qty['status'] == 'error') {
                    DB::rollBack();
                    return response()->json($adjust_qty);
                }

                $adjust['qty'] = $adjust['delivered_qty'];
                Inventories::stockMovement($adjust, 'in');

                InventoryHistories::create([
                    'ref_number' => $params['bpb_number'],
                    'inventory_id' => $val,
                    'qty' => $params['delivered_qty'][$key],
                    'date' => $params['date'],
                    'type' => 'in',
                    'models' => __CLASS__
                ]);
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Diubah!'
            ]);
        }

        $insert = self::create($receipt_of_goods);

        if ($insert) {
            foreach ($params['inventory_id'] as $key => $val) {
                PurchaseOrderDeliveryItems::create([
                    'purchase_order_delivery_id' => $insert->id,
                    'inventory_id' => $val,
                    'delivered_qty' => $params['delivered_qty'][$key],
                    'note' => $params['note'][$key],
                ]);

                $adjust['inventory_id'] = $val;
                $adjust['delivered_qty'] = $params['delivered_qty'][$key];
                $adjust['purchase_order_id'] = $params['purchase_order_id'];
                $adjust_qty = PurchaseOrderItems::adjustPurchaseOrderDeliveredItems($adjust);
                
                if (isset($adjust_qty['status']) && $adjust_qty['status'] == 'error') {
                    DB::rollBack();
                    return response()->json($adjust_qty);
                }

                $adjust['qty'] = $adjust['delivered_qty'];
                Inventories::stockMovement($adjust, 'in');

                InventoryHistories::create([
                    'ref_number' => $params['bpb_number'],
                    'inventory_id' => $val,
                    'qty' => $params['delivered_qty'][$key],
                    'date' => $params['date'],
                    'type' => 'in',
                    'models' => __CLASS__
                ]);
            }
            
            PurchaseOrders::adjustPOStatus($params['purchase_order_id']);
        }

        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Data Berhasil Disimpan'
        ]);
    }

    public static function getPaginatedResult($params)
    {
        $paramsPage = isset($params['page']) ? $params['page'] : 0;

        unset($params['page']);

        $_select = [];
        foreach(array_values(self::mapSchema()) as $select) {
            $_select[] = $select['alias'];
        }

        $db = self::select($_select)
                ->addSelect('inventory_units.name as unit_name')
                ->join('inventory_units', 'inventory_units.id', '=', 'inventories.unit_id');

        if ($params) {
            foreach (array($params) as $k => $v) {
                foreach (array_keys($v) as $key => $row) {
                    if (isset(self::mapSchema()[$row])) {
                        if (is_array(array_values($v)[$key])) {
                            if ($this->operators[array_keys(array_values($v)[$key])[$key]] != 'like') {
                                $db->where(self::mapSchema()[$row]['alias'], $this->operators[array_keys(array_values($v)[$key])[$key]], array_values(array_values($v)[$key])[$key]);
                            } else {
                                if (self::mapSchema()[$row]['type'] === 'int') {
                                    $db->where(self::mapSchema()[$row]['alias'], array_values($v)[$key]);
                                } else {
                                    $db->where(self::mapSchema()[$row]['alias'], 'like', '%'.array_values($v)[$key].'%');
                                }
                            }
                        } else {
                            if (self::mapSchema()[$row]['type'] === 'int') {
                                $db->where(self::mapSchema()[$row]['alias'], array_values($v)[$key]);
                            } else {
                                $db->where(self::mapSchema()[$row]['alias'], 'like', '%'.array_values($v)[$key].'%');
                            }
                        }
                    }
                }
            }
        }

        $countAll = $db->count();
        $currentPage = $paramsPage > 0 ? $paramsPage - 1 : 0;
        $page = $paramsPage > 0 ? $paramsPage + 1 : 2; 
        $nextPage = env('APP_URL').'/inventories?page='.$page;
        $prevPage = env('APP_URL').'/inventories?page='.($currentPage < 1 ? 1 : $currentPage);
        $totalPage = ceil((int)$countAll / 10);

        // $db->orderBy($model->table.'.fullname', 'asc');
        $db->skip($currentPage)
           ->take(10);

        return response()->json([
            'nav' => [
                'totalData' => $countAll,
                'nextPage' => $nextPage,
                'prevPage' => $prevPage,
                'totalPage' => $totalPage
            ],
            'data' => $db->get()
        ]);
    }

    public static function getById($id, $params = null)
    {
        $data = self::where('id', $id)
                    ->first();

        return response()->json($data);
    }

    public static function getAllResult($params)
    {
        unset($params['all']);

        $db = self::select(array_keys(self::mapSchema()));

        if ($params) {
            foreach (array($params) as $k => $v) {
                foreach (array_keys($v) as $key => $row) {
                    if (isset(self::mapSchema()[$row])) {
                        if (is_array(array_values($v)[$key])) {
                            if ($this->operators[array_keys(array_values($v)[$key])[$key]] != 'like') {
                                $db->where(self::mapSchema()[$row], $this->operators[array_keys(array_values($v)[$key])[$key]], array_values(array_values($v)[$key])[$key]);
                            } else {
                                $db->where(self::mapSchema()[$row], 'like', '%'.array_values($v)[$key].'%');
                            }
                        } else {
                            if (self::mapSchema()[$row]['type'] === 'int') {
                                $db->where(self::mapSchema()[$row]['alias'], array_values($v)[$key]);
                            } else {
                                $db->where(self::mapSchema()[$row]['alias'], 'like', '%'.array_values($v)[$key].'%');
                            }
                        }
                    }
                }
            }
        }

        return response()->json([
            'data' => $db->get()
        ]);
    }
}
