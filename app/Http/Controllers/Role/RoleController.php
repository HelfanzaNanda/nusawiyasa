<?php

namespace App\Http\Controllers\Role;

use App\Http\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\GeneralSetting\GeneralSetting;

class RoleController extends Controller
{
    public function index()
    {
        return view('setting.role.role', [
            'company_logo' => GeneralSetting::getCompanyLogo(),
            'company_name' => GeneralSetting::getCompanyName()
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'role' => ['required', 'unique:roles,name'],
        ];
        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'unique' => ':attribute sudah pernah di tambahkan',
        ];
        $customAttributes = [
            'role' => 'Hak Akses',
        ];
        $this->validate($request, $rules, $messages, $customAttributes);
        Role::create([
            'name' => $request->role,
            'guard_name' => 'web',
            'description' => $request->role,
        ]);

        return [
            'status' => 'success'
        ];
    }

    public function update(Request $request)
    {
        $rules = [
            'role' => ['required', 'unique:roles,name,' . $request->id],
        ];
        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'unique' => ':attribute sudah pernah di tambahkan',
        ];
        $customAttributes = [
            'role' => 'Hak Akses',
        ];
        $this->validate($request, $rules, $messages, $customAttributes);
        Role::where('id', $request->id)->update([
            'name' => $request->role,
            'guard_name' => 'web',
            'description' => $request->role,
        ]);

        return [
            'status' => 'success'
        ];
    }

    public function get($id)
    {
        return Role::findOrFail($id);
    }

    public function delete($id)
    {
        Role::destroy($id);
        return [
            'status' => 'success'
        ];
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
            0 => 'roles.id'
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

        $res = Role::datatables($start, $limit, $order, $dir, $search, $filter);

        $data = [];

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData['id'] = $row['id'];
                $nestedData['name'] = $row['name'];
                $nestedData['guard_name'] = $row['guard_name'];
                $nestedData['action'] = '';
                $nestedData['action'] .='        <div class="dropdown dropdown-action">';
                $nestedData['action'] .='            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>';
                $nestedData['action'] .='            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(159px, 32px, 0px);">';
                $nestedData['action'] .='                <a class="dropdown-item" id="edit" href="#" data-toggle="modal" data-target="#edit_leave" data-id="'.$row['id'].'"><i class="fa fa-pencil m-r-5"></i> Edit</a>';
                $nestedData['action'] .='                <a class="dropdown-item" id="delete" href="#" data-toggle="modal" data-target="#delete_approve" data-id="'.$row['id'].'"><i class="fa fa-trash-o m-r-5"></i> Delete</a>';
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
}
