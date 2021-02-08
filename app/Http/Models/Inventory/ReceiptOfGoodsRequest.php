<?php

namespace App\Http\Models\Inventory;

use DB;
use Carbon\Carbon;
use App\Http\Models\Cluster\Lot;
use Illuminate\Database\Eloquent\Model;
use App\Http\Models\Inventory\Inventories;
use App\Http\Models\Purchase\PurchaseOrders;
use App\Http\Models\Purchase\PurchaseOrderItems;
use App\Http\Models\Purchase\PurchaseOrderDeliveryItems;
use App\Http\Models\Inventory\ReceiptOfGoodsRequestItems;

/**
 * @property Date       $date
 * @property int        $purchase_order_item_id
 * @property int        $delivered_qty
 * @property int        $created_at
 * @property int        $updated_at
 */
class ReceiptOfGoodsRequest extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'receipt_of_goods_request';

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
        'number',
        'date',
        'cluster_id',
        'lot_id',
        'approved_user_id',
        'known_user_id',
        'created_user_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

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
        return $this->hasMany(ReceiptOfGoodsRequestItems::class);
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
            'date' => ['alias' => $model->table.'.date', 'type' => 'string'],
            'cluster_id' => ['alias' => $model->table.'.cluster_id', 'type' => 'int'],
            'lot_id' => ['alias' => $model->table.'.lot_id', 'type' => 'int'],
            'approved_user_id' => ['alias' => $model->table.'.approved_user_id', 'type' => 'int'],
            'known_user_id' => ['alias' => $model->table.'.known_user_id', 'type' => 'int'],
            'created_user_id' => ['alias' => $model->table.'.created_user_id', 'type' => 'int']
        ];
    }
    // Scopes...

    // Functions ...

    // Relations ...

    public static function queryUsedInventory($_select)
    {
        $year = now()->year();
        $month = now()->month();
        $endDate = now()->format('Y-m-d');
        return self::select($_select)
                ->addSelect('clusters.name as cluster_name')
                ->addSelect('lots.block')
                ->addSelect('lots.unit_number')
                ->addSelect('lots.surface_area')
                ->addSelect('lots.building_area')
                ->leftJoin('lots', 'lots.id', '=', 'receipt_of_goods_request.lot_id')
                ->leftJoin('clusters', 'clusters.id', '=', 'lots.cluster_id')
                ->whereBetween('receipt_of_goods_request.date', [$year.'-'.$month.'-01', $endDate]);
    }

    public static function queryFilterUsedInventory($_select, $operator, $filter)
    {
        $startDate = Carbon::parse(substr($filter['daterange'], 0, 10))->format('Y-m-d');
        $endDate = Carbon::parse(substr($filter['daterange'], 12))->format('Y-m-d');
        return self::select($_select)
                ->addSelect('clusters.name as cluster_name')
                ->addSelect('lots.block')
                ->addSelect('lots.unit_number')
                ->addSelect('lots.surface_area')
                ->addSelect('lots.building_area')
                ->leftJoin('lots', 'lots.id', '=', 'receipt_of_goods_request.lot_id')
                ->leftJoin('clusters', 'clusters.id', '=', 'lots.cluster_id')
                ->where('receipt_of_goods_request.cluster_id', $operator, $filter['cluster'])
                ->whereBetween('receipt_of_goods_request.date', [$startDate, $endDate]);
    }

    public static function datatables($start, $length, $order, $dir, $search, $filter = '', $session = [])
    {
        $totalData = self::count();

        $_select = [];
        foreach(array_values(self::mapSchema()) as $select) {
            $_select[] = $select['alias'];
        }

        if ($filter['used_inventory'] ?? '') {
            if ($filter['cluster'] == '' && $filter['daterange'] == '') {
                $qry = self::queryUsedInventory($_select);
            }else{
                $operator = $filter['cluster'] == '0' || $filter['cluster'] == '' ? '!=' : '=';
                $qry = self::queryFilterUsedInventory($_select,$operator, $filter);
            }
        }else{
            $qry = self::select($_select)
                ->addSelect('clusters.name as cluster_name')
                ->addSelect('lots.block')
                ->addSelect('lots.unit_number')
                ->addSelect('lots.surface_area')
                ->addSelect('lots.building_area')
                ->leftJoin('lots', 'lots.id', '=', 'receipt_of_goods_request.lot_id')
                ->leftJoin('clusters', 'clusters.id', '=', 'lots.cluster_id');
        }

        if ((isset($session['_role_id']) && in_array($session['_role_id'], [2, 3, 4, 5, 6, 10])) && isset($session['_cluster_id'])) {
            $qry->where('lots.cluster_id', $session['_cluster_id']);
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

        $request_of_goods_request['number'] = $params['number'];
        $request_of_goods_request['date'] = $params['date'];
        $request_of_goods_request['lot_id'] = $params['lot_id'];
        $request_of_goods_request['cluster_id'] = Lot::where('id', $params['lot_id'])->value('cluster_id');
        $request_of_goods_request['approved_user_id'] = 0;
        $request_of_goods_request['known_user_id'] = 0;
        $request_of_goods_request['created_user_id'] = session()->get('_id');

        if (isset($params['id']) && $params['id']) {
            $id = $params['id'];
            unset($params['id']);

            $update = self::where('id', $id)->update($request_of_goods_request);

            ReceiptOfGoodsRequestItems::whereReceiptOfGoodsRequestId($id)->delete();

            foreach ($params['inventory_id'] as $key => $val) {
                ReceiptOfGoodsRequestItems::create([
                    'receipt_of_goods_request_id' => $id,
                    'inventory_id' => $val,
                    'qty' => $params['qty'][$key],
                    'note' => $params['note'][$key],
                ]);

                $adjust['inventory_id'] = $val;
                $adjust['qty'] = $params['qty'][$key];

                Inventories::stockMovement($adjust, 'out');

                InventoryHistories::create([
                    'ref_number' => $params['number'],
                    'inventory_id' => $val,
                    'qty' => $params['qty'][$key],
                    'date' => $params['date'],
                    'type' => 'out',
                    'models' => __CLASS__
                ]);
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Diubah!'
            ]);
        }

        $insert = self::create($request_of_goods_request);

        if ($insert) {
            foreach ($params['inventory_id'] as $key => $val) {
                ReceiptOfGoodsRequestItems::create([
                    'receipt_of_goods_request_id' => $insert->id,
                    'inventory_id' => $val,
                    'qty' => $params['qty'][$key],
                    'note' => $params['note'][$key],
                ]);

                $adjust['inventory_id'] = $val;
                $adjust['qty'] = $params['qty'][$key];

                Inventories::stockMovement($adjust, 'out');

                InventoryHistories::create([
                    'ref_number' => $params['number'],
                    'inventory_id' => $val,
                    'qty' => $params['qty'][$key],
                    'date' => $params['date'],
                    'type' => 'out',
                    'models' => __CLASS__
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

        $db = self::select($_select);

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
        $db->skip($currentPage * 10)
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

    public static function generatePdf($request)
    {
        
        $startDate = Carbon::parse(substr($request->daterange_pdf, 0, 10))->format('Y-m-d');
        $endDate = Carbon::parse(substr($request->daterange_pdf, 12))->format('Y-m-d');
        if ($request->cluster_pdf == '0') {
            
            $receipts = self::whereBetween('date', [$startDate, $endDate])
            ->with('receiptOfGoodsRequestItems')->get();
        }else{
            $cluster = $request->cluster_pdf;
            $receipts = self::where('cluster_id', $cluster)
            ->whereBetween('date', [$startDate, $endDate])
            ->with('receiptOfGoodsRequestItems')->get();
        }
        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'receipts' => $receipts
        ];
    }

    public function receiptOfGoodsRequestItems()
    {
        return $this->hasMany(ReceiptOfGoodsRequestItems::class);
    }

    public function date_translate_format()
    {
        return Carbon::parse($this->date)->translatedFormat('d F Y');
    }

    public function lot()
    {
        return $this->belongsTo(Lot::class);
    }
}
