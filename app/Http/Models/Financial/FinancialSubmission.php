<?php

namespace App\Http\Models\Financial;

use Illuminate\Database\Eloquent\Model;
use App\Http\Models\Cluster\Cluster;
use App\Http\Models\Financial\FinancialSubmissionDetail;
use App\Http\Models\Users;

class FinancialSubmission extends Model
{
    protected $fillable = [
        'date',
        'number',
        'total',
        'cluster_id',
        'created_by_user_id',
        'approved_by_user_id',
        'received_by_user_id'
    ];

    private $operators = [
        "\$gt" => ">",
        "\$gte" => ">=",
        "\$lte" => "<=",
        "\$lt" => "<",
        "\$like" => "like",
        "\$not" => "<>",
        "\$in" => "in"
    ];

    public static function mapSchema($params = [], $user = []){
        $model = new self;

        return [
            'id' => ['alias' => $model->table.'.id', 'type' => 'int'],
            'date' => ['alias' => $model->table.'.date', 'type' => 'string'],
            'number' => ['alias' => $model->table.'.number', 'type' => 'string'],
            'total' => ['alias' => $model->table.'.total', 'type' => 'int'],
            'cluster_id' => ['alias' => $model->table.'.cluster_id', 'type' => 'int'],
            'created_by_user_id' => ['alias' => $model->table.'.created_by_user_id', 'type' => 'int'],
            'approved_by_user_id' => ['alias' => $model->table.'.approved_by_user_id', 'type' => 'int'],
            'received_by_user_id' => ['alias' => $model->table.'.received_by_user_id', 'type' => 'int'],
        ];
    }

    public static function getById($id, $params)
    {
        $db = self::where('id', $id)
            ->first();

        return response()->json($db);
    }

    public static function getPaginatedResult($params)
    {
        $paramsPage = isset($params['page']) ? $params['page'] : 0;

        unset($params['page']);

        $db = self::select(array_keys(self::mapSchema()));

        if ($params) {
            foreach (array($params) as $k => $v) {
                foreach (array_keys($v) as $key => $row) {
                    if (isset(self::mapSchema()[$row])) {
                        if (is_array(array_values($v)[$key])) {
                            if ($this->operators[array_keys(array_values($v)[$key])[$key]] != 'ilike') {
                                $db->where(self::mapSchema()[$row]['alias'], $this->operators[array_keys(array_values($v)[$key])[$key]], array_values(array_values($v)[$key])[$key]);
                            } else {
                                if (self::mapSchema()[$row]['type'] === 'int') {
                                    $db->where(self::mapSchema()[$row]['alias'], array_values($v)[$key]);
                                } else {
                                    $db->where(self::mapSchema()[$row]['alias'], 'ilike', '%'.array_values($v)[$key].'%');
                                }
                            }
                        } else {
                            if (self::mapSchema()[$row]['type'] === 'int') {
                                $db->where(self::mapSchema()[$row]['alias'], array_values($v)[$key]);
                            } else {
                                $db->where(self::mapSchema()[$row]['alias'], 'ilike', '%'.array_values($v)[$key].'%');
                            }
                        }
                    }
                }
            }
        }
        $countAll = $db->count();
        $currentPage = $paramsPage > 0 ? $paramsPage - 1 : 0;
        $page = $paramsPage > 0 ? $paramsPage + 1 : 2; 
        $nextPage = env('APP_URL').'/api/users?page='.$page;
        $prevPage = env('APP_URL').'/api/users?page='.($currentPage < 1 ? 1 : $currentPage);
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

    public static function getAllResult($params)
    {
        unset($params['all']);

        $db = self::select(array_keys(self::mapSchema()));

        if ($params) {
            foreach (array($params) as $k => $v) {
                foreach (array_keys($v) as $key => $row) {
                    if (isset(self::mapSchema()[$row])) {
                        if (is_array(array_values($v)[$key])) {
                            if ($this->operators[array_keys(array_values($v)[$key])[$key]] != 'ilike') {
                                $db->where(self::mapSchema()[$row]['alias'], $this->operators[array_keys(array_values($v)[$key])[$key]], array_values(array_values($v)[$key])[$key]);
                            } else {
                                if (self::mapSchema()[$row]['type'] === 'int') {
                                    $db->where(self::mapSchema()[$row]['alias'], array_values($v)[$key]);
                                } else {
                                    $db->where(self::mapSchema()[$row]['alias'], 'ilike', '%'.array_values($v)[$key].'%');
                                }
                            }
                        } else {
                            if (self::mapSchema()[$row]['type'] === 'int') {
                                $db->where(self::mapSchema()[$row]['alias'], array_values($v)[$key]);
                            } else {
                                $db->where(self::mapSchema()[$row]['alias'], 'ilike', '%'.array_values($v)[$key].'%');
                            }
                        }
                    }
                }
            }
        }

        return response()->json($db->get());
    }

    public static function createOrUpdate($params, $method, $request)
    {
        if (isset($params['_token']) && $params['_token']) {
            unset($params['_token']);
        }

        if (isset($params['id']) && $params['id']) {
            $id = $params['id'];
            unset($params['id']);

            $data['date'] = $params['date'];
            $data['number'] = $params['number'];
            $data['total'] = floatval(preg_replace('/[^\d\.\-]/', '', $params['total']));
            $data['cluster_id'] = $params['cluster_id'];

            FinancialSubmissionDetail::where('financial_submission_id', $id)->delete();

            foreach ($params['item_value'] as $key => $val) {
                FinancialSubmissionDetail::create([
                    'financial_submission_id' => $id,
                    'value' => $params['item_value'][$key],
                    'qty' => $params['item_qty'][$key],
                    'unit' => $params['item_unit'][$key],
                    'price' => floatval(preg_replace('/[^\d\.\-]/', '', $params['item_price'][$key])),
                    'total_price' => floatval(preg_replace('/[^\d\.\-]/', '', $params['item_total_price'][$key])),
                    'note' => $params['item_note'][$key]
                ]);
            }            

            $update = self::where('id', $id)->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Diubah!'
            ]);
        }
        $params['total'] = floatval(preg_replace('/[^\d\.\-]/', '', $params['total']));
        $insert = self::create($params);

