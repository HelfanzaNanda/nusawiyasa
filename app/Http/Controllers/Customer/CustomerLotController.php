<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Models\Cluster\Lot;
use App\Http\Models\Customer\Customer;
use App\Http\Models\Customer\CustomerCost;
use App\Http\Models\Customer\CustomerLot;
use App\Http\Models\Customer\CustomerPayment;
use App\Http\Models\Customer\CustomerTerm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerLotController extends Controller
{
    public function index()
    {
        return view('customer.customer_lot');
    }

    public function create($lot_id = 0)
    {
        $customers = Customer::select(['customers.id as id', 'users.name as name'])->join('users', 'users.id', '=', 'customers.user_id')->get();
        $lots = Lot::select(['lots.id', 'clusters.name', 'lots.block', 'lots.unit_number', 'customer_lots.id as booking_id'])->join('clusters', 'clusters.id', '=', 'lots.cluster_id')->leftJoin('customer_lots', 'customer_lots.lot_id', '=', 'lots.id')->get();
        return view('customer.customer_lot_create', compact('customers', 'lots', 'lot_id'));
    }

    public function insertData(Request $request)
    {
        $params = $request->all();
        //return $params; 
        return CustomerLot::createOrUpdate($params, $request->method(), $request);
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
                $nestedData['action'] .='                <a class="dropdown-item" href="'.url('/bookings/edit/'.$row['id']).'"><i class="fa fa-info m-r-5"></i> Edit</a>';
                $nestedData['action'] .='                <a class="dropdown-item" href="'.url('/bookings/detail/'.$row['id']).'"><i class="fa fa-info m-r-5"></i> Detail</a>';
                $nestedData['action'] .='                <button class="dropdown-item" id="delete-data" data-id="'.$row['id'].'"><i class="fa fa-info m-r-5"></i> Hapus</button>';
                if ($row['payment_type'] == 'cash_in_stages') {
                    $nestedData['action'] .='                <button class="dropdown-item" id="booking-installment" data-id="'.$row['id'].'"><i class="fa fa-info m-r-5"></i> Cicilan Cash</button>';
                }
                if ($row['payment_type'] == 'credit') {
                    $nestedData['action'] .='                <button class="dropdown-item" id="update-bank-status" data-id="'.$row['id'].'"><i class="fa fa-info m-r-5"></i> Update Status Bank</button>';
                }
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

        return view('customer.customer_lot_detail', compact('data', 'customer_terms', 'customer_costs'));
    }

    public function get($id=null, Request $request)
    {
        $request = $request->all();

        if ($id != null) {
            $res = CustomerLot::getById($id, $request);
        } else if (isset($request['all']) && $request['all']) {
            $res = CustomerLot::getAllResult($request);
        } else {
            $res = CustomerLot::getPaginatedResult($request);
        }

        return $res;
    }

    public function delete($id){
        $customer_lot = CustomerLot::where('id', $id)->first();

        CustomerCost::where('customer_id', $customer_lot['customer_id'])->where('lot_id', $customer_lot['lot_id'])->delete();

        CustomerPayment::where('customer_lot_id', $customer_lot['id'])->delete();

        CustomerTerm::where('customer_id', $customer_lot['customer_id'])->where('lot_id', $customer_lot['lot_id'])->delete();
        
        $cluster = CustomerLot::destroy($id);
        
        return response()->json([
            'message' => 'data berhasil dihapus',
            'status' => 'success'
        ]);
    }

    public function edit($id)
    {
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
        $customers = Customer::select(['customers.id as id', 'users.name as name'])->join('users', 'users.id', '=', 'customers.user_id')->get();
        $lots = Lot::select(['lots.id', 'clusters.name', 'lots.block', 'lots.unit_number', 'customer_lots.id as booking_id'])->join('clusters', 'clusters.id', '=', 'lots.cluster_id')->leftJoin('customer_lots', 'customer_lots.lot_id', '=', 'lots.id')->get();
        return view('customer.customer_lot_edit', [
            'customers' => $customers,
            'lots' => $lots,
            'data' => $data,
            'customer_terms' => $customer_terms,
            'customer_costs' => $customer_costs
        ]);
    }
}
