<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Models\Customer\CustomerLot;
use App\Http\Models\Project\SpkProjects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;

class SPKProjectController extends Controller
{
    public function index()
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

        $lots = CustomerLot::select([
                        'customer_lots.id',
                        'clusters.name as cluster_name',
                        'lots.unit_number as unit_number',
                        'lots.block as unit_block',
                        'users.name as customer_name'
                    ])
                    ->join('customers', 'customers.id', '=', 'customer_lots.customer_id')
                    ->join('users', 'users.id', '=', 'customers.user_id')
                    ->join('lots', 'lots.id', '=', 'customer_lots.lot_id')
                    ->join('clusters', 'clusters.id', '=', 'lots.cluster_id');


        if ((isset($session['_role_id']) && in_array($session['_role_id'], [2, 3, 4, 5, 6])) && isset($session['_cluster_id'])) {
            $lots->where('lots.cluster_id', $session['_cluster_id']);
        }

        $lots = $lots->get();

        return view('project.spk_project', compact('lots'));
    }

    public function create()
    {

    }

    public function insertData(Request $request)
    {
        $params = $request->all();

        return SpkProjects::createOrUpdate($params, $request->method(), $request);
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
            0 => 'spk_projects.id'
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

        $res = SpkProjects::datatables($start, $limit, $order, $dir, $search, $filter, $session);

        $data = [];

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData['id'] = $row['id'];
                $nestedData['number'] = $row['number'];
                $nestedData['date'] = $row['date'];
                $nestedData['cluster_name'] = $row['cluster_name'];
                $nestedData['unit_number'] = $row['unit_number'];
                $nestedData['unit_block'] = $row['unit_block'];
                $nestedData['customer_name'] = $row['customer_name'];
                $nestedData['action'] = '';
                $nestedData['action'] .='        <div class="dropdown dropdown-action">';
                $nestedData['action'] .='            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>';
                $nestedData['action'] .='            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(159px, 32px, 0px);">';
                $nestedData['action'] .='                <a class="dropdown-item" id="edit" href="#" data-toggle="modal" data-target="#edit_leave" data-id="'.$row['id'].'"><i class="fa fa-pencil m-r-5"></i> Edit</a>';
                $nestedData['action'] .='                <a class="dropdown-item" id="delete" href="#" data-toggle="modal" data-target="#delete_approve"  data-id="'.$row['id'].'"><i class="fa fa-trash-o m-r-5"></i> Delete</a>';
                $nestedData['action'] .='                <a class="dropdown-item" target="_blank" href="'.url('/spk-project-pdf/'.$row['id']).'"><i class="fa fa-print m-r-5"></i> Cetak</a>';
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
        $spk = SpkProjects::where('id', $id)->first();
        $spk->date = date('Y-m-d', strtotime($spk->date));
        return response()->json($spk);
    }

    public function delete($id){
        SpkProjects::destroy($id);

        return response()->json([
            'message' => 'data berhasil dihapus',
            'status' => 'success'
        ]);
    }

    public function generatePdf($id)
    {
        //return json_encode(SpkProjects::generatePdf($id));
        // return view('project.spk_project_pdf', [
        //     'data' => SpkProjects::generatePdf($id)
        // ]);

        $pdf = PDF::setOptions(['isRemoteEnabled' => true])
        ->loadview('project.spk_project_pdf', [
            'data' => SpkProjects::generatePdf($id)
        ]);
        return $pdf->download('Surat Perintah Kerja.pdf');
    }

    public function get($id=null, Request $request)
    {
        $request = $request->all();

        if ($id != null) {
            $res = SpkProjects::getById($id, $request);
        } else if (isset($request['all']) && $request['all']) {
            $res = SpkProjects::getAllResult($request);
        } else {
            $res = SpkProjects::getPaginatedResult($request);
        }

        return $res;
    }
}
