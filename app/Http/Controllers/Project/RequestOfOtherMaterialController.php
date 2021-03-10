<?php

namespace App\Http\Controllers\Project;

use Illuminate\Http\Request;
use App\Http\Models\Cluster\{Lot, Cluster};
use App\Http\Models\Ref\Province;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Models\Project\{RequestMaterialItems, RequestOfOtherMaterialItems, RequestOfOtherMaterials, WorkAgreements, SpkProjects};
use App\Http\Models\GeneralSetting\GeneralSetting;

class RequestOfOtherMaterialController extends Controller
{
    public function index()
    {
        return view('project.request_of_other_materials.request_of_other_material', [
            'company_logo' => GeneralSetting::getCompanyLogo(),
            'company_name' => GeneralSetting::getCompanyName()
        ]);
    }

    public function create()
    {
        // $spk = SpkProjects::get();
        $spk = WorkAgreements::get();
        $clusters = Cluster::selectClusterBySession();
        return view('project.request_of_other_materials.request_of_other_material_create', [
            'spk' => $spk,
            'clusters' => $clusters,
            'company_logo' => GeneralSetting::getCompanyLogo(),
            'company_name' => GeneralSetting::getCompanyName()
        ]);
    }

    public function insertData(Request $request)
    {
        $params = $request->all();

        return RequestOfOtherMaterials::createOrUpdate($params, $request->method(), $request);
    }

    public function edit($id)
    {
        $spk = WorkAgreements::get();
        $clusters = Cluster::selectClusterBySession();
        $lots = Lot::all();

        $material = RequestOfOtherMaterials::whereId($id)->first();
        $no = 1;
        $company_logo = GeneralSetting::getCompanyLogo();
        $company_name = GeneralSetting::getCompanyName();

        return view('project.request_of_other_materials.request_of_other_material_update', compact('spk', 'clusters', 'material', 'lots', 'no', 'company_logo', 'company_name'));
    }

    public function datatables(Request $request)
    {
        $session = [
            '_login' => session()->get('_login'),
            '_id' => session()->get('_id'),
            '_name' => session()->get('_name'),
            '_email' => session()->get('_email'),
            '_username' => session()->get('_username'),
            '_phone' => session()->get('_phone'),
            '_role_id' => session()->get('_role_id'),
            '_role_name' => session()->get('_role_name'),
            '_cluster_id' => session()->get('_cluster_id')
        ];

        $columns = [
            0 => 'request_of_other_materials.id'
        ];

        $dataOrder = [];

        $limit = $request->length;

        $start = $request->start;

        foreach ($request->order as $row) {
            $nestedOrder['column'] = $columns[$row['column']];
            $nestedOrder['dir'] = $row['dir'];

            $dataOrder[] = $nestedOrder;
        }

        $order = $dataOrder;

        $dir = $request->order[0]['dir'];

        $search = $request->search['value'];

        $filter = $request->only(['sDate', 'eDate']);

        $res = RequestOfOtherMaterials::datatables($start, $limit, $order, $dir, $search, $filter, $session);

        $data = [];
        $type = '';
        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData['id'] = $row['id'];
                $nestedData['number'] = $row['number'];
                $nestedData['title'] = $row['title'];
                $nestedData['subject'] = $row['subject'];
                $nestedData['date'] = $row['date'];
                $nestedData['spk_number'] = $row['spk_number'];
                if ($row['type'] == 'rap') {
                    $type = 'RAP';
                } else if ($row['type'] == 'non_rap') {
                    $type = 'NON RAP';
                } else if ($row['type'] == 'disposition') {
                    $type = 'DISPOSISI';
                }
                $nestedData['type'] = $type;
                $nestedData['action'] = '';
                $nestedData['action'] .='        <div class="dropdown dropdown-action">';
                $nestedData['action'] .='            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>';
                $nestedData['action'] .='            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(159px, 32px, 0px);">';
                $nestedData['action'] .='                <a class="dropdown-item" href="'.route('request__of_other_material.edit', $row['id']).'"><i class="fa fa-pencil m-r-5"></i> Edit</a>';
                $nestedData['action'] .='                <a class="dropdown-item" id="delete" href="#" data-toggle="modal" data-target="#delete_approve" data-id="'.$row['id'].'"><i class="fa fa-trash-o m-r-5"></i> Delete</a>';
                $nestedData['action'] .='                <a class="dropdown-item" target="_blank" href="'.url('/request-of-other-material-pdf/'.$row['id']).'"><i class="fa fa-print m-r-5"></i> Cetak</a>';
                $nestedData['action'] .='            </div>';
                $nestedData['action'] .='        </div>';
                $data[] = $nestedData;
            }
        }

        $json_data = [
            'draw'  => intval($request->draw),
            'recordsTotal'  => intval($res['totalData']),
            'recordsFiltered' => intval($res['totalFiltered']),
            'data'  => $data,
            'order' => $order
        ];

        return json_encode($json_data);
    }

    public function detail($id)
    {

    }

    public function get($id=null, Request $request)
    {
        $request = $request->all();

        if ($id != null) {
            $res = RequestOfOtherMaterials::getById($id, $request);
        } else if (isset($request['all']) && $request['all']) {
            $res = RequestOfOtherMaterials::getAllResult($request);
        } else {
            $res = RequestOfOtherMaterials::getPaginatedResult($request);
        }

        return $res;
    }

    public function post(Request $request)
    {
        $params = $request->all();
        return RequestOfOtherMaterials::createOrUpdate($params, $request->method(), $request);
    }

    public function put($id, Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;
        return RequestOfOtherMaterials::createOrUpdate($params, $request->method(), $request);
    }

    public function patch($id, Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;
        return RequestOfOtherMaterials::createOrUpdate($params, $request->method(), $request);
    }

    public function delete($id)
    {
        RequestOfOtherMaterialItems::where('request_of_other_material_id', $id)->delete();
        RequestOfOtherMaterials::destroy($id);

        return response()->json([
            'message' => 'data berhasil dihapus',
            'status' => 'success'
        ]);
    }

    public function generatePdf($id)
    {
        $pdf = PDF::setOptions(['isRemoteEnabled' => true])
        ->loadview('project.request_of_other_materials.request_of_other_material_pdf', [
            'data' => RequestOfOtherMaterialItems::generatePdf($id),
            'header' => GeneralSetting::getPdfHeaderImage(),
            'footer' => GeneralSetting::getPdfFooterImage(),
            'company_name' => GeneralSetting::getCompanyName(),
            'company_logo' => GeneralSetting::getCompanyLogo(),
        ]);
        return $pdf->download('Requesition Other Material Form.pdf');
    }
}
