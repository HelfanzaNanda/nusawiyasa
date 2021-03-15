<?php

namespace App\Http\Models\Project;

use DB;
use App\Http\Models\Project\RequestMaterialItems;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string     $number
 * @property string     $title
 * @property string     $subject
 * @property int        $spk_id
 * @property Date       $date
 * @property int        $created_at
 * @property int        $updated_at
 */
class RequestMaterials extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'request_materials';

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
        'number', 'title', 'subject', 'spk_id', 'date', 'created_at', 'updated_at', 'type', 'cluster_id', 'lot_id'
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
        'number' => 'string', 'title' => 'string', 'subject' => 'string', 'spk_id' => 'int', 'date' => 'date', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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
        return $this->hasMany('App\Http\Models\Project\RequestMaterialItems', 'request_material_id');
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
            'title' => ['alias' => $model->table.'.title', 'type' => 'string'],
            'subject' => ['alias' => $model->table.'.subject', 'type' => 'string'],
            'spk_id' => ['alias' => $model->table.'.spk_id', 'type' => 'string'],
            'date' => ['alias' => $model->table.'.date', 'type' => 'string'],
            'type' => ['alias' => $model->table.'.type', 'type' => 'string'],
            'cluster_id' => ['alias' => $model->table.'.cluster_id', 'type' => 'string'],
            'lot_id' => ['alias' => $model->table.'.lot_id', 'type' => 'string'],
            'created_at' => ['alias' => $model->table.'.created_at', 'type' => 'string'],
            'updated_at' => ['alias' => $model->table.'.updated_at', 'type' => 'string']
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

        $qry = self::select($_select)->addSelect('spk_workers.number as spk_number')
                    ->leftJoin('spk_workers', 'spk_workers.id', '=', 'request_materials.spk_id');
        
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

        $request_material['number'] = $params['number'];
        $request_material['title'] = isset($params['title']) && $params['title'] ? $params['title'] : 'Request Pengajuan Bahan';
        $request_material['subject'] = isset($params['subject']) && $params['subject'] ? $params['subject'] : 'Permohonan Pembelian Barang';
        $request_material['spk_id'] = $params['spk_id'];
        $request_material['date'] = $params['date'];
        $request_material['type'] = $params['type'];
        $request_material['cluster_id'] = $params['cluster_id'];
        $request_material['lot_id'] = isset($params['lot_id']) && $params['lot_id'] ? $params['lot_id'] : null;

        if (isset($params['id']) && $params['id']) {
            $id = $params['id'];
            unset($params['id']);

            $update = self::where('id', $id)->update($request_material);

            RequestMaterialItems::whereRequestMaterialId($id)->delete();

            foreach($params['item_inventory_id'] as $key => $val) {
                $request_material_item['inventory_id'] = $val;
                if (!is_numeric($val)) {
                    $request_material_item['inventory_id'] = 0;
                }
                $request_material_item['request_material_id'] = $id;
                $request_material_item['inventory_name'] = $params['item_name'][$key];
                $request_material_item['brand'] = $params['item_brand'][$key];
                $request_material_item['qty'] = $params['item_qty'][$key];

                RequestMaterialItems::create($request_material_item);
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Diubah!'
            ]);
        }


        $insert = self::create($request_material);

        if ($insert) {
            if (isset($params['item_inventory_id']) && count($params['item_inventory_id']) > 0) {
                foreach($params['item_inventory_id'] as $key => $val) {
                    $request_material_item['inventory_id'] = $val;
                    if (!is_numeric($val)) {
                        $request_material_item['inventory_id'] = 0;
                    }
                    $request_material_item['request_material_id'] = $insert->id;
                    $request_material_item['inventory_name'] = $params['item_name'][$key];
                    $request_material_item['brand'] = $params['item_brand'][$key];
                    $request_material_item['qty'] = $params['item_qty'][$key];

                    RequestMaterialItems::create($request_material_item);
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

        $db = self::select($_select)->addSelect('spk_workers.number as spk_number')
                    ->join('spk_workers', 'spk_workers.id', '=', 'request_materials.spk_id');

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
                    ->with('items.inventory.unit')
                    ->first();

        return response()->json($data);
    }

    public static function getAllResult($params)
    {
        unset($params['all']);

        $_select = [];
        foreach(array_values(self::mapSchema()) as $select) {
            $_select[] = $select['alias'];
        }

        $db = self::select($_select)->addSelect('spk_workers.number as spk_number')
                    ->join('spk_workers', 'spk_workers.id', '=', 'request_materials.spk_id');

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

    public static function selectClusterBySession()
    {
        $session = [
            '_login' => session()->get('_login'),
            '_id' => session()->get('_id'),
            '_name' => session()->get('_name'),
            '_email' => session()->get('_email'),
            '_username' => session()->get('_username'),
            '_phone' => session()->get('_phone'),
            '_role_id' => session()->get('_role_id'),
            '_role_name' => session()->get('_role_name'),
            '_cluster_id' => session()->get('_cluster_id')
        ];

        $qry = self::select('*');

        if ((isset($session['_role_id']) && $session['_role_id'] > 1) && (isset($session['_cluster_id']) && $session['_cluster_id'] > 0)) {
            $qry->where('cluster_id', $session['_cluster_id']);
        }

        return $qry->get();
    }
}
