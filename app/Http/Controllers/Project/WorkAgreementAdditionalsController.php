<?php

namespace App\Http\Controllers\Project;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\NumberGenerateController;
use App\Http\Models\GeneralSetting\GeneralSetting;
use App\Http\Models\Project\WorkAgreementAdditionals;

class WorkAgreementAdditionalsController extends Controller
{
    public function index($spk_worker_id)
    {
        return view('project.work_agreement_additional', [
            'spk_worker_id' => $spk_worker_id,
            'spk_worker_adtts' => WorkAgreementAdditionals::where('spk_worker_id', $spk_worker_id)->get(),
            'company_logo' => GeneralSetting::getCompanyLogo(),
            'company_name' => GeneralSetting::getCompanyName()
        ]);
    }

    public function insertData(Request $request, $spk_worker_id)
    {
        $params = $request->all();
        $params['spk_worker_id'] = $spk_worker_id;
        return WorkAgreementAdditionals::createOrUpdate($params,  $request->method(), $request);
    }

    public function delete($spk_worker_id, $id)
    {
        $wap = WorkAgreementAdditionals::destroy($id);
        if ($wap) {
            return [
                'status' => 'success'
            ];
        }
    }
}
