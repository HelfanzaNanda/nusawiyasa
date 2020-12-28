<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Models\Role;
use App\Http\Models\Users;
use App\Http\Models\Cluster\Cluster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $roles = Role::get();
        $clusters = Cluster::get();
        return view('setting.user.'.__FUNCTION__, compact('roles', 'clusters'));
    }

    public function create()
    {
        return view('setting.user.'.__FUNCTION__);
    }

    public function insertData(Request $request)
    {
        $params = $request->all();

        return Users::createOrUpdate($params, $request->method(), $request);
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
            0 => 'users.id'
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

        $res = Users::datatables($start, $limit, $order, $dir, $search, $filter);

        $data = [];

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData['id'] = $row['id'];
                $nestedData['name'] = $row['name'];
                $nestedData['cluster_name'] = $row['cluster_name'];
                $nestedData['role_name'] = $row['role_name'];
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
            $res = Users::getById($id, $request);
        } else if (isset($request['all']) && $request['all']) {
            $res = Users::getAllResult($request);
        } else {
            $res = Users::getPaginatedResult($request);
        }

        return $res;
    }

    public function post(Request $request)
    {
        $params = $request->all();
        return Users::createOrUpdate($params, $request->method(), $request);
    }

    public function put($id, Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;
        return Users::createOrUpdate($params, $request->method(), $request);
    }

    public function patch($id, Request $request)
    {
        $params = $request->all();
        $params['id'] = $id;
        return Users::createOrUpdate($params, $request->method(), $request);
    }

    public function delete($id, Request $request)
    {

    }
}