        if($insert){
            foreach ($params['item_value'] as $key => $val) {
                FinancialSubmissionDetail::create([
                    'financial_submission_id' => $insert->id,
                    'value' => $params['item_value'][$key],
                    'qty' => $params['item_qty'][$key],
                    'unit' => $params['item_unit'][$key],
                    'price' => floatval(preg_replace('/[^\d\.\-]/', '', $params['item_price'][$key])),
                    'total_price' => floatval(preg_replace('/[^\d\.\-]/', '', $params['item_total_price'][$key])),
                    'note' => $params['item_note'][$key]
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data Berhasil Disimpan'
        ]);
    }

    public static function datatables($start, $length, $order, $dir, $search, $filter = '', $session = [])
    {
        $totalData = self::count();

        $_select = [];
        foreach(array_values(self::mapSchema()) as $select) {
            $_select[] = $select['alias'];
        }

        $qry = self::select($_select);

        if ((isset($session['_role_id']) && $session['_role_id'] > 1) && isset($session['_cluster_id'])) {
            $qry->where('id', $session['_cluster_id']);
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

        if ((isset($session['_role_id']) && $session['_role_id'] > 1) && isset($session['_cluster_id'])) {
            $qry->where('id', $session['_cluster_id']);
        }

        return $qry->get();
    }

    public function cluster(){
        return $this->belongsTo(Cluster::class, 'cluster_id');
    }

    public function createdByUser(){
        return $this->belongsTo(Users::class, 'created_by_user_id');
    }

    public function approvedByUser(){
        return $this->belongsTo(Users::class, 'upproved_by_user_id');
    }

    public function receivedByUser(){
        return $this->belongsTo(Users::class, 'received_by_user_id');
    }

    public function detail(){
        return $this->hasMany(FinancialSubmissionDetail::class, 'financial_submission_id');
    }
}
