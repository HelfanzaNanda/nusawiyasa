<?php

namespace App\Http\Models\Purchase;

use App\Http\Models\Cluster\Lot;
use App\Http\Models\Purchase\PurchaseOrderItems;
use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string     $number
 * @property int        $inventory_id
 * @property int        $supplier_id
 * @property Date       $date
 * @property int        $status
 * @property int        $created_at
 * @property int        $updated_at
 */
class PurchaseOrders extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_orders';

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
        'number', 'supplier_id', 'date', 'status', 'created_at', 'updated_at', 'fpp_number', 'type', 'note', 'subtotal', 'tax', 'delivery', 'other', 'total', 'approved_user_id', 'known_user_id', 'created_user_id', 'cluster_id', 'lot_id'
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
        'number' => 'string', 'supplier_id' => 'int', 'date' => 'date', 'status' => 'int', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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
        return $this->hasMany('App\Http\Models\Purchase\PurchaseOrderItems', 'purchase_order_id');
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
            'number' => ['alias' => $model->table.'.number', 'type' => 'string'],
            'supplier_id' => ['alias' => $model->table.'.supplier_id', 'type' => 'int'],
            'date' => ['alias' => $model->table.'.date', 'type' => 'string'],
            'status' => ['alias' => $model->table.'.status', 'type' => 'int'],
            'created_at' => ['alias' => $model->table.'.created_at', 'type' => 'string'],
            'updated_at' => ['alias' => $model->table.'.updated_at', 'type' => 'string'],
            'fpp_number' => ['alias' => $model->table.'.fpp_number', 'type' => 'string'],
            'type' => ['alias' => $model->table.'.type', 'type' => 'string'],
            'note' => ['alias' => $model->table.'.note', 'type' => 'string'],
            'subtotal' => ['alias' => $model->table.'.subtotal', 'type' => 'string'],
            'tax' => ['alias' => $model->table.'.tax', 'type' => 'string'],
            'delivery' => ['alias' => $model->table.'.delivery', 'type' => 'string'],
            'other' => ['alias' => $model->table.'.other', 'type' => 'string'],
            'total' => ['alias' => $model->table.'.total', 'type' => 'string'],
            'cluster_id' => ['alias' => $model->table.'.cluster_id', 'type' => 'string'],
            'lot_id' => ['alias' => $model->table.'.lot_id', 'type' => 'string'],
            'approved_user_id' => ['alias' => $model->table.'.approved_user_id', 'type' => 'int'],
            'known_user_id' => ['alias' => $model->table.'.known_user_id', 'type' => 'int'],
            'created_user_id' => ['alias' => $model->table.'.created_user_id', 'type' => 'int']
        ];
    }
    // Scopes...

    // Functions ...

    // Relations ...

    public static function datatables($start, $length, $order, $dir, $search, $filter = '', $session = [])
    {
        $totalData = self::count();

        $_select = [];
        foreach(array_values(self::mapSchema()) as $select) {
            $_select[] = $select['alias'];
        }

        $qry = self::select($_select)->addSelect('request_materials.number as request_number')->leftJoin('request_materials', 'request_materials.id', '=', 'purchase_orders.fpp_number');
        
        if ((isset($session['_role_id']) && in_array($session['_role_id'], [2, 3, 4, 5, 6])) && isset($session['_cluster_id'])) {
            $qry->where('cluster_id', $session['_cluster_id']);
        }

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

        $purchase_order['number'] = $params['number'];
        $purchase_order['date'] = $params['date'];
        $purchase_order['status'] = 4;
        $purchase_order['fpp_number'] = $params['fpp_number'];
        $purchase_order['type'] = $params['type'];
        $purchase_order['note'] = $params['note'];
        $purchase_order['subtotal'] = floatval(preg_replace('/[^\d\.\-]/', '', $params['subtotal']));
        $purchase_order['tax'] = floatval(preg_replace('/[^\d\.\-]/', '', $params['tax']));
        $purchase_order['delivery'] = floatval(preg_replace('/[^\d\.\-]/', '', $params['delivery']));
        $purchase_order['other'] = floatval(preg_replace('/[^\d\.\-]/', '', $params['other']));
        $purchase_order['total'] = floatval(preg_replace('/[^\d\.\-]/', '', $params['total']));
        // $purchase_order['lot_id'] = $params['lot_id'];
        // $purchase_order['cluster_id'] = Lot::where('id', $params['lot_id'])->value('cluster_id');
        $purchase_order['cluster_id'] = $params['cluster_id'];
        $purchase_order['approved_user_id'] = 0;
        $purchase_order['known_user_id'] = 0;
        $purchase_order['created_user_id'] = session()->get('_id');

        if (isset($params['id']) && $params['id']) {
            $id = $params['id'];
            unset($params['id']);

            $update = self::where('id', $id)->update($purchase_order);
            PurchaseOrderItems::where('purchase_order_id', $id)->delete();
            foreach ($params['item_inventory_id'] as $key => $val) {
                PurchaseOrderItems::create([
                    'purchase_order_id' => $id,
                    'inventory_id' => $params['item_inventory_id'][$key],
                    'qty' => $params['item_qty'][$key],
                    'delivered_qty' => 0,
                    'price' => floatval(preg_replace('/[^\d\.\-]/', '', $params['item_price'][$key])),
                    'tax' => 0,
                    'discount' => 0,
                    'total' => floatval(preg_replace('/[^\d\.\-]/', '', $params['item_total'][$key])),
                    'supplier_id' => $params['item_supplier_id'][$key]
                ]);
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Diubah!'
            ]);
        }


        $insert = self::create($purchase_order);

        if ($insert) {
            foreach ($params['item_inventory_id'] as $key => $val) {
                PurchaseOrderItems::create([
                    'purchase_order_id' => $insert->id,
                    'inventory_id' => $params['item_inventory_id'][$key],
                    'qty' => $params['item_qty'][$key],
                    'delivered_qty' => 0,
                    'price' => floatval(preg_replace('/[^\d\.\-]/', '', $params['item_price'][$key])),
                    'tax' => 0,
                    'discount' => 0,
                    'total' => floatval(preg_replace('/[^\d\.\-]/', '', $params['item_total'][$key])),
                    'supplier_id' => $params['item_supplier_id'][$key]
                ]);
            }
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
        $data = self::where('id', $id)->with('items.inventory.unit')
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

    public static function adjustPOStatus($po_id)
    {
        $po_items = PurchaseOrderItems::where('purchase_order_id', $po_id);
        $count_items = $po_items->count();
        $res_po_items = $po_items->where('qty', '<=', DB::raw('delivered_qty'))->get()->toArray();

        if (count($res_po_items) < 1) {
            self::where('id', $po_id)->update(['status' => 6]);
        } else if (count($res_po_items) < $count_items) {
            self::where('id', $po_id)->update(['status' => 5]);
        } else {
            $delivered_qty = 0;
            foreach($res_po_items as $row) {
                if ($row['delivered_qty'] > 0) {
                    $delivered_qty = $row['delivered_qty'];
                    break;
                }
            }

            if ($delivered_qty > 0) {
                self::where('id', $po_id)->update(['status' => 5]);
            }
        }

        return;
    }
}
