<?php

namespace App\Http\Models\Project;

use App\Http\Models\Cluster\Lot;
use App\Http\Models\Project\RapItems;
use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string     $title
 * @property Date       $date
 * @property int        $cluster_id
 * @property int        $lot_id
 * @property int        $created_at
 * @property int        $updated_at
 */
class Rap extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'rap';

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
        'title', 'date', 'cluster_id', 'lot_id', 'total', 'created_at', 'updated_at'
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
        'title' => 'string', 'date' => 'date', 'cluster_id' => 'int', 'lot_id' => 'int', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'date', 'created_at', 'updated_at'
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
            'title' => ['alias' => $model->table.'.title', 'type' => 'string'],
            'date' => ['alias' => $model->table.'.date', 'type' => 'string'],
            'cluster_id' => ['alias' => $model->table.'.cluster_id', 'type' => 'string'],
            'lot_id' => ['alias' => $model->table.'.lot_id', 'type' => 'string'],
            'total' => ['alias' => $model->table.'.total', 'type' => 'string'],
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

        $qry = self::select($_select);
        
        if ((isset($session['_role_id']) && in_array($session['_role_id'], [2, 3, 4, 5, 6, 10])) && isset($session['_cluster_id'])) {
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

        if (isset($params['id']) && $params['id']) {
            $id = $params['id'];
            unset($params['id']);

            $rap_params['title'] = $params['title'];
            $rap_params['date'] = date('Y-m-d');
            $rap_params['cluster_id'] = $params['cluster_id'];
            // $rap_params['lot_id'] = $params['lot_id'];
            $rap_params['total'] = floatval(preg_replace('/[^\d\.\-]/', '', $params['total']));

            RapItems::whereRapId($id)->delete();
            
            $update = self::where('id', $id)->update($rap_params);

            foreach ($params['item_inventory_id'] as $key => $val) {
                RapItems::create([
                    'rap_id' => $id,
                    'inventory_id' => $params['item_inventory_id'][$key],
                    'qty' => $params['item_qty'][$key],
                    'price' => floatval(preg_replace('/[^\d\.\-]/', '', $params['item_price'][$key])),
                    'total' => floatval(preg_replace('/[^\d\.\-]/', '', $params['item_total'][$key]))
                ]);
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Diubah!'
            ]);
        }

        $rap_params['title'] = $params['title'];
        $rap_params['date'] = date('Y-m-d');
        // $rap_params['cluster_id'] = isset($params['lot_id']) && $params['lot_id'] > 0 ? Lot::where('id', $params['lot_id'])->value('cluster_id') : 0;
        $rap_params['cluster_id'] = $params['cluster_id'];
        // $rap_params['lot_id'] = $params['lot_id'];
        $rap_params['total'] = floatval(preg_replace('/[^\d\.\-]/', '', $params['total']));

        $insert = self::create($rap_params);

        if ($insert) {
            foreach ($params['item_inventory_id'] as $key => $val) {
                RapItems::create([
                    'rap_id' => $insert->id,
                    'inventory_id' => $params['item_inventory_id'][$key],
                    'qty' => $params['item_qty'][$key],
                    'price' => floatval(preg_replace('/[^\d\.\-]/', '', $params['item_price'][$key])),
                    'total' => floatval(preg_replace('/[^\d\.\-]/', '', $params['item_total'][$key]))
                ]);
            }
        }

        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Data Berhasil Disimpan'
        ]);
    }

    public function rapItem(){
        return $this->hasMany(RapItems::class, 'rap_id');
    }
}