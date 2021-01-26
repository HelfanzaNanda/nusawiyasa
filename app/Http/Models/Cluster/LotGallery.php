<?php

namespace App\Http\Models\Cluster;

use DB;
use File;
use Redirect;
use Illuminate\Database\Eloquent\Model;

class LotGallery extends Model
{
	protected $table = 'lot_galleries';

	protected $fillable = [
		'lot_id',
		'filename',
		'filepath',
		'is_cover'
    ];

    public static function mapSchema($params = [], $user = [])
    {
        $model = new self;

        return [
            'id' => ['alias' => $model->table.'.id', 'type' => 'int'],
			'lot_id' => ['alias' => $model->table.'.lot_id', 'type' => 'int'],
			'filename' => ['alias' => $model->table.'.filename', 'type' => 'string'],
			'filepath' => ['alias' => $model->table.'.filepath', 'type' => 'string'],
			'is_cover' => ['alias' => $model->table.'.is_cover', 'type' => 'int'],
			'created_at' => ['alias' => $model->table.'.created_at', 'type' => 'string'],
			'updated_at' => ['alias' => $model->table.'.updated_at', 'type' => 'string'],
        ];
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
                            if ($this->operators[array_keys(array_values($v)[$key])[$key]] != 'like') {
                                $db->where(self::mapSchema()[$row]['alias'], $this->operators[array_keys(array_values($v)[$key])[$key]], array_values(array_values($v)[$key])[$key]);
                            } else {
                                if (self::mapSchema()[$row]['type'] === 'int') {
                                    $db->where(self::mapSchema()[$row]['alias'], array_values($v)[$key]);
                                } else {
                                    $db->where(self::mapSchema()[$row]['alias'], 'like', '%'.array_values($v)[$key].'%');
                                }
                            }
                        } else {
                            if (self::mapSchema()[$row]['type'] === 'int') {
                                $db->where(self::mapSchema()[$row]['alias'], array_values($v)[$key]);
                            } else {
                                $db->where(self::mapSchema()[$row]['alias'], 'like', '%'.array_values($v)[$key].'%');
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

        if ($request->file('files')) {
            $allowedfileExtension = ['pdf','jpg','jpeg','png','docx'];
            $files = $request->file('files');

            $month_year_pfx = date('mY');
            $path_pfx = 'media/lot-gallery/'.$month_year_pfx;
            $path = '/storage/app/'.$path_pfx;

            File::makeDirectory($path, 0777, true, true);

            foreach($files as $key => $file) {
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $check = in_array($extension, $allowedfileExtension);
                if ($check) {
                    $filename = md5(uniqid(rand(), true).time()).'.'.$extension;

                    $file->move(storage_path('app').'/'.$path_pfx, $filename);
					
					self::create([
						'lot_id' => $params['lot_id'],
                		'filename' => $filename,
                		'filepath' => '/storage/media/lot-gallery/'.$month_year_pfx,
						'is_cover' => 0
					]);

                } else {
                    DB::rollBack();

                    return response()->json([
                        'status' => 'error',
                        'message' => 'Only upload jpg, png, and pdf'
                    ]);
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data Berhasil Disimpan'
        ]);
    }
}