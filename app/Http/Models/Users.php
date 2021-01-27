<?php

namespace App\Http\Models;

use App\Http\Models\Role;
use App\Http\Models\UserCompany;
use App\Http\Models\Users;
use DB;
use DataTables;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

class Users extends Model
{
    use HasApiTokens;
    protected $table = 'users';
    protected $fillable = [
        'id',
        'name',
        'email',
        'username',
        'phone',
        'email_verified_at',
        'password',
        'role_id',
        'is_suspend',
        'is_active',
        'is_deleted',
        'remember_token',
        'cluster_id',
        'created_at',
        'updated_at'
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

    public static function mapSchema($params = [], $user = [])
    {
        $model = new self;

        return [
            'id' => ['alias' => $model->table.'.id', 'type' => 'int'],
            'name' => ['alias' => $model->table.'.name', 'type' => 'string'],
            'email' => ['alias' => $model->table.'.email', 'type' => 'string'],
            'username' => ['alias' => $model->table.'.username', 'type' => 'string'],
            'phone' => ['alias' => $model->table.'.phone', 'type' => 'string'],
            'email_verified_at' => ['alias' => $model->table.'.email_verified_at', 'type' => 'string'],
            'password' => ['alias' => $model->table.'.password', 'type' => 'string'],
            'role_id' => ['alias' => $model->table.'.role_id', 'type' => 'int'],
            'is_suspend' => ['alias' => $model->table.'.is_suspend', 'type' => 'int'],
            'is_active' => ['alias' => $model->table.'.is_active', 'type' => 'int'],
            'is_deleted' => ['alias' => $model->table.'.is_deleted', 'type' => 'int'],
            'remember_token' => ['alias' => $model->table.'.remember_token', 'type' => 'string'],
            'cluster_id' => ['alias' => $model->table.'.cluster_id', 'type' => 'string'],
            'created_at' => ['alias' => $model->table.'.created_at', 'type' => 'string'],
            'updated_at' => ['alias' => $model->table.'.updated_at', 'type' => 'string']
        ];
    }

    public static function createOrUpdate($params, $method, $request)
    {
        $filename = null;

        if ($request->hasFile('files')) {
            $allowedfileExtension=['jpg','png'];
            $files = $request->file('files');

            $filename = $files->getClientOriginalName();
            $extension = $files->getClientOriginalExtension();
            $check = in_array($extension, $allowedfileExtension);
            if ($check) {
                $filename = md5(uniqid(rand(), true).time()).'.'.$extension;

                $files->storeAs('media/avatars', $filename);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Only upload jpg and png'
                ]);
            }
        }

        if (isset($params['_token']) && $params['_token']) {
            unset($params['_token']);
        }

