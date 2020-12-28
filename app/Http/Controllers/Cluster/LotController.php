<?php

namespace App\Http\Controllers\Cluster;

use App\Http\Controllers\Controller;
use App\Http\Models\Cluster\Cluster;
use App\Http\Models\Cluster\Lot;
use App\Http\Models\Ref\Province;
use App\Http\Models\Ref\RefLotStatuses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LotController extends Controller
{
    public function index()
    {
        $clusters = Cluster::get();
        
        return view('cluster.lot', compact('clusters'));
    }

    public function create()
    {

    }

    public function insertData(Request $request)
    {
        $params = $request->all();
        return Lot::createOrUpdate($params, $request->method(), $request);
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
            0 => 'lots.id'
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

        $res = Lot::datatables($start, $limit, $order, $dir, $search, $filter, $session);

        $data = [];

        $status_collection = RefLotStatuses::get();

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData['id'] = $row['id'];
                $nestedData['cluster_name'] = $row['cluster_name'];
                $nestedData['block'] = $row['block'];
                $nestedData['unit_number'] = $row['unit_number'];
                $nestedData['total_floor'] = $row['total_floor'];
                $nestedData['building_area'] = $row['building_area'];
                $nestedData['surface_area'] = $row['surface_area'];
                $nestedData['status'] = $status_collection->where('id', $row['lot_status'])->values()[0]['name'];
                $nestedData['action'] = '';
                $nestedData['action'] .='<div class="dropdown dropdown-action">';
                $nestedData['action'] .='<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>';
                $nestedData['action'] .='<div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(159px, 32px, 0px);">';
                if (!$row['booking_id']) {
                    $nestedData['action'] .='<a href="'.url('/bookings/'.$row['id']).'" class="dropdown-item"><i class="fa fa-home m-r-5"></i> Booking</a>';
                }
                $nestedData['action'] .='<a href="#" class="dropdown-item" id="edit-data" data-id="'.$row['id'].'"><i class="fa fa-pencil m-r-5"></i> Edit</a>';
                $nestedData['action'] .='<a href="#" class="dropdown-item" id="delete-data" data-id="'.$row['id'].'"><i class="fa fa-trash-o m-r-5"></i> Delete</a>';

                $nestedData['action'] .='</div>';
                $nestedData['action'] .='</div>';
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
}
