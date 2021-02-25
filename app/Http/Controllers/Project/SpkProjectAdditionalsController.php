<?php

namespace App\Http\Controllers\Project;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\GeneralSetting\GeneralSetting;
use App\Http\Models\Project\SpkProjectAdditionals;

class SpkProjectAdditionalsController extends Controller
{
    public function index($spk_project_id)
    {
        return view('project.spk_project_additional', [
            'spk_project_id' => $spk_project_id,
            'company_logo' => GeneralSetting::getCompanyLogo(),
            'company_name' => GeneralSetting::getCompanyName(),
            'spk_project_adtts' => SpkProjectAdditionals::where('spk_project_id', $spk_project_id)->get()
        ]);
    }

    public function insertData(Request $request, $spk_project_id)
    {
        $params = $request->all();
        $params['spk_project_id'] = $spk_project_id;
        return SpkProjectAdditionals::createOrUpdate($params,  $request->method(), $request);
    }

    public function delete($spk_project_id, $id)
    {
        $spkAdt = SpkProjectAdditionals::destroy($id);
        if ($spkAdt) {
            return [
                'status' => 'success'
            ];
        }
    }
}
