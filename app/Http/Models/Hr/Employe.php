<?php

namespace App\Http\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Http\Models\Hr\EmployeEducation;
use App\Http\Models\Hr\EmployeMedia;

class Employe extends Model
{
    protected $fillable =[
        'fullname',
        'email',
        'bank_account',
        'date_birth',
        'place_birth',
        'joined_at',
        'resign_at',
        'avatar',
        'employe_status',
        'gender',
        'religion',
        'mariage_status',
        'father_name',
        'mother_name',
        'identity_type',
        'identity_card_number',
        'phone_number',
        'emergency_number',
        'emergency_name',
        'emergency_relation',
        'current_address_kecamatan',
        'current_address_kelurahan',
        'current_address_rt',
        'current_address_rw',
        'current_address_province',
        'current_address_city',
        'current_address_street',
        'bank_name',
        'owner_bank_number',
        'twitter',
        'facebook',
        'instagram',
        'youtube',
        'linkedin',
        'blood_type'
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

    public static function mapSchema($params = [], $users = []){
        $model = new self;

        return [
            'id' => ['alias' => $model->table.'.id', 'type' => 'int'],
            'fullname' => ['alias' => $model->table.'.fullname', 'type'=> 'string'],
            'email' => ['alias' => $model->table.'.email', 'type'=> 'string'],
            'bank_account' => ['alias' => $model->table.'.bank_account', 'type'=> 'string'],
            'date_birth' => ['alias' => $model->table.'.date_birth', 'type'=> 'string'],
            'bank_place' => ['alias' => $model->table.'.place_birth', 'type'=> 'string'],
            'bank_account' => ['alias' => $model->table.'.bank_account', 'type'=> 'string'],
            'joined_at' => ['alias' => $model->table.'.joined_at', 'type'=> 'string'],
            'bank_account' => ['alias' => $model->table.'.bank_account', 'type'=> 'string'],
            'resign_at' => ['alias' => $model->table.'.resign_at', 'type'=> 'string'],
            'avatar' => ['alias' => $model->table.'.avatar', 'type'=> 'string'],
            'employe_status' => ['alias' => $model->table.'.employe_status', 'type'=> 'string'],
            'gender' => ['alias' => $model->table.'.gender', 'type'=> 'string'],
            'religion' => ['alias' => $model->table.'.religion', 'type'=> 'string'],
            'mariage_status' => ['alias' => $model->table.'.mariage_status', 'type'=> 'string'],
            'father_name' => ['alias' => $model->table.'.father_name', 'type'=> 'string'],
            'mother_name' => ['alias' => $model->table.'.mother_name', 'type'=> 'string'],
            'identity_type' => ['alias' => $model->table.'.identity_type', 'type'=> 'string'],
            'identity_card_number' => ['alias' => $model->table.'.identity_card_number', 'type'=> 'string'],
            'phone_number' => ['alias' => $model->table.'.phone_number', 'type'=> 'string'],
            'emergency_number' => ['alias' => $model->table.'.emergency_number', 'type'=> 'string'],
            'emergency_name' => ['alias' => $model->table.'.emergency_name', 'type'=> 'string'],
            'emergency_relation' => ['alias' => $model->table.'.emergency_relation', 'type'=> 'string'],
            'current_address_kecamatan' => ['alias' => $model->table.'.current_address_kecamatan', 'type'=> 'string'],
            'current_address_kelurahan' => ['alias' => $model->table.'.current_address_kelurahan', 'type'=> 'string'],
            'current_address_rt' => ['alias' => $model->table.'.current_address_rt', 'type'=> 'string'],
            'current_address_rw' => ['alias' => $model->table.'.current_address_rw', 'type'=> 'string'],
            'current_address_province' => ['alias' => $model->table.'.current_address_province', 'type'=> 'string'],
            'current_address_city' => ['alias' => $model->table.'.current_address_city', 'type'=> 'string'],
            'current_address_street' => ['alias' => $model->table.'.current_address_street', 'type'=> 'string'],
            'bank_name' => ['alias' => $model->table.'.bank_name', 'type'=> 'string'],
            'owner_bank_number' => ['alias' => $model->table.'.owner_bank_number', 'type'=> 'string'],
            'twitter' => ['alias' => $model->table.'.twitter', 'type'=> 'string'],
            'facebook' => ['alias' => $model->table.'.facebook', 'type'=> 'string'],
            'instagram' => ['alias' => $model->table.'.instagram', 'type'=> 'string'],
            'youtube' => ['alias' => $model->table.'.youtube', 'type'=> 'string'],
            'linkedin' => ['alias' => $model->table.'.linkedin', 'type'=> 'string'],
            'blood_type' => ['alias' => $model->table.'.blood_type', 'type'=> 'string'],
            'created_at' => ['alias' => $model->table.'.created_at', 'type' => 'string'],
            'updated_at' => ['alias' => $model->table.'.updated_at', 'type' => 'string'],
        ];
    }

    public static function datatables($start, $length, $order, $dir, $search, $filter = '')
    {
        $totalData = self::count();

        $_select = [];
        foreach(array_values(self::mapSchema()) as $select) {
            $_select[] = $select['alias'];
        }

        $qry = self::select($_select);
        
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
        $nextPage = env('APP_URL').'/api/employes?page='.$page;
        $prevPage = env('APP_URL').'/api/employes?page='.($currentPage < 1 ? 1 : $currentPage);
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

        return response()->json([
            'data' => $db->get()
        ]);
    }

    public static function createOrUpdate($params, $method, $request){
        DB::beginTransaction();

        if($request->hasFile('file')){
            $allowedFileExtension = ['jpg', 'png'];

            $files = $request->file('file');
            $filename = $files->getClientOriginalName();
            $extension = $files->getClientOriginalExtension();
            $check = in_array($extension, $allowedFileExtension);
            if($check){
                $filename = md5(uniqid(rand(), true).time()).'.'.$extension;

                $files->storeAs('employe/avatar', $filename, ['disk' => 'public']);

                $params['avatar'] = 'storage/employe/avatar/'.$filename;
            }
            unset($params['file']);
        }

        if (isset($params['_token']) && $params['_token']) {
            unset($params['_token']);
        }

        if (isset($params['id']) && $params['id']) {
            $id = $params['id'];
            unset($params['id']);

            $update = self::where('id', $id)->update($params);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Diubah!'
            ]);
        }

        $insert = self::create($params);

        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Data Berhasil Disimpan'
        ]);
    }

    public function educations(){
        return $this->hasMany(EmployeEducation::class);
    }

    public function medias(){
        return $this->hasMany(EmployeMedia::class);
    }
}
