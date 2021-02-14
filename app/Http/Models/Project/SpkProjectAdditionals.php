<?php

namespace App\Http\Models\Project;

use File;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string     $number
 * @property int        $template_id
 * @property string     $title
 * @property string     $subject
 * @property Date       $date
 * @property string     $params
 * @property int        $created_at
 * @property int        $updated_at
 */
class SpkProjectAdditionals extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'spk_project_additionals';

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
        'spk_project_id', 'number', 'title', 'date', 'filepath', 'filename', 'create_by_user_id', 'approved_by_user_id', 'received_by_user_id', 'created_at', 'updated_at'
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
        'filepath' => 'string',  'filename' => 'string',  'spk_project_id' => 'int',  'create_by_user_id' => 'int', 'approved_by_user_id' => 'int', 'received_by_user_id' => 'int',
        'number' => 'string', 'title' => 'string', 'date' => 'date', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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

    public static function mapSchema($params = [], $user = [])
    {
        $model = new self;

        return [
            'id' => ['alias' => $model->table.'.id', 'type' => 'int'],
            'spk_project_id' => ['alias' => $model->table.'.spk_project_id', 'type' => 'int'],
            'title' => ['alias' => $model->table.'.title', 'type' => 'string'],
            'number' => ['alias' => $model->table.'.number', 'type' => 'string'],
            'date' => ['alias' => $model->table.'.date', 'type' => 'string'],
            'filepath' => ['alias' => $model->table.'.filepath', 'type' => 'string'],
            'filename' => ['alias' => $model->table.'.filename', 'type' => 'string'],
            'created_by_user_id' => ['alias' => $model->table.'.created_by_user_id', 'type' => 'int'],
            'approved_by_user_id' => ['alias' => $model->table.'.approved_by_user_id', 'type' => 'int'],
            'received_by_user_id' => ['alias' => $model->table.'.received_by_user_id', 'type' => 'int'],
            'created_at' => ['alias' => $model->table.'.created_at', 'type' => 'string'],
            'updated_at' => ['alias' => $model->table.'.updated_at', 'type' => 'string']
        ];
    }

    public static function datatables($start, $length, $order, $dir, $search, $filter = '', $session = [])
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

            if ($request->file('file')) {
                $allowedfileExtension = ['pdf'];
                $file = $request->file('file');
                $month_year_pfx = date('mY');
                $path_pfx = 'public/media/work-agreement/'.$month_year_pfx;
                $path = '/storage/'.$path_pfx;
                File::makeDirectory($path, 0777, true, true);
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $check = in_array($extension, $allowedfileExtension);
                if ($check) {
                    $filename = md5(uniqid(rand(), true).time()).'.'.$extension;
                    $file->move(storage_path('app').'/'.$path_pfx, $filename);
                    self::where('id', $id)->update([
                        'title' => $params['title'],
                        'number' => $params['number'],
                        'date' => $params['date'],
                        'filepath' => '/storage/media/work-agreement/'.$month_year_pfx,
                        'filename' => $filename,
                    ]);
                } else {
                    DB::rollBack();
    
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Only upload pdf'
                    ]);
                }
    
                DB::commit();
    
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data Berhasil DiUbah!'
                ]);
            }

            self::where('id', $id)->update([
                'title' => $params['title'],
                'number' => $params['number'],
                'date' => $params['date'],
            ]);
            
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Diubah!'
            ]);
        }

        if ($request->file('file')) {
            $allowedfileExtension = ['pdf'];
            $file = $request->file('file');
            $month_year_pfx = date('mY');
            $path_pfx = 'public/media/work-agreement/'.$month_year_pfx;
            $path = '/storage/'.$path_pfx;
            File::makeDirectory($path, 0777, true, true);
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $check = in_array($extension, $allowedfileExtension);
            if ($check) {
                $filename = md5(uniqid(rand(), true).time()).'.'.$extension;
                $file->move(storage_path('app').'/'.$path_pfx, $filename);
                self::create([
                    'title' => $params['title'],
                    'number' => $params['number'],
                    'date' => $params['date'],
                    'spk_project_id' => $params['spk_project_id'],
                    'filepath' => '/storage/media/work-agreement/'.$month_year_pfx,
                    'filename' => $filename,
                ]);
            } else {
                DB::rollBack();

                return response()->json([
                    'status' => 'error',
                    'message' => 'Only upload pdf'
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil Disimpan'
            ]);
        }

        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Data Berhasil Disimpan'
        ]);
    }

    // Scopes...

    // Functions ...

    // Relations ...
}
