<?php

namespace App\Http\Models\Project;

use DB;
use File;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int        $customer_id
 * @property int        $lot_id
 * @property string     $filename
 * @property string     $filepath
 * @property string     $filetype
 * @property int        $status
 * @property string     $type
 * @property string     $note
 * @property boolean    $is_active
 * @property boolean    $is_deleted
 * @property int        $created_at
 * @property int        $updated_at
 */
class CustomerConfirmations extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'customer_confirmations';

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
        'customer_id', 'lot_id', 'filename', 'filepath', 'filetype', 'status', 'type', 'note', 'is_active', 'is_deleted', 'created_at', 'updated_at'
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
        'customer_id' => 'int', 'lot_id' => 'int', 'filename' => 'string', 'filepath' => 'string', 'filetype' => 'string', 'status' => 'int', 'type' => 'string', 'note' => 'string', 'is_active' => 'boolean', 'is_deleted' => 'boolean', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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

    // Scopes...

    // Functions ...

    // Relations ...

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

        if ($request->file('file')) {
            $allowedfileExtension = ['pdf', 'jpg', 'png', 'docx', 'jpeg', 'txt', 'xls', 'xlsx', 'csv'];
            $files = $request->file('file');

            $month_year_pfx = date('mY');
            $path_pfx = 'public/media/customer-confirmation/'.$month_year_pfx;
            $path = '/storage/'.$path_pfx;

            File::makeDirectory($path, 0777, true, true);

            $filename = $files->getClientOriginalName();
            $extension = $files->getClientOriginalExtension();
            $check = in_array(strtolower($extension), $allowedfileExtension);
            if ($check) {
                $filename = md5(uniqid(rand(), true).time()).'.'.$extension;

                $files->move(storage_path('app').'/'.$path_pfx, $filename);

                self::create([
                    'customer_id' => $params['customer_id'],
                    'lot_id' => $params['lot_id'],
                    'filename' => $filename,
                    'filepath' => '/storage/media/customer-confirmation/'.$month_year_pfx,
                    'filetype' => $extension,
                    'status' => $params['status'],
                    'type' => 'confirmation_bundle',
                    'note' => '-'
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
