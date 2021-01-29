<?php

namespace App\Http\Models\Customer;

use App\Http\Models\Users;
use DB;
use Illuminate\Database\Eloquent\Model;
use App\Http\Models\Project\DevelopmentProgress;
use App\Http\Models\Customer\CustomerLot;
use Redirect;

class Customer extends Model
{
	protected $table = 'customers';

	protected $fillable = [
        'user_id',
        'place_of_birth',
        'date_of_birth',
        'province',
        'city',
        'district',
        'subdistrict',
        'address',
        'occupation',
        'is_active',
        'is_deleted'
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

    public function user()
    {
        return $this->hasOne('App\Http\Models\Users', 'id', 'user_id');
    }

    public static function mapSchema($params = [], $user = [])
    {
        $model = new self;

        return [
            'id' => ['alias' => $model->table.'.id', 'type' => 'int'],
            'user_id' => ['alias' => $model->table.'.user_id', 'type' => 'int'],
            'place_of_birth' => ['alias' => $model->table.'.place_of_birth', 'type' => 'string'],
            'date_of_birth' => ['alias' => $model->table.'.date_of_birth', 'type' => 'string'],
            'province' => ['alias' => $model->table.'.province', 'type' => 'string'],
            'city' => ['alias' => $model->table.'.city', 'type' => 'string'],
            'district' => ['alias' => $model->table.'.district', 'type' => 'string'],
            'subdistrict' => ['alias' => $model->table.'.subdistrict', 'type' => 'string'],
            'address' => ['alias' => $model->table.'.address', 'type' => 'string'],
            'occupation' => ['alias' => $model->table.'.occupation', 'type' => 'string'],
            'is_active' => ['alias' => $model->table.'.is_active', 'type' => 'int'],
            'is_deleted' => ['alias' => $model->table.'.is_deleted', 'type' => 'int'],
            'created_at' => ['alias' => $model->table.'.created_at', 'type' => 'string'],
            'updated_at' => ['alias' => $model->table.'.updated_at', 'type' => 'string'],
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

        return response()->json([
            'data' => $db->get()
        ]);
    }

    public static function createOrUpdate($params, $method, $request)
    {
        DB::beginTransaction();
        $filename = null;

        if (isset($params['_token']) && $params['_token']) {
            unset($params['_token']);
        }

        if (isset($params['id']) && $params['id']) {

            $id = $params['id'];
            unset($params['id']);
            $userId = self::whereId($id)->first()->user_id;

            $user['name'] = $params['name'];
            $user['email'] = $params['email'];
            $user['username'] = $params['email'];
            $user['phone'] = $params['phone'];
            unset($params['name']);
            unset($params['phone']);
            unset($params['email']);

            //dd($params);
            $update = self::where('id', $id)->update($params);
            $data = Users::where('id',$userId)->update($user);
            
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Diubah!'
            ]);
        }

        $user['name'] = $params['name'];
        $user['email'] = $params['email'];
        $user['username'] = $params['email'];
        $user['phone'] = $params['phone'];
        $user['password'] = bcrypt('123456');
        $user['role_id'] = 99;
        
        $create_user = Users::create($user);
        $params['user_id'] = $create_user->id;

        unset($params['name']);
        unset($params['email']);
        unset($params['phone']);

        $insert = self::create($params);

        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Data Berhasil Disimpan'
        ]);
    }

    public static function datatables($start, $length, $order, $dir, $search, $filter = '')
    {
        $totalData = self::count();

        $_select = [];
        foreach(array_values(self::mapSchema()) as $select) {
            $_select[] = $select['alias'];
        }

        $qry = self::select($_select)
                ->addSelect('users.name')
                ->addSelect('users.email')
                ->addSelect('users.phone')
                ->join('users', 'users.id', '=', 'customers.user_id');
        
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

    public function developmentProgress(){
        return $this->hasMany(DevelopmentProgress::class);
    }

    public function customerLot(){
        return $this->hasOne(customerLot::class, 'customer_id');
    }
}
