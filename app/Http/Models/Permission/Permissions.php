<?php

namespace App\Http\Models\Permission;

use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permissions';

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
        'name', 'guard_name', 'created_at', 'updated_at', 'deleted_at'
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
        'name' => 'string', 'guard_name' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp', 'deleted_at' => 'timestamp'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $appends = ['permissions'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = false;

    public static function mapSchema($params = [], $user = [])
    {
        $model = new self;
        return [
            'id' => ['alias' => $model->table.'.id', 'type' => 'int'],
            'name' => ['alias' => $model->table.'.name', 'type' => 'string'],
            'guard_name' => ['alias' => $model->table.'.guard_name', 'type' => 'string'],
            // 'created_at' => ['alias' => $model->table.'.created_at', 'type' => 'string'],
            // 'updated_at' => ['alias' => $model->table.'.updated_at', 'type' => 'string'],
            // 'deleted_at' => ['alias' => $model->table.'.deleted_at', 'type' => 'string'],
        ];
    }

    public static function datatables($start, $length, $order, $dir, $search, $filter = '', $session = [])
    {
        $totalData = self::count();

        $_select = [];
        foreach(array_values(self::mapSchema()) as $select) {
            $_select[] = $select['alias'];
        }
        $qry = self::select($_select)
        ->addSelect(DB::raw('CONCAT("[", GROUP_CONCAT(DISTINCT roles.name) ,"]") as roles'))
        ->join('role_has_permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
        ->join('roles', 'role_has_permissions.role_id', '=', 'roles.id')
        ->groupBy('permissions.id');
        $attrs = [];
        foreach ($qry->get() as $q) {
            $attrs[explode('.',$q['name'])[0]][] = [
                'id' => $q['id'],
                'name' => $q['name'],
                'guard_name' => $q['guard_name'],
                'roles' => $q['roles'],
            ];
        }
        $query = collect($attrs);
        $totalFiltered = $query->count();

        if (empty($search)) {

            if ($length > 0) {
                $query->skip($start)
                    ->take($length);
            }

            foreach ($order as $row) {
                $query->orderBy($row['column'], $row['dir']);
            }

        } else {
            foreach (array_values(self::mapSchema()) as $key => $val) {
                if ($key < 1) {
                    $query->whereRaw('('.$val['alias'].' LIKE \'%'.$search.'%\'');
                } else if (count(array_values(self::mapSchema())) == ($key + 1)) {
                    $query->orWhereRaw($val['alias'].' LIKE \'%'.$search.'%\')');
                } else {
                    $query->orWhereRaw($val['alias'].' LIKE \'%'.$search.'%\'');
                }
            }

            $totalFiltered = $query->count();

            if ($length > 0) {
                $query->skip($start)
                    ->take($length);
            }

            foreach ($order as $row) {
                $query->orderBy($row['column'], $row['dir']);
            }
        }

        return [
            'data' => $attrs,
            'totalData' => $totalData,
            'totalFiltered' => $totalFiltered
        ];
    }

    // public function roleHasPermissions()
    // {
    //     return $this->hasMany(RoleHasPermissions::class, 'permission_id');
    // }

    public static function getPermissionsAttribute()
    {
        // return self::roles->map(function ($role) {
        //     return $role->permissions;
        // })->collapse()->pluck('name')->unique();

        return self::roles;
    }
}


