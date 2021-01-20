<?php

namespace App\Http\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use DB;

class EmployeEducation extends Model
{
    protected $fillable = [
        'employe_id',
        'grade',
        'school',
        'graduation_year',
        'major'
    ];

    public static function createOrUpdate($params, $method, $request){
        DB::beginTransaction();

        if (isset($params['_token']) && $params['_token']) {
            unset($params['_token']);
        }

        if (isset($params['file']) && $params['file']) {
            unset($params['file']);
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
}
