<?php

namespace App\Http\Models\Inventory;

use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string     $name
 * @property string     $city
 * @property string     $district
 * @property string     $subdistrict
 * @property string     $address
 * @property string     $phone
 * @property string     $email
 * @property int        $created_at
 * @property int        $updated_at
 */
class Suppliers extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'suppliers';

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
        'name', 'city', 'district', 'subdistrict', 'address', 'phone', 'email', 'debt', 'created_at', 'updated_at', 'pic_name', 'pic_phone'
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
        'name' => 'string', 'city' => 'string', 'district' => 'string', 'subdistrict' => 'string', 'address' => 'string', 'phone' => 'string', 'email' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at'
    ];

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
            'name' => ['alias' => $model->table.'.name', 'type' => 'string'],
            'city' => ['alias' => $model->table.'.city', 'type' => 'string'],
            'district' => ['alias' => $model->table.'.district', 'type' => 'string'],
            'subdistrict' => ['alias' => $model->table.'.subdistrict', 'type' => 'string'],
            'address' => ['alias' => $model->table.'.address', 'type' => 'string'],
            'phone' => ['alias' => $model->table.'.phone', 'type' => 'string'],
            'email' => ['alias' => $model->table.'.email', 'type' => 'string'],
            'debt' => ['alias' => $model->table.'.debt', 'type' => 'string'],
            'pic_name' => ['alias' => $model->table.'.pic_name', 'type' => 'string'],
            'pic_phone' => ['alias' => $model->table.'.pic_phone', 'type' => 'string']
        ];
    }
    // Scopes...

    // Functions ...

    // Relations ...

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

            $update = self::where('id', $id)->update($params);

            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Diubah!'
            ]);
        }

        $params['debt'] = isset($params['debt']) && $params['debt'] > 0 ? $params['debt'] : 0;
        $insert = self::create($params);

        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Data Berhasil Disimpan'
        ]);
    }
}
