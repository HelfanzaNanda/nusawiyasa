<?php

namespace App\Http\Models\Ref;

use DB;
use Redirect;
use Illuminate\Database\Eloquent\Model;
use App\Http\Models\Ref\Province;

class City extends Model
{
	protected $table = 'cities';

    public static function cityByProvince($id)
    {
        return response()->json(
            self::where('province_code', $id)->get()
        );
    }
    public function province(){
        return $this->belongsTo(Province::class, 'province_code', 'code');
    }
}