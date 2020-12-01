<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Models\Customer\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerLotProgressController extends Controller
{
    public function index()
    {
        return view('customer.index');
    }

    public function create()
    {

    }

    public function insertData(Request $request)
    {
        $params = $request->all();
        return MOP::createOrUpdate($params, $request->method(), $request);
    }

    public function edit($id)
    {

    }

    public function datatables(Request $request)
    {
        $_user_id = session()->get('_id');
        $_role_id = session()->get('_role_id');

        $columns = [
            0 => 'mop.id',
            1 => 'mop.update_date',
            2 => 'mop.project_name',
            4 => 'mop.status'
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

        $purchases = MOP::datatables($start, $limit, $order, $dir, $search, $filter);

        $data = [];

        if (!empty($purchases['data'])) {
            foreach ($purchases['data'] as $row) {
                $target_version = Version::where('id', $row['target_version'])->first();

                $nestedData['id'] = $row['id'];
                $nestedData['update_date'] = $row['update_date'];
                $nestedData['project_name'] = $row['project_name'];
                $nestedData['target_version'] = $target_version['name'];
                $nestedData['status'] = $row['status'];
                $nestedData['action'] = '';

                $data[] = $nestedData;
            }
        }

        $json_data = [
            'draw'  => intval($request->draw),
            'recordsTotal'  => intval($purchases['totalData']),
            'recordsFiltered' => intval($purchases['totalFiltered']),
            'data'  => $data,
            'order' => $order
        ];

        return json_encode($json_data);
    }

    public function detail($id)
    {

    }
}
