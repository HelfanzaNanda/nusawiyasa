<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Models\Cluster\Lot;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Models\Customer\Customer;
use App\Http\Models\Customer\CustomerLot;
use App\Http\Models\Customer\CustomerCost;
use App\Http\Models\Customer\CustomerTerm;
use App\Http\Models\Customer\CustomerPayment;
use App\Http\Models\Accounting\AccountingMaster;
use App\Http\Models\Project\DevelopmentProgress;
use App\Http\Models\GeneralSetting\GeneralSetting;

class CustomerPaymentController extends Controller
{
    public function index()
    {
        return view('customer.customer_payment', [
            'company_logo' => GeneralSetting::getCompanyLogo(),
            'company_name' => GeneralSetting::getCompanyName()
        ]);
    }

    public function insertData(Request $request)
    {
        $params = $request->all();
        return CustomerPayment::createOrUpdate($params, $request->method(), $request);
    }

    public function edit($id)
    {

    }

    public function update(Request $request)
    {
        $params = $request->all();
        //return $params;
        return CustomerPayment::createOrUpdate($params, $request->method(), $request);
    }

    public function delete($id)
    {
        CustomerPayment::destroy($id);
        return response()->json([
            'message' => 'data berhasil dihapus',
            'status' => 'success'
        ]);
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
            0 => 'customer_lots.id'
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

        $res = CustomerLot::datatables($start, $limit, $order, $dir, $search, $filter, $session);

        $data = [];

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData['id'] = $row['id'];
                $nestedData['customer_name'] = $row['customer_name'];
                $nestedData['cluster_name'] = $row['cluster_name'];
                $nestedData['block'] = $row['block'];
                $nestedData['unit_number'] = $row['unit_number'];
                $nestedData['surface_area'] = $row['surface_area'];
                $nestedData['building_area'] = $row['building_area'];
                $nestedData['status'] = $row['status'];
                $nestedData['action'] = '';
                $nestedData['action'] .='        <div class="dropdown dropdown-action">';
                $nestedData['action'] .='            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>';
                $nestedData['action'] .='            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(159px, 32px, 0px);">';
                $nestedData['action'] .='                <a class="dropdown-item" href="'.url('/bookings/'.$row['id'].'/payments').'"><i class="fa fa-info m-r-5"></i> Pembayaran</a>';
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
        $coa = AccountingMaster::getChildrenCOA();

        $data = CustomerLot::select('customer_lots.*')->where('customer_lots.id', $id)
                    ->addSelect('customers.user_id as user_id')
                    ->addSelect('customers.id as customer_id')
                    ->addSelect('users.name as customer_name')
                    ->addSelect('clusters.name as cluster_name')
                    ->addSelect('lots.block')
                    ->addSelect('lots.unit_number')
                    ->addSelect('lots.surface_area')
                    ->addSelect('lots.building_area')
                    ->leftJoin('customers', 'customers.id', '=', 'customer_lots.customer_id')
                    ->leftJoin('users', 'users.id', '=', 'customers.user_id')
                    ->leftJoin('lots', 'lots.id', '=', 'customer_lots.lot_id')
                    ->leftJoin('clusters', 'clusters.id', '=', 'lots.cluster_id')
                    ->first();

        $customer_terms = CustomerTerm::select('customer_terms.*')->addSelect('ref_term_purchasing_customers.name as key_name')->where('customer_id', $data['customer_id'])->where('lot_id', $data['lot_id'])->join('ref_term_purchasing_customers', 'ref_term_purchasing_customers.id', '=', 'customer_terms.ref_term_purchasing_customer_id')->get();

        $customer_costs = CustomerCost::select('customer_costs.*')->addSelect('ref_term_purchasing_customers.name as key_name')->where('customer_id', $data['customer_id'])->where('lot_id', $data['lot_id'])->join('ref_term_purchasing_customers', 'ref_term_purchasing_customers.id', '=', 'customer_costs.ref_term_purchasing_customer_id')->get();

        $customer_payments = CustomerPayment::select('customer_payments.*')->addSelect('accounting_masters.name as account_name')->where('customer_lot_id', $id)->leftJoin('accounting_masters', 'accounting_masters.coa', '=', 'customer_payments.payment_type')->get();

        return view('customer.customer_payment_detail', compact('data', 'customer_terms', 'customer_costs', 'id', 'customer_payments', 'coa'));
    }

    public function get($id=null, Request $request)
    {
        $request = $request->all();

        if ($id != null) {
            $res = CustomerPayment::getById($id, $request);
        } else if (isset($request['all']) && $request['all']) {
            $res = CustomerPayment::getAllResult($request);
        } else {
            $res = CustomerPayment::getPaginatedResult($request);
        }

        return $res;
    }

    public function lotProgress(Request $request)
    {
        $params = $request->all();

        $data = DevelopmentProgress::select(DB::raw('MAX(development_progress.percentage) as percentage'), 'lots.block', 'lots.unit_number', 'spk_workers.wage', 'customer_lots.id')
                ->join('customer_lots', 'customer_lots.lot_id', '=', 'development_progress.lot_id')
                ->join('spk_workers', 'spk_workers.customer_lot_id', '=', 'customer_lots.id')
                ->join('lots', 'lots.id', '=', 'development_progress.lot_id')
                ->where(DB::raw('CONCAT(lots.block, "-", lots.unit_number)'), 'LIKE', '%'.$params['term'].'%')
                ->where('development_progress.cluster_id', $params['cluster_id'])
                ->groupBy('customer_lots.id')
                ->get();

        return json_decode($data);
    }
}
