<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Models\Inventory\DeliveryOrderItems;
use App\Http\Models\Inventory\DeliveryOrders;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;

class DeliveryOrderController extends Controller
{
    public function index()
    {
        return view('inventory.delivery_order');
    }

    public function create()
    {
        return view('inventory.delivery_order_create');
    }

    public function insertData(Request $request)
    {
        $params = $request->all();

        return DeliveryOrders::createOrUpdate($params, $request->method(), $request);
    }

    public function edit($id)
    {
        $order = DeliveryOrders::whereId($id)->first();

        return view('inventory.delivery_order_update', compact('order'));

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
            0 => 'delivery_orders.id'
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

        $res = DeliveryOrders::datatables($start, $limit, $order, $dir, $search, $filter);

        $data = [];

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData['id'] = $row['id'];
                $nestedData['number'] = $row['number'];
                $nestedData['date'] = $row['date'];
                $nestedData['dest_name'] = $row['dest_name'];
                $nestedData['dest_address'] = $row['dest_address'];
                $nestedData['action'] = '';
                $nestedData['action'] .='        <div class="dropdown dropdown-action">';
                $nestedData['action'] .='            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>';
                $nestedData['action'] .='            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(159px, 32px, 0px);">';
                $nestedData['action'] .='                <a class="dropdown-item" target="_blank" href="'.url('/delivery-order-pdf/'.$row['id']).'"><i class="fa fa-print m-r-5"></i> Cetak</a>';
                $nestedData['action'] .='                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_leave"><i class="fa fa-info m-r-5"></i> Detail</a>';
                $nestedData['action'] .='                <a class="dropdown-item" href="'.route('delivery.edit', $row['id']).'"><i class="fa fa-pencil m-r-5"></i> Edit</a>';
                $nestedData['action'] .='                <a class="dropdown-item" id="delete" data-id="'.$row['id'].'"href="#" data-toggle="modal" data-target="#delete_approve"><i class="fa fa-trash-o m-r-5"></i> Delete</a>';
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

    public function delete($id){
        DeliveryOrders::destroy($id);
        return response()->json([
            'message' => 'data berhasil dihapus',
            'status' => 'success'
        ]);
    }

    public function generatePdf($id)
    {
        // return view('inventory.delivery_order_pdf', [
        //     'data' => DeliveryOrderItems::generatePdf($id),
        // ]);
        $pdf = PDF::setOptions(['isRemoteEnabled' => true])->loadview('inventory.delivery_order_pdf', [
            'data' => DeliveryOrderItems::generatePdf($id),
        ]);
        return $pdf->download('surat jalan.pdf');
    }
}
