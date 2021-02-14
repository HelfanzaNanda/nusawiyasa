<?php

namespace App\Http\Controllers\Project;

use Illuminate\Http\Request;
use App\Http\Models\Ref\Province;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Models\Customer\Customer;
use App\Http\Models\Customer\CustomerLot;
use App\Http\Models\Project\WorkAgreements;

class WorkAgreementController extends Controller
{
    public function index()
    {
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
        
        if ((isset($session['_role_id']) && in_array($session['_role_id'], [2, 3, 4, 5, 6, 10])) && isset($session['_cluster_id'])) {
            $lots->where('lots.cluster_id', $session['_cluster_id']);
        }

        return view('project.work_agreement', [
            'lots' => $lots->get()
        ]);
    }

    public function create()
    {

    }

    public function insertData(Request $request)
    {
        $params = $request->all();

        return WorkAgreements::createOrUpdate($params, $request->method(), $request);
    }

    public function edit($id)
    {

    }

    public function datatables(Request $request)
    {
        $_login = session()->get('_login');
        $_id = session()->get('_id');
        $_name = session()->get('_name');
        $_email = session()->get('_email');
        $_username = session()->get('_username');
        $_phone = session()->get('_phone');
        $_role_id = session()->get('_role_id');
        $_role_name = session()->get('_role_name');

        $columns = [
            0 => 'spk_workers.id'
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

        $res = WorkAgreements::datatables($start, $limit, $order, $dir, $search, $filter);

        $data = [];

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData['id'] = $row['id'];
                $nestedData['number'] = $row['number'];
                $nestedData['title'] = $row['title'];
                $nestedData['date'] = $row['date'];
                $nestedData['customer_name'] = $row['customer_name'];
                $nestedData['action'] = '';
                $nestedData['action'] .='        <div class="dropdown dropdown-action">';
                $nestedData['action'] .='            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>';
                $nestedData['action'] .='            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(159px, 32px, 0px);">';
                $nestedData['action'] .='                <a class="dropdown-item" id="edit" href="#" data-toggle="modal" data-target="#edit_leave" data-id="'.$row['id'].'"><i class="fa fa-pencil m-r-5"></i> Edit</a>';
                $nestedData['action'] .='                <a class="dropdown-item" id="delete" href="#" data-toggle="modal" data-target="#delete_approve"  data-id="'.$row['id'].'"><i class="fa fa-trash-o m-r-5"></i> Delete</a>';
                $nestedData['action'] .='                <a class="dropdown-item" href="'.url('/work-agreement/'.$row['id'].'/additional').'" > SPK Tambahan</a>';
                
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

    public function get($id)
    {
        return WorkAgreements::findOrFail($id);
    }

    public function delete($id)
    {
        $wa = WorkAgreements::findOrFail($id);
        $wa->work_agreement_additionals()->delete();
        $wa->delete();
        if ($wa) {
            return [
                'status' => 'success'
            ];
        }
    }
}
