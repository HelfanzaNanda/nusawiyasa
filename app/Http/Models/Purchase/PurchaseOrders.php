<?php

namespace App\Http\Models\Purchase;

use App\Helper\GlobalHelper;
use App\Http\Models\Accounting\Debt;
use App\Http\Models\Cluster\Cluster;
use App\Http\Models\Cluster\Lot;
use App\Http\Models\Inventory\Suppliers;
use App\Http\Models\Project\RequestMaterialItems;
use App\Http\Models\Purchase\PurchaseOrderItems;
use App\Http\Models\Ref\RefGeneralStatuses;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use tidy;

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
        'number', 'supplier_id', 'date', 'status', 'created_at', 'updated_at', 'fpp_number', 'payment_type', 'type', 'note', 'subtotal', 'tax', 'delivery', 'other', 'total', 'approved_user_id', 'known_user_id', 'created_user_id', 'cluster_id', 'lot_id'
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

    public function supplier()
    {
        return $this->belongsTo(Suppliers::class);
    }

    public function cluster()
    {
        return $this->belongsTo(Cluster::class);
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
            'payment_type' => ['alias' => $model->table.'.payment_type', 'type' => 'string'],
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

    public static function queryFilterOutStanding($_select, $operator, $cluster, $daterange){
        $startDate = Carbon::parse(substr($daterange, 0, 10))->format('Y-m-d');
        $endDate = Carbon::parse(substr($daterange, 12))->format('Y-m-d');
        return self::select($_select)->addSelect('request_materials.number as request_number')
                ->leftJoin('request_materials', 'request_materials.id', '=', 'purchase_orders.fpp_number')
                ->leftJoin('purchase_order_items', 'purchase_order_items.purchase_order_id', '=', 'purchase_orders.id')
                ->leftJoin('clusters', 'clusters.id', 'purchase_orders.cluster_id')
                ->where('purchase_orders.status', '!=', '6')
                ->where('purchase_order_items.delivered_qty', '!=', '0')
                ->whereBetween('purchase_orders.date', [$startDate, $endDate])
                ->where('purchase_orders.cluster_id', $operator, $cluster)
                ->groupBy('purchase_order_items.purchase_order_id');
    }

    public static function queryFilterInventoryPurchase($_select, $operator, $cluster, $daterange){
        $startDate = Carbon::parse(substr($daterange, 0, 10))->format('Y-m-d');
        $endDate = Carbon::parse(substr($daterange, 12))->format('Y-m-d');
        return self::select($_select)->addSelect('request_materials.number as request_number')
                ->addSelect('purchase_order_items.qty as qty')
                ->leftJoin('request_materials', 'request_materials.id', '=', 'purchase_orders.fpp_number')
                ->leftJoin('purchase_order_items', 'purchase_order_items.purchase_order_id', '=', 'purchase_orders.id')
                ->leftJoin('clusters', 'clusters.id', 'purchase_orders.cluster_id')
                ->where('purchase_orders.status', '!=', '6')
                ->where('purchase_order_items.delivered_qty', '!=', '0')
                ->whereBetween('purchase_orders.date', [$startDate, $endDate])
                ->where('purchase_orders.cluster_id', $operator, $cluster)
                ->groupBy('purchase_order_items.purchase_order_id');
    }

    public static function queryOutStanding($_select)
    {
        $year = now()->year();
        $month = now()->month();
        $endDate = now()->format('Y-m-d');
        return self::select($_select)->addSelect('request_materials.number as request_number')
                ->join('request_materials', 'request_materials.id', '=', 'purchase_orders.fpp_number')
                ->join('purchase_order_items', 'purchase_order_items.purchase_order_id', '=', 'purchase_orders.id')
                ->where('purchase_orders.status', '!=', '6')
                ->where('purchase_order_items.delivered_qty', '!=', '0')
                ->whereBetween('purchase_orders.date', [$year.'-'.$month.'-01', $endDate])
                ->groupBy('purchase_order_items.purchase_order_id');
    }

    public static function queryInventoryPurchase($_select)
    {
        $year = now()->year();
        $month = now()->month();
        $endDate = now()->format('Y-m-d');
        return self::select($_select)->addSelect('request_materials.number as request_number')
                ->addSelect('purchase_order_items.qty as qty')
                ->leftJoin('request_materials', 'request_materials.id', '=', 'purchase_orders.fpp_number')
                ->leftJoin('purchase_order_items', 'purchase_order_items.purchase_order_id', '=', 'purchase_orders.id')
                ->where('purchase_orders.status', '!=', '6')
                ->where('purchase_order_items.delivered_qty', '!=', '0')
                ->whereBetween('purchase_orders.date', [$year.'-'.$month.'-01', $endDate])
                ->groupBy('purchase_order_items.purchase_order_id');
    }

    public static function datatables($start, $length, $order, $dir, $search, $filter = '', $session = [])
    {
        $totalData = self::count();

        $_select = [];
        foreach(array_values(self::mapSchema()) as $select) {
            $_select[] = $select['alias'];
        }

        if ($filter['outstanding_po'] ?? '') {
            if ($filter['cluster'] == '' && $filter['daterange'] == '') {
                $qry = self::queryOutStanding($_select);
            }else{
                $operator = $filter['cluster'] == '0' || $filter['cluster'] == '' ? '!=' : '=';
                $qry = self::queryFilterOutStanding($_select,$operator, $filter['cluster'], $filter['daterange']);
            }
        }elseif ($filter['inventory_purchase'] ?? '') {
            if ($filter['cluster'] == '' && $filter['daterange'] == '') {
                $qry = self::queryInventoryPurchase($_select);
            }else{
                $operator = $filter['cluster'] == '0' || $filter['cluster'] == '' ? '!=' : '=';
                $qry = self::queryFilterInventoryPurchase($_select,$operator, $filter['cluster'], $filter['daterange']);
            }

        }
        else{
            $qry = self::select($_select)->addSelect('request_materials.number as request_number')
                ->leftJoin('request_materials', 'request_materials.id', '=', 'purchase_orders.fpp_number');
        }

        // $qry = self::select($_select)->addSelect('request_materials.number as request_number')
        //         ->leftJoin('request_materials', 'request_materials.id', '=', 'purchase_orders.fpp_number');

        if ((isset($session['_role_id']) && $session['_role_id'] > 1) && (isset($session['_cluster_id']) && $session['_cluster_id'] > 0)) {
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
        $purchase_order['payment_type'] = $params['payment_type'];
        $purchase_order['note'] = $params['note'];
        $purchase_order['subtotal'] = floatval(preg_replace('/[^\d\.\-]/', '', $params['subtotal']));
        $purchase_order['tax'] = floatval(preg_replace('/[^\d\.\-]/', '', $params['tax']));
        $purchase_order['delivery'] = floatval(preg_replace('/[^\d\.\-]/', '', $params['delivery']));
        $purchase_order['other'] = floatval(preg_replace('/[^\d\.\-]/', '', $params['other']));
        $purchase_order['total'] = floatval(preg_replace('/[^\d\.\-]/', '', $params['total']));
        $purchase_order['supplier_id'] = $params['item_supplier_id'];
        // $purchase_order['cluster_id'] = Lot::where('id', $params['lot_id'])->value('cluster_id');
        $purchase_order['cluster_id'] = $params['cluster_id'];
        $purchase_order['approved_user_id'] = 0;
        $purchase_order['known_user_id'] = 0;
        $purchase_order['created_user_id'] = session()->get('_id');
        $purchase_order['lot_id'] = $params['lot_id'];
        $debt['number'] = $params['number_debt'] ?? 0;
        $debt['supplier_id'] = $params['item_supplier_id'];
        $debt['total'] = GlobalHelper::convertSeparator($params['total']);
        $debt['date'] = $params['date'];
        $debt['payment_plan_date'] = $params['payment_plan_date'] ?? now();
        $debt['description'] = $params['note'];
        if (isset($params['id']) && $params['id']) {
            $id = $params['id'];
            unset($params['id']);
            $update = self::where('id', $id)->update($purchase_order);
            if ($params['payment_type'] == 'credit') {
                $tb_debt = Debt::whereId($params['id_debt'])->first();
                $tb_debt->update($debt);
            }
            //Debt::upda
            self::adjustPOStatus($id);

            PurchaseOrderItems::where('purchase_order_id', $id)->delete();
            foreach ($params['item_inventory_id'] as $key => $val) {
                if ($params['checkbox'][$key] == "1") {
                    PurchaseOrderItems::create([
                        'purchase_order_id' => $id,
                        'inventory_id' => $params['item_inventory_id'][$key],
                        'qty' => $params['item_qty'][$key],
                        'delivered_qty' => 0,
                        'price' => floatval(preg_replace('/[^\d\.\-]/', '', $params['item_price'][$key])),
                        'tax' => 0,
                        'discount' => 0,
                        'total' => floatval(preg_replace('/[^\d\.\-]/', '', $params['item_total'][$key])),
                    ]);
                    // RequestMaterialItems::where('id', $params['item_request_material_id'][$key])->update([
                    //     'is_used_in_po' => true
                    // ]);
                }
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Diubah!'
            ]);
        }


        $insert = self::create($purchase_order);

        if ($insert) {
            $debt['purchase_order_id'] = $insert->id;
            if ($params['payment_type'] == 'credit') {
                Debt::create($debt);
            }

            foreach ($params['item_inventory_id'] as $key => $val) {
                if ($params['checkbox'][$key] == "1") {
                    PurchaseOrderItems::create([
                        'purchase_order_id' => $insert->id,
                        'inventory_id' => $params['item_inventory_id'][$key],
                        'qty' => $params['item_qty'][$key],
                        'delivered_qty' => 0,
                        'price' => floatval(preg_replace('/[^\d\.\-]/', '', $params['item_price'][$key])),
                        'tax' => 0,
                        'discount' => 0,
                        'total' => floatval(preg_replace('/[^\d\.\-]/', '', $params['item_total'][$key])),
                        //'supplier_id' => $params['item_supplier_id'][$key]
                        //'supplier_id' => $params['item_supplier_id']
                    ]);
                    RequestMaterialItems::where('id', $params['item_request_material_id'][$key])->update([
                        'purchase_order_id' => $insert->id,
                        'is_used_in_po' => true
                    ]);
                }
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


    public static function generatePdfOutStanding(Request $request)
    {
        $startDate = Carbon::parse(substr($request->daterange_pdf, 0, 10))->format('Y-m-d');
        $endDate = Carbon::parse(substr($request->daterange_pdf, 12))->format('Y-m-d');
        if ($request->cluster_pdf == '0') {
            $purchases = self::where('status', '!=', '6')
            ->whereBetween('date', [$startDate, $endDate])
            ->with(['purchaseOrderItems' => function($item){
                $item->where('delivered_qty', '!=', '0');
            }])->get();
            $res = [];
            foreach($purchases as $purchase){
                if (count($purchase->purchaseOrderItems) > 0) {
                    array_push($res, $purchase);
                }
            }
        }else{
            $cluster = $request->cluster_pdf;
            $startDate = Carbon::parse(substr($request->daterange_pdf, 0, 10))->format('Y-m-d');
            $endDate = Carbon::parse(substr($request->daterange_pdf, 12))->format('Y-m-d');

            $purchases = self::where('cluster_id', $cluster)
            ->where('status', '!=', '6')
            ->whereBetween('date', [$startDate, $endDate])
            ->with(['purchaseOrderItems' => function($item){
                $item->where('delivered_qty', '!=', '0');
            }])->get();
            $res = [];
            foreach($purchases as $purchase){
                if (count($purchase->purchaseOrderItems) > 0) {
                    array_push($res, $purchase);
                }
            }
        }
        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'purchases' => $res
        ];
    }

    public static function generatePdfInventoryPurchase(Request $request)
    {
        $startDate = Carbon::parse(substr($request->daterange_pdf, 0, 10))->format('Y-m-d');
        $endDate = Carbon::parse(substr($request->daterange_pdf, 12))->format('Y-m-d');
        if ($request->cluster_pdf == '0') {
            $purchases = self::whereBetween('date', [$startDate, $endDate])
            ->with(['purchaseOrderItems' => function($item){
                $item->where('delivered_qty', '!=', '0');
            }])->get();
            $res = [];
            foreach($purchases as $purchase){
                if (count($purchase->purchaseOrderItems) > 0) {
                    array_push($res, $purchase);
                }
            }
        }else{
            $cluster = $request->cluster_pdf;
            $startDate = Carbon::parse(substr($request->daterange_pdf, 0, 10))->format('Y-m-d');
            $endDate = Carbon::parse(substr($request->daterange_pdf, 12))->format('Y-m-d');

            $purchases = self::where('cluster_id', $cluster)
            ->whereBetween('date', [$startDate, $endDate])
            ->with(['purchaseOrderItems' => function($item){
                $item->where('delivered_qty', '!=', '0');
            }])->get();
            $res = [];
            foreach($purchases as $purchase){
                if (count($purchase->purchaseOrderItems) > 0) {
                    array_push($res, $purchase);
                }
            }
        }
        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'purchases' => $res
        ];
    }

    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItems::class, 'purchase_order_id', 'id');
    }

    public function date_translate_format()
    {
        return Carbon::parse($this->date)->translatedFormat('d F Y');
    }


    public function refGeneralStatuses()
    {
        return $this->belongsTo(RefGeneralStatuses::class, 'status', 'id');
    }

}
