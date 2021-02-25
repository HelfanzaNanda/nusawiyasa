<?php

namespace App\Http\Controllers\Ref;

use Illuminate\Http\Request;
use App\Http\Models\Ref\City;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Models\GeneralSetting\GeneralSetting;

class CityController extends Controller
{
    public function index()
    {
        return view('ref.city', [
            'company_logo' => GeneralSetting::getCompanyLogo(),
            'company_name' => GeneralSetting::getCompanyName()
        ]);
    }

    public function cityByProvince($id)
    {
        return City::cityByProvince($id);
    }
}