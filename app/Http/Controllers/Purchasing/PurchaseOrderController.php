<?php

namespace App\Http\Controllers\Purchasing;

use App\Http\Controllers\Controller;
use App\Http\Models\Cluster\Lot;
use App\Http\Models\Inventory\Suppliers;
use App\Http\Models\Purchase\PurchaseOrders;
use App\Http\Models\Ref\Province;
use App\Http\Models\Ref\RefGeneralStatuses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        return view('purchasing.purchase_order');
    }

    public function create()
    {
        $suppliers = Suppliers::get();
        $lots = Lot::select(['lots.id', 'clusters.name', 'lots.block', 'lots.unit_number'])->join('clusters', 'clusters.id', '=', 'lots.cluster_id')->get();

        return view('purchasing.purchase_order_create', compact('suppliers', 'lots'));
    }

    public function insertData(Request $request)
    {
        $params = $request->all();

        return PurchaseOrders::createOrUpdate($params, $request->method(), $request);
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
            0 => 'purchase_orders.id'
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

        $res = PurchaseOrders::datatables($start, $limit, $order, $dir, $search, $filter);

        $data = [];
        
        $status_collection = RefGeneralStatuses::get();


        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData['id'] = $row['id'];
                $nestedData['number'] = $row['number'];
                $nestedData['fpp_number'] = $row['fpp_number'];
                $nestedData['supplier_name'] = $row['supplier_name'];
                $nestedData['type'] = $row['type'];
                $nestedData['date'] = $row['date'];
                $nestedData['status'] = $status_collection->where('id', $row['status'])->values()[0]['name'];
                $nestedData['total'] = number_format(floatval($row['total']));
                $nestedData['action'] = '';
                $nestedData['action'] .='        <div class="dropdown dropdown-action">';
                $nestedData['action'] .='            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>';
                $nestedData['action'] .='            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(159px, 32px, 0px);">';
                $nestedData['action'] .='                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_leave"><i class="fa fa-pencil m-r-5"></i> Edit</a>';
                $nestedData['action'] .='                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_approve"><i class="fa fa-trash-o m-r-5"></i> Delete</a>';
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
            $res = PurchaseOrders::getById($id, $request);
        } else if (isset($request['all']) && $request['all']) {
            $res = PurchaseOrders::getAllResult($request);
        } else {
            $res = PurchaseOrders::getPaginatedResult($request);
        }

        return $res;
    }

    public function post(Request $request)
    {
        $params = $request->all();
        return PurchaseOrders::createOrUpdate($params, $request->method(), $request);
    }

    public function put($id, Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;
        return PurchaseOrders::createOrUpdate($params, $request->method(), $request);
    }

    public function patch($id, Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;
        return PurchaseOrders::createOrUpdate($params, $request->method(), $request);
    }

    public function delete($id, Request $request)
    {

    }
}
