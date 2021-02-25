<?php

namespace App\Http\Controllers\GeneraSetting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\GeneralSetting\GeneralSetting;

class GeneraSettingController extends Controller
{
    public function index()
    {
        return view('setting.general_setting.index', [
            'datas' => GeneralSetting::all(),
            'company_logo' => GeneralSetting::getCompanyLogo(),
            'company_name' => GeneralSetting::getCompanyName()
        ]);
    }

    public function update(Request $request)
    {
        $params = $request->all();
        //return $params;
        return GeneralSetting::updateData($params, $request);
    }
}
