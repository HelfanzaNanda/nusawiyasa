<?php

namespace App\Http\Controllers\Project;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Models\Ref\Province;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Models\Customer\CustomerLot;
use App\Http\Models\Project\DevelopmentProgress;
use App\Http\Models\GeneralSetting\GeneralSetting;
use App\Http\Models\Project\DevelopmentProgressJobs;
use App\Http\Models\Project\DevelopmentProgressFiles;
use App\Http\Models\Project\DevelopmentProgressMaterials;

class DevelopmentProgressController extends Controller
{
    public function index()
    {
        return view('project.development_progress', [
            'company_logo' => GeneralSetting::getCompanyLogo(),
            'company_name' => GeneralSetting::getCompanyName()
        ]);
    }

    public function create()
    {
        $lots = CustomerLot::bookingLotBySession();

        return view('project.development_progress_create', [
            'lots' => $lots,
            'company_logo' => GeneralSetting::getCompanyLogo(),
            'company_name' => GeneralSetting::getCompanyName()
        ]);
    }

    public function insertData(Request $request)
    {
        $params = $request->all();

        return DevelopmentProgress::createOrUpdate($params, $request->method(), $request);
    }

    public function edit($id)
    {

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
            0 => 'development_progress.id'
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

        $res = DevelopmentProgress::datatables($start, $limit, $order, $dir, $search, $filter, $session);

        $data = [];

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData['id'] = $row['id'];
                $nestedData['customer_name'] = $row['customer_name'];
                $nestedData['cluster_name'] = $row['cluster_name'];
                $nestedData['block'] = $row['block'];
                $nestedData['unit_number'] = $row['unit_number'];
                $nestedData['date'] = date('d M Y', strtotime($row['date']));
                $nestedData['percentage'] = $row['percentage'];
                $nestedData['status'] = $row['status'];
                $nestedData['action'] = '';
                $nestedData['action'] .='        <div class="dropdown dropdown-action">';
                $nestedData['action'] .='            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>';
                $nestedData['action'] .='            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(159px, 32px, 0px);">';
                $nestedData['action'] .='                <a class="dropdown-item" href="'.url('/development-progress/detail/'.$row['id']).'"><i class="fa fa-info m-r-5"></i> Detail</a>';
                $nestedData['action'] .='                <a class="dropdown-item" target="_blank" href="'.url('development-progress/pdf/'.$row['id']).'"><i class="fa fa-print m-r-5"></i> Cetak</a>';
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
        $data = DevelopmentProgress::customerDetail($id);

        $files = DevelopmentProgressFiles::where('development_progress_id', $id)->get();
        $jobs = DevelopmentProgressJobs::where('development_progress_id', $id)->get();
        $materials = DevelopmentProgressMaterials::select(['inventories.name as inventory_name', 'development_progress_materials.qty as qty', 'development_progress_materials.type as type', 'inventory_units.name as inventory_unit'])->join('inventories', 'inventories.id', '=', 'development_progress_materials.inventory_id')->join('inventory_units', 'inventory_units.id', '=', 'inventories.unit_id')->where('development_progress_materials.development_progress_id', $id)->get();
        $company_logo = GeneralSetting::getCompanyLogo();
        $company_name = GeneralSetting::getCompanyName();
        return view('project.development_progress_detail', compact('data', 'files', 'jobs', 'materials', 'company_logo', 'company_name'));
    }

    public function pdf($id){
        $dev = DevelopmentProgress::whereId($id)->first();
        $dev['tanggal'] = $dev->date->isoFormat('dddd, D MMMM Y');
        $dev['file'] = $dev->files;
        $dev['cluster'] = $dev->cluster;
        $dev['lot'] = $dev->lot;
        $dev['job'] = $dev->jobs;
        $max = 0;
        $service = DevelopmentProgressMaterials::whereDevelopmentProgressId($id)->where('type', 'service')->get();
        $tool = DevelopmentProgressMaterials::whereDevelopmentProgressId($id)->where('type', 'tools')->get();
        $material = DevelopmentProgressMaterials::whereDevelopmentProgressId($id)->where('type', 'materials')->get();

        $max = ($service->count() > $max) ? $service->count() : $max;
        $max = ($tool->count() > $max) ? $tool->count() : $max;
        $max = ($material->count() > $max) ? $material->count() : $max;

        $eq = null;
        $dev['host'] = substr_replace(asset(''), "", -1);
        for ($i=0; $i < $max; $i++) { 
            $eq[$i] = array(
                'no' => ($i+1),
                'material_name' => (isset($material[$i])) ? $material[$i]->inventory->name : '-',
                'material_qty' => (isset($material[$i])) ? $material[$i]->qty.' '.$material[$i]->inventory->unit->name : '-',
                'tool_name' => (isset($tool[$i])) ? $tool[$i]->inventory->name : '-',
                'tool_qty' => (isset($tool[$i])) ? $tool[$i]->qty : '-',
                'service_name' => (isset($service[$i])) ? $service[$i]->inventory->name : '-',
                'service_qty' => (isset($service[$i])) ? $service[$i]->qty : '-',
                
            );
        }
        $dev['eq'] = $eq;
        $customPaper = array(0,0,360,360);
        $pdf = PDF::setOptions(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true, 'setPaper' => $customPaper])
        ->loadview('project.development_progress_pdf', [
            'data' => $dev,
            'header' => GeneralSetting::getPdfHeaderImage(),
            'footer' => GeneralSetting::getPdfFooterImage(),
            'company_name' => GeneralSetting::getCompanyName(),
            'company_logo' => GeneralSetting::getCompanyLogo(),
        ]);
        return $pdf->download('Development progress '.$dev['date'].'.pdf');
        
    }

    public function get($id=null, Request $request)
    {
        $request = $request->all();

        if ($id != null) {
            $res = DevelopmentProgress::getById($id, $request);
        } else if (isset($request['all']) && $request['all']) {
            $res = DevelopmentProgress::getAllResult($request);
        } else {
            $res = DevelopmentProgress::getPaginatedResult($request);
        }

        return $res;
    }
}