        if (isset($params['id']) && $params['id']) {
            $employee_code = $params['id'];
            unset($params['id']);
            if (isset($params['password']) && $params['password']) {
                $params['password'] = bcrypt($params['password']);
            } else {
                unset($params['password']);
            }

            $user = self::where('id', $employee_code)->first();

            // if (isset($params['old_password']) && $params['old_password']) {
            //     if (!Hash::check($params['old_password'], $user->password)) {
            //         return response()->json([
            //             'status' => 'error',
            //             'message' => 'GAGAL! Password Lama Tidak Sesuai!'
            //         ]);
            //     }

            //     unset($params['old_password']);
            // }

            $update = self::where('id', $employee_code)->update($params);
            
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'User Sukses Diubah!'
            ]);
        }

        $validation = self::customValidation($params);

        if (!$validation['status']) {
            return response()->json([
                'status' => 'error',
                'message' => $validation['message']
            ]);
        }

        $users = self::create([
            'password' => bcrypt($params['password']),
            'name' => $params['name'],
            'email' => $params['email'],
            'username' => $params['username'],
            'phone' => $params['phone'],
            'role_id' => $params['role_id'],
            'cluster_id' => $params['cluster_id']
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User successfully registered'
        ]);
    }

    public static function authorize($params, $method, $request)
    {
        if(Auth::attempt(['email' => $params['email'], 'password' => $params['password']])){
            $user = Auth::user();

            $roles = Roles::where('id', $user['role_id'])->value('name');

            $employee = Employee::where('code', $user['employee_code'])->first();

            $user['role'] = $roles;
            $user['employee'] = $employee;

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil Login',
                'user' => $user
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Kombinasi password ataupun email tidak benar',
                'data' => null
            ], 200);
        }
    }

    public static function login_web($params, $method, $request)
    {
        if(Auth::attempt(['username' => $params['username'], 'password' => $params['password']])){
            $user = Auth::user();
            $user['role_name'] = Role::where('id', $user['role_id'])->value('name');

            $request->session()->flush();
            $request->session()->put('_login', true);
            $request->session()->put('_id', $user['id']);
            $request->session()->put('_name', $user['name']);
            $request->session()->put('_email', $user['email']);
            $request->session()->put('_username', $user['username']);
            $request->session()->put('_phone', $user['phone']);
            $request->session()->put('_role_id', $user['role_id']);
            $request->session()->put('_role_name', $user['role_name']);
            $request->session()->put('_cluster_id', $user['cluster_id']);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil Login',
                'user' => $user
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Kombinasi password ataupun email tidak benar',
                'data' => null
            ], 200);
        }
    }

    private static function customValidation($params)
    {
        $check_phone = self::where('phone', $params['phone'])->count();

        if ($check_phone > 0) {
            return [
                'status' => false,
                'message' => "Nomor HP sudah pernah digunakan!"
            ];
        }

        $check_email = self::where('email', $params['email'])->count();

        if ($check_email > 0) {
            return [
                'status' => false,
                'message' => "Email sudah pernah digunakan!"
            ];
        }

        return [
            'status' => true
        ];
    }

    public static function getAllResult($params)
    {
        $model = new self;
        
        unset($params['all']);

        $map = [
            'name' => $model->table.'.name',
            'email' => $model->table.'.email',
            'phone' => $model->table.'.phone',
            'role_id' => $model->table.'.role_id',
            'avatar' => $model->table.'.avatar'
        ];

        $db = self::select('name', 'email', 'phone', 'role_id', 'avatar')
                ->with(['roleDetails' => function($query) {
                    return $query->select(['id', 'name']);
                }]);

        if ($params) {
            foreach (array($params) as $k => $v) {
                foreach (array_keys($v) as $key => $row) {
                    if (isset($map[$row])) {
                        if (is_array(array_values($v)[$key])) {
                            if ($this->operators[array_keys(array_values($v)[$key])[$key]] != 'like') {
                                $db->where($map[$row], $this->operators[array_keys(array_values($v)[$key])[$key]], array_values(array_values($v)[$key])[$key]);
                            } else {
                                $db->where($map[$row], 'ilike', '%'.array_values($v)[$key].'%');
                            }
                        } else {
                            $db->where($map[$row], 'ilike', '%'.array_values($v)[$key].'%');
                        }
                    }
                }
            }
        }

        $db->orderBy('name', 'asc');
        
        return response()->json([
            'data' => $db->get()
        ]);
    }

    public static function resetPassword($params)
    {
        if ($params['password'] != $params['password_confirmation']) {
            // return response()->json([
            //     'status' => 'error',
            //     'message' => 'Tidak ada kecocokan pada password anda',
            //     'data' => null
            // ], 200);
            return \Redirect::route('master.user')->withMessage('Tidak ada kecocokan pada password anda');
        }

        $user = self::where('id', $params['user_id']);
        
        if ($user->update(['password' => $params['password']])) {
            return \Redirect::route('master.user')->withMessage('Successfully updated your password');
        } else {
            return \Redirect::route('master.user')->withMessage('ERROR DATABASE: Failed to update password!');
        }
    }

    public static function datatables($start, $length, $order, $dir, $search, $filter = '')
    {
        $totalData = self::count();

        $_select = [];
        foreach(array_values(self::mapSchema()) as $select) {
            $_select[] = $select['alias'];
        }

        $qry = self::select($_select)->addSelect('roles.name as role_name')->addSelect('clusters.name as cluster_name')
                    ->where('role_id', '<', 99)
                    ->join('roles', 'roles.id', '=', 'users.role_id')
                    ->leftJoin('clusters', 'clusters.id', 'users.cluster_id');
        
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
}