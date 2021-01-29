<?php

namespace App\Http\Models\Project;

use App\Http\Models\Customer\CustomerLot;
use App\Http\Models\Inventory\Inventories;
use App\Http\Models\Project\DevelopmentProgressFiles;
use App\Http\Models\Project\DevelopmentProgressJobs;
use App\Http\Models\Project\DevelopmentProgressMaterials;
use App\Http\Models\Cluster\Cluster;
use App\Http\Models\Cluster\Lot;
use DB;
use File;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

/**
 * @property int        $cluster_id
 * @property int        $lot_id
 * @property Date       $date
 * @property int        $user_created_id
 * @property int        $user_consultant_id
 * @property int        $user_supervisor_id
 * @property int        $percentage
 * @property int        $customer_id
 * @property boolean    $customer_approval
 * @property int        $status
 * @property int        $created_at
 * @property int        $updated_at
 */
class DevelopmentProgress extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'development_progress';

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
        'cluster_id', 'lot_id', 'date', 'user_created_id', 'user_consultant_id', 'user_supervisor_id', 'percentage', 'customer_id', 'customer_approval', 'status', 'created_at', 'updated_at'
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
        'cluster_id' => 'int', 'lot_id' => 'int', 'date' => 'date', 'user_created_id' => 'int', 'user_consultant_id' => 'int', 'user_supervisor_id' => 'int', 'percentage' => 'int', 'customer_id' => 'int', 'customer_approval' => 'boolean', 'status' => 'int', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'date', 'created_at', 'updated_at'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;

    private $operators = [
        "\$gt" => ">",
        "\$gte" => ">=",
        "\$lte" => "<=",
        "\$lt" => "<",
        "\$like" => "like",
        "\$not" => "<>",
        "\$in" => "in"
    ];

    public static function mapSchema($params = [], $user = [])
    {
        $model = new self;

        return [
            'id' => ['alias' => $model->table.'.id', 'type' => 'int'],
            'cluster_id' => ['alias' => $model->table.'.cluster_id', 'type' => 'int'],
            'lot_id' => ['alias' => $model->table.'.lot_id', 'type' => 'int'],
            'date' => ['alias' => $model->table.'.date', 'type' => 'date'],
            'user_created_id' => ['alias' => $model->table.'.user_created_id', 'type' => 'int'],
            'user_consultant_id' => ['alias' => $model->table.'.user_consultant_id', 'type' => 'int'],
            'user_supervisor_id' => ['alias' => $model->table.'.user_supervisor_id', 'type' => 'int'],
            'percentage' => ['alias' => $model->table.'.percentage', 'type' => 'int'],
            'customer_id' => ['alias' => $model->table.'.customer_id', 'type' => 'int'],
            'customer_approval' => ['alias' => $model->table.'.customer_approval', 'type' => 'string'],
            'status' => ['alias' => $model->table.'.status', 'type' => 'string'],
            'created_at' => ['alias' => $model->table.'.created_at', 'type' => 'string'],
            'updated_at' => ['alias' => $model->table.'.updated_at', 'type' => 'string']
        ];
    }

    public static function customerDetail($id)
    {
        $_select = [];
        foreach(array_values(self::mapSchema()) as $select) {
            $_select[] = $select['alias'];
        }

        $qry = self::select($_select)
                ->addSelect('customers.user_id as user_id')
                ->addSelect('users.name as customer_name')
                ->addSelect('clusters.name as cluster_name')
                ->addSelect('lots.block')
                ->addSelect('lots.unit_number')
                ->addSelect('lots.surface_area')
                ->addSelect('lots.building_area')
                ->leftJoin('customers', 'customers.id', '=', 'development_progress.customer_id')
                ->leftJoin('users', 'users.id', '=', 'customers.user_id')
                ->leftJoin('lots', 'lots.id', '=', 'development_progress.lot_id')
                ->leftJoin('clusters', 'clusters.id', '=', 'lots.cluster_id')
                ->where('development_progress.id', $id)
                ->first();

        return $qry;
    }

    public static function datatables($start, $length, $order, $dir, $search, $filter = '')
    {
        $totalData = self::count();

        $_select = [];
        foreach(array_values(self::mapSchema()) as $select) {
            $_select[] = $select['alias'];
        }

        $qry = self::select($_select)
                ->addSelect('customers.user_id as user_id')
                ->addSelect('users.name as customer_name')
                ->addSelect('clusters.name as cluster_name')
                ->addSelect('lots.block')
                ->addSelect('lots.unit_number')
                ->addSelect('lots.surface_area')
                ->addSelect('lots.building_area')
                ->leftJoin('customers', 'customers.id', '=', 'development_progress.customer_id')
                ->leftJoin('users', 'users.id', '=', 'customers.user_id')
                ->leftJoin('lots', 'lots.id', '=', 'development_progress.lot_id')
                ->leftJoin('clusters', 'clusters.id', '=', 'lots.cluster_id');

        if ((isset($session['_role_id']) && in_array($session['_role_id'], [2, 3, 4, 5, 6])) && isset($session['_cluster_id'])) {
            $qry->where('development_progress.cluster_id', $session['_cluster_id']);
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
        $_id = session()->get('_id');
        // dd($params);
        DB::beginTransaction();
        $filename = null;

        if (isset($params['_token']) && $params['_token']) {
            unset($params['_token']);
        }

        if (isset($params['id']) && $params['id']) {
            $id = $params['id'];
            unset($params['id']);

            $update = self::where('id', $id)->update($params);

            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Diubah!'
            ]);
        }

        $customer_lot = CustomerLot::select(['customer_lots.customer_id', 'clusters.id as cluster_id'])
                            ->join('lots', 'lots.id', '=', 'customer_lots.lot_id')
                            ->join('clusters', 'clusters.id', '=', 'lots.cluster_id')
                            ->where('customer_lots.lot_id', $params['lot_id'])
                            ->first();

        $development_progress['cluster_id'] = $customer_lot['cluster_id'];
        $development_progress['lot_id'] = $params['lot_id'];
        $development_progress['date'] = $params['date'];
        $development_progress['user_created_id'] = $_id;
        $development_progress['user_consultant_id'] = 0;
        $development_progress['user_supervisor_id'] = 0;
        $development_progress['percentage'] = $params['percentage'];
        $development_progress['customer_id'] = $customer_lot['customer_id'];
        $development_progress['customer_approval'] = 1;
        $development_progress['status'] = 6;

        $insert = self::create($development_progress);

        if ($insert) {
            if (isset($params['job_work']) && is_array($params['job_work'])) {
                foreach($params['job_work'] as $k_job => $jobs) {
                    DevelopmentProgressJobs::create([
                        'development_progress_id' => $insert->id,
                        'jobs' => $jobs,
                        'location' => $params['job_location'][$k_job],
                        'volume' => $params['job_volume'][$k_job],
                        'note' => $params['job_note'][$k_job],
                    ]);
                }
            }

            $material_types = ['material', 'tools', 'service'];

            foreach ($material_types as $material_type) {
                if (isset($params[$material_type.'_inventory_id']) && is_array($params[$material_type.'_inventory_id'])) {
                    foreach($params[$material_type.'_inventory_id'] as $k_material => $materials) {
                        $inventory_type = Inventories::where('id', $materials)->value('type');
                        DevelopmentProgressMaterials::create([
                            'development_progress_id' => $insert->id,
                            'inventory_id' => $materials,
                            'qty' => $params[$material_type.'_qty'][$k_material],
                            'type' => $inventory_type
                        ]);
                    }
                }
            }

            if ($request->file('file')) {
                $allowedfileExtension = ['pdf', 'jpg', 'png', 'docx', 'jpeg', 'txt'];
                $files = $request->file('file');

                $month_year_pfx = date('mY');
                $path_pfx = 'public/media/development_progress/'.$month_year_pfx;
                $path = '/storage/'.$path_pfx;

                File::makeDirectory($path, 0777, true, true);

                foreach($files as $keyFile => $file){
                    $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $check = in_array($extension, $allowedfileExtension);
                    if ($check) {
                        $filename = md5(uniqid(rand(), true).time()).'.'.$extension;

                        $file->move(storage_path('app').'/'.$path_pfx, $filename);

                        DevelopmentProgressFiles::create([
                            'development_progress_id' => $insert->id,
                            'filename' => $filename,
                            'filepath' => '/storage/media/development_progress/'.$month_year_pfx,
                            'filetype' => $extension
                        ]);

                    } else {
                        DB::rollBack();

                        return response()->json([
                            'status' => 'error',
                            'message' => 'Only upload jpg, png, and pdf'
                        ]);
                    }
                }

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Data Berhasil Disimpan'
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

        if (isset($params['detail']) && $params['detail']) {
            $db->with('files')->with('jobs')->with('materials');
        }

        if ($params) {
            foreach (array($params) as $k => $v) {
                foreach (array_keys($v) as $key => $row) {
                    if (isset(self::mapSchema()[$row])) {
                        if (is_array(array_values($v)[$key])) {
                            if ($this->operators[array_keys(array_values($v)[$key])[$key]] != 'like') {
                                $db->where(self::mapSchema()[$row]['alias'], $this->operators[array_keys(array_values($v)[$key])[$key]], array_values(array_values($v)[$key])[$key]);
                            } else {
                                if (self::mapSchema()[$row]['type'] === 'int' || self::mapSchema()[$row]['type'] === 'date') {
                                    $db->where(self::mapSchema()[$row]['alias'], array_values($v)[$key]);
                                } else {
                                    $db->where(self::mapSchema()[$row]['alias'], 'like', '%'.array_values($v)[$key].'%');
                                }
                            }
                        } else {
                            if (self::mapSchema()[$row]['type'] === 'int' || self::mapSchema()[$row]['type'] === 'date') {
                                $db->where(self::mapSchema()[$row]['alias'], array_values($v)[$key]);
                            } else {
                                $db->where(self::mapSchema()[$row]['alias'], 'like', '%'.array_values($v)[$key].'%');
                            }
                        }
                    }
                }
            }
        }

        // dd($db->toSql(), $db->getBindings());

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

        if (isset($params['detail']) && $params['detail']) {
            $db->with('files')->with('jobs')->with('materials');
        }

        if(isset($params['report']) && $params['report']){
            $db->with('files')->with('lot')->with('cluster');
        }
        
        
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
                            if (self::mapSchema()[$row]['type'] === 'int' || self::mapSchema()[$row]['type'] === 'date') {
                                $db->where(self::mapSchema()[$row]['alias'], array_values($v)[$key]);
                            } else {
                                $db->where(self::mapSchema()[$row]['alias'], 'like', '%'.array_values($v)[$key].'%');
                            }
                        }
                    }
                }
            }
        }

        return response()->json($db->get());
    }

    public function cluster(){
        return $this->belongsTo(Cluster::class);
    }

    public function lot(){
        return $this->belongsTo(Lot::class);
    }

    public function files(){
        return $this->hasMany(DevelopmentProgressFiles::class);
    }
    public function jobs(){
        return $this->hasMany(DevelopmentProgressJobs::class);
    }
    public function materials(){
        return $this->hasMany(DevelopmentProgressMaterials::class);
    }
}
