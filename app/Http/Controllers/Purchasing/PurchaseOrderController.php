<?php

namespace App\Http\Controllers\Purchasing;

use Illuminate\Http\Request;
use App\Http\Models\Cluster\Lot;
use App\Http\Models\Ref\Province;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Models\Accounting\Debt;
use App\Http\Models\Cluster\Cluster;
use App\Http\Models\Inventory\Suppliers;
use App\Http\Models\Inventory\Inventories;
use App\Http\Models\Ref\RefGeneralStatuses;
use App\Http\Models\Purchase\PurchaseOrders;
use App\Http\Models\Project\RequestMaterials;
use App\Http\Models\Purchase\PurchaseOrderItems;
use App\Http\Models\GeneralSetting\GeneralSetting;
use App\Http\Models\Project\RequestMaterialItems;
use Carbon\Carbon;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        return view('purchasing.purchase_order', [
            'company_logo' => GeneralSetting::getCompanyLogo(),
            'company_name' => GeneralSetting::getCompanyName()
        ]);
    }

    public function create()
    {
        $suppliers = Suppliers::get();
        $lots = Lot::selectClusterBySession();
        $clusters = Cluster::selectClusterBySession();
        $request_materials = RequestMaterials::selectClusterAndMaterialItemBySession();
        $company_logo = GeneralSetting::getCompanyLogo();
        $company_name = GeneralSetting::getCompanyName();

        return view('purchasing.purchase_order_create', compact('suppliers', 'lots', 'clusters', 'request_materials', 'company_logo', 'company_name'));
    }

    public function insertData(Request $request)
    {
        $params = $request->all();
        //return json_encode($params);
        return PurchaseOrders::createOrUpdate($params, $request->method(), $request);
    }

    public function edit($id)
    {
        $suppliers = Suppliers::get();
        $clusters = Cluster::selectClusterBySession();
        $request_materials = RequestMaterials::selectClusterAndMaterialItemBySession();
        $purchase = PurchaseOrders::whereId($id)->first();
        $lots = Lot::whereClusterId($purchase->cluster_id)->get();
        $debt = Debt::where('purchase_order_id' , $id)->first();

        if($purchase){
            $purchase->subtotal = self::withoutCurency($purchase->subtotal);
            $purchase->tax = self::withoutCurency($purchase->tax);
            $purchase->delivery = self::withoutCurency($purchase->delivery);
            $purchase->other = self::withoutCurency($purchase->other);
            $purchase->total = self::withoutCurency($purchase->total);
        }
        $no = 1;

        $company_logo = GeneralSetting::getCompanyLogo();
        $company_name = GeneralSetting::getCompanyName();

        return view('purchasing.purchase_order_update', compact('suppliers', 'lots', 'clusters', 'request_materials', 'purchase', 'no', 'debt', 'company_logo', 'company_name'));
    }

    private static function withoutCurency($data){
        $tmp = explode('.', $data);
        return $tmp[0];
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

        $res = PurchaseOrders::datatables($start, $limit, $order, $dir, $search, $filter, $session);

        $data = [];

        $status_collection = RefGeneralStatuses::get();

        $type = '';

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData['id'] = $row['id'];
                $nestedData['number'] = $row['number'];
                $nestedData['fpp_number'] = $row['request_number'];
                if ($row['type'] == 'non_rap') {
                    $type = 'NON RAP';
                } else if ($row['type'] == 'rap') {
                    $type = 'RAP';
                } else if ($row['type'] == 'disposition') {
                    $type = 'DISPOSISI';
                }

                $nestedData['type'] = $type;
                $nestedData['date'] = date('d M Y', strtotime($row['date']));
                $nestedData['status'] = $status_collection->where('id', $row['status'])->values()[0]['name'];
                $nestedData['total'] = number_format(floatval($row['total']));
                $nestedData['action'] = '';
                $nestedData['action'] .='        <div class="dropdown dropdown-action">';
                $nestedData['action'] .='            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>';
                $nestedData['action'] .='            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(159px, 32px, 0px);">';
                $nestedData['action'] .='                <a class="dropdown-item" href="'.route('po.edit', $row['id']).'"><i class="fa fa-pencil m-r-5"></i> Edit</a>';
                $nestedData['action'] .='                <a class="dropdown-item" target="_blank" href="'.url('/purchase-order-pdf/'.$row['id']).'"><i class="fa fa-print m-r-5"></i> Cetak</a>';
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

    public function delete($id)
    {
        PurchaseOrders::destroy($id);
        PurchaseOrderItems::where('purchase_order_id', $id)->delete();
        RequestMaterialItems::where('purchase_order_id', $id)->update([
            'is_used_in_po' => false,
            'purchase_order_id' => null
        ]);
        return response()->json([
            'message' => 'data berhasil dihapus',
            'status' => 'success'
        ]);
    }

    public function generatePdf($id)
    {
        $pdf = PDF::setOptions(['isRemoteEnabled' => true])
        ->loadview('purchasing.purchase_order_pdf', [
            'data' => PurchaseOrderItems::generatePdf($id),
            'header' => GeneralSetting::getPdfHeaderImage(),
            'footer' => GeneralSetting::getPdfFooterImage(),
            'company_name' => GeneralSetting::getCompanyName(),
            'company_logo' => GeneralSetting::getCompanyLogo(),
        ]);
        return $pdf->download('Purchase Order.pdf');
    }
}
