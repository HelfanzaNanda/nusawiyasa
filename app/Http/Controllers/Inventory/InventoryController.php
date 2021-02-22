<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Models\Cluster\Cluster;
use App\Http\Models\Inventory\Inventories;
use App\Http\Models\Inventory\InventoryCategories;
use App\Http\Models\Inventory\InventoryUnits;
use App\Http\Models\Ref\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        $units = InventoryUnits::where('is_deleted', 0)->get();
        $categories = InventoryCategories::where('is_deleted', 0)->get();
        $clusters = Cluster::selectClusterBySession();

        return view('inventory.inventory', compact('units', 'categories', 'clusters'));
    }

    public function create()
    {

    }

    public function insertData(Request $request)
    {
        $params = $request->all();

        return Inventories::createOrUpdate($params, $request->method(), $request);
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
            0 => 'inventories.id'
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

        $res = Inventories::datatables($start, $limit, $order, $dir, $search, $filter, null, $session);

        $data = [];

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData['id'] = $row['id'];
                $nestedData['name'] = $row['name'];
                $nestedData['stock'] = floatval($row['stock']);
                // $nestedData['category_name'] = $row['category_name'];
                $nestedData['cluster_name'] = $row['cluster_name'];
                $nestedData['unit_name'] = $row['unit_name'];
                $nestedData['purchase_price'] = $row['purchase_price'];
                $nestedData['type'] = $row['type'];
                $nestedData['brand'] = $row['brand'];
                $nestedData['action'] = '';
                $nestedData['action'] .='        <div class="dropdown dropdown-action">';
                $nestedData['action'] .='            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>';
                $nestedData['action'] .='            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(159px, 32px, 0px);">';
                $nestedData['action'] .='                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_leave" id="edit" data-id="'.$row['id'].'"><i class="fa fa-pencil m-r-5"></i> Edit</a>';
                $nestedData['action'] .='                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_approve" id="delete" data-id="'.$row['id'].'"><i class="fa fa-trash-o m-r-5"></i> Delete</a>';
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

        //return $request;
        if ($id != null) {
            $res = Inventories::getById($id, $request);
        } else if (isset($request['cluster_id']) && isset($request['name']) && $request['cluster_id'] && $request['name']) {
            $res = Inventories::getByCluster($request);
        }else if (isset($request['all']) && $request['all']) {
            $res = Inventories::getAllResult($request);
        } else {
            $res = Inventories::getPaginatedResult($request);
        }

        return $res;
    }

    public function post(Request $request)
    {
        $params = $request->all();
        return Inventories::createOrUpdate($params, $request->method(), $request);
    }

    public function put($id, Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;
        return Inventories::createOrUpdate($params, $request->method(), $request);
    }

    public function patch($id, Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;
        return Inventories::createOrUpdate($params, $request->method(), $request);
    }

    public function delete($id)
    {
        Inventories::destroy($id);

        return response()->json([
            'message' => 'data berhasil dihapus',
            'status' => 'success'
        ]);
    }
}
