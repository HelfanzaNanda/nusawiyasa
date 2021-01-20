<?php

namespace App\Http\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use DB;

class EmployeMedia extends Model
{
    protected $fillable =[
        'employe_id',
        'filepath',
        'filename',
        'type'
    ];
    public static function createOrUpdate($params, $method, $request){
        DB::beginTransaction();

        $filename = null;
        $filepath = null;
        $type = null;
        if($request->hasFile('file')){
            $allowedFileExtension = ['jpg', 'png', 'mp4', 'pdf'];

            $files = $request->file('file');
            $filename = $files->getClientOriginalName();
            $extension = $files->getClientOriginalExtension();
            $check = in_array($extension, $allowedFileExtension);
            if($check){
                $filename = md5(uniqid(rand(), true).time()).'.'.$extension;

                $files->storeAs('employe/media', $filename, ['disk' => 'public']);

                $filepath = 'storage/employe/media/'.$filename;
                $type = $extension;
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Only upload jpg, png and mp4'
                ]);
            }
        }

        $media = [
            'employe_id' => $params['employe_id'],
            'filename' => $params['filename'],
            'filepath' => $filepath,
            'type' => $type
        ];
        if (isset($params['_token']) && $params['_token']) {
            unset($params['_token']);
        }

        if (isset($params['id']) && $params['id']) {
            $id = $params['id'];
            unset($params['id']);

            $update = self::where('id', $id)->update($media);

            DB::commit();

            redirect()->back()->withSuccess('success');
        }

        $insert = self::create($media);

        DB::commit();
        return redirect()->back()->withSuccess('success');
    }
}
