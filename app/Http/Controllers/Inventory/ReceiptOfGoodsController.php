<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Models\Inventory\Suppliers;
use App\Http\Models\Purchase\PurchaseOrderDeliveries;
use App\Http\Models\Purchase\PurchaseOrderDeliveryItems;
use App\Http\Models\Purchase\PurchaseOrders;
use App\Http\Models\Ref\Province;
use Illuminate\Http\Request;
use App\Http\Models\Inventory\Inventories;
use Illuminate\Support\Facades\DB;

class ReceiptOfGoodsController extends Controller
{
    public function index()
    {
        return view('inventory.receipt_of_goods');
    }

    public function create()
    {
        $purchase_orders = PurchaseOrders::whereIn('status', [4, 5])->get();

        return view('inventory.receipt_of_goods_create', compact('purchase_orders'));
    }

    public function insertData(Request $request)
    {
        $params = $request->all();

        return PurchaseOrderDeliveries::createOrUpdate($params, $request->method(), $request);
    }

    public function edit($id)
    {
        $delivery = PurchaseOrderDeliveries::whereId($id)->first();       
         
        $purchase_orders = PurchaseOrders::whereIn('status', [4, 5])->get();
        $no = 1;
        return view('inventory.receipt_of_goods_update', compact('purchase_orders', 'delivery', 'no'));
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
            0 => 'purchase_order_deliveries.id'
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

        $res = PurchaseOrderDeliveries::datatables($start, $limit, $order, $dir, $search, $filter);

        $data = [];

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData['id'] = $row['id'];
                $nestedData['date'] = $row['date'];
                $nestedData['bpb_number'] = $row['bpb_number'];
                $nestedData['invoice_number'] = $row['invoice_number'];
                $nestedData['supplier_name'] = $row['supplier_name'];
                $nestedData['po_number'] = $row['po_number'];
                $nestedData['action'] = '';
                $nestedData['action'] .='        <div class="dropdown dropdown-action">';
                $nestedData['action'] .='            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>';
                $nestedData['action'] .='            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(159px, 32px, 0px);">';
                $nestedData['action'] .='                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_leave"><i class="fa fa-info m-r-5"></i> Detail</a>';
                $nestedData['action'] .='                <a class="dropdown-item" href="'.route('receipt_a.edit', $row['id']).'"><i class="fa fa-pencil m-r-5"></i> Edit</a>';
                $nestedData['action'] .='                <a class="dropdown-item" id="delete" data-id="'.$row['id'].'" href="#" data-toggle="modal" data-target="#delete_approve"><i class="fa fa-trash-o m-r-5"></i> Delete</a>';
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
        $items = PurchaseOrderDeliveryItems::where('purchase_order_delivery_id', $id)->get();
        
        foreach ($items as $item) {
            $latest_qty = $item->inventory->stock;
            //dd($latest_qty);
            Inventories::whereId($item->inventory_id)
                ->update(
                    [
                        'stock' => ($latest_qty - $item->delivered_qty)
                    ]
                );
                PurchaseOrderDeliveryItems::where('id', $item->id)->delete();
        }
        PurchaseOrderDeliveries::whereId($id)->delete();
        return response()->json([
            'message' => 'data berhasil dihapus',
            'status' => 'success'
        ]);
    }
}
