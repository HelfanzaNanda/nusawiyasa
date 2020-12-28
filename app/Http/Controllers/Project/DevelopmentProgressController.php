<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Models\Customer\CustomerLot;
use App\Http\Models\Project\DevelopmentProgress;
use App\Http\Models\Project\DevelopmentProgressFiles;
use App\Http\Models\Project\DevelopmentProgressJobs;
use App\Http\Models\Project\DevelopmentProgressMaterials;
use App\Http\Models\Ref\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DevelopmentProgressController extends Controller
{
    public function index()
    {
        return view('project.development_progress');
    }

    public function create()
    {
        $lots = CustomerLot::bookingLotBySession();

        return view('project.development_progress_create', compact('lots'));
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
                $nestedData['date'] = $row['date'];
                $nestedData['percentage'] = $row['percentage'];
                $nestedData['status'] = $row['status'];
                $nestedData['action'] = '';
                $nestedData['action'] .='        <div class="dropdown dropdown-action">';
                $nestedData['action'] .='            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>';
                $nestedData['action'] .='            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(159px, 32px, 0px);">';
                $nestedData['action'] .='                <a class="dropdown-item" href="'.url('/development-progress/detail/'.$row['id']).'"><i class="fa fa-info m-r-5"></i> Detail</a>';
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

        return view('project.development_progress_detail', compact('data', 'files', 'jobs', 'materials'));
    }
}
