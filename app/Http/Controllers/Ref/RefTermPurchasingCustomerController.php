<?php

namespace App\Http\Controllers\Ref;

use Illuminate\Http\Request;
use App\Http\Models\Ref\Province;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Models\Customer\Customer;
use App\Http\Models\Accounting\AccountingMaster;
use App\Http\Models\GeneralSetting\GeneralSetting;
use App\Http\Models\Ref\RefTermPurchasingCustomer;

class RefTermPurchasingCustomerController extends Controller
{
    public function index_customer_cost()
    {
        $coa = AccountingMaster::getChildrenCOA();

        return view('ref.customer_cost.index', [
            'coa' => $coa,
            'company_logo' => GeneralSetting::getCompanyLogo(),
            'company_name' => GeneralSetting::getCompanyName()
        ]);
    }

    public function index_customer_term()
    {
        $coa = AccountingMaster::getChildrenCOA();
        return view('ref.customer_term.index', [
            'coa' => $coa,
            'company_logo' => GeneralSetting::getCompanyLogo(),
            'company_name' => GeneralSetting::getCompanyName()
        ]);
    }

    public function create()
    {

    }

    public function insertData(Request $request)
    {
        $params = $request->all();
        return RefTermPurchasingCustomer::createOrUpdate($params, $request->method(), $request);
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
            0 => 'ref_term_purchasing_customers.id'
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

        $filter = $request->only(['sDate', 'eDate', 'terms_type']);

        $res = RefTermPurchasingCustomer::datatables($start, $limit, $order, $dir, $search, $filter, $session);

        $data = [];

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData['id'] = $row['id'];
                $nestedData['name'] = $row['name'];
                $nestedData['payment_type'] = $row['payment_type'];
                $nestedData['terms_type'] = $row['terms_type'];
                $nestedData['type'] = $row['type'];
                $nestedData['income'] = $row['income_account_code'].' | '.$row['income_account_name'];
                $nestedData['receivable'] = $row['receivable_account_code'].' | '.$row['receivable_account_name'];
                $nestedData['account_type'] = $row['account_type'];
                $nestedData['is_active'] = $row['is_active'];
                $nestedData['is_deleted'] = $row['is_deleted'];
                $nestedData['action'] = '';
                $nestedData['action'] .='        <div class="dropdown dropdown-action">';
                $nestedData['action'] .='            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>';
                $nestedData['action'] .='            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(159px, 32px, 0px);">';
                $nestedData['action'] .='                <a class="dropdown-item" href="#" id="edit" data-id="'.$row['id'].'"><i class="fa fa-pencil m-r-5"></i> Edit</a>';
                $nestedData['action'] .='                <a class="dropdown-item" href="#" id="delete" data-id="'.$row['id'].'"><i class="fa fa-trash-o m-r-5"></i> Delete</a>';
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
            $res = RefTermPurchasingCustomer::getById($id, $request);
        } else if (isset($request['all']) && $request['all']) {
            $res = RefTermPurchasingCustomer::getAllResult($request);
        } else {
            $res = RefTermPurchasingCustomer::getPaginatedResult($request);
        }

        return $res;
    }

    public function delete($id, Request $request)
    {
        RefTermPurchasingCustomer::where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data Sukses Dihapus'
        ]);
    }
}