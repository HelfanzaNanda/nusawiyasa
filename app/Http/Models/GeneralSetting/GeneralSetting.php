<?php

namespace App\Http\Models\GeneralSetting;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    protected $table = 'general_settings';
    protected $fillable = [ 'name', 'key', 'value', 'type', 'description'];

    public static function uploadImage($image, $folder)
    {
        $allowedfileExtension = ['jpg','png', 'jpeg'];
        $month_year_pfx = date('mY');
        $path_pfx = 'media/'.$folder.'/'.$month_year_pfx;
        $path = '/storage/'.$path_pfx;

        File::makeDirectory($path, 0777, true, true);

        $filename = $image->getClientOriginalName();

        $extension = $image->getClientOriginalExtension();
        $check = in_array($extension, $allowedfileExtension);
        if ($check) {
            $filename = md5(uniqid(rand(), true).time()).'.'.$extension;
            $image->move(storage_path('app').'/public/'.$path_pfx, $filename);
            return [
                'status' => true,
                'value' => $path_pfx.'/'.$filename,
            ];

        } else {
            return [
                'status' => false,
            ];
        }
    }

    public static function updateData($params, $request)
    {
        DB::beginTransaction();
        try {
            foreach ($params as $key => $value) {
                $data = self::where('key', $key)->first();
                if ($data) {
                    if ($data->type == 'file') {
                        $uploadFile = self::uploadImage($request->file($key), 'general_setting');
                        if ($uploadFile['status']) {
                            $data->update([
                                'value' => $uploadFile['value']
                            ]);
                        }else{
                            return [
                                'status' => 'error',
                                'message' => 'gagal upload image, harus bertipe jpeg, jpg, png'
                            ];
                        }
                    }else{
                        $data->update([
                            'value' => $value ?? $data->value
                        ]);
                    }
                }
            }

            DB::commit();
            return [ 
                'status' => 'success',
                'message' => 'berhasil mengubah data!'
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public static function getCompanyName()
    {
        return self::where('key', 'company_name')->pluck('value')->first();
    }

    public static function getCompanyLogo()
    {
        return self::where('key', 'logo')->pluck('value')->first();
    }

    public static function getPdfHeaderImage()
    {
        return self::where('key', 'pdf_header_image')->pluck('value')->first();
    }

    public static function getPdfFooterImage()
    {
        return self::where('key', 'pdf_footer_image')->pluck('value')->first();
    }
}
