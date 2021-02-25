<?php

namespace App\Http\Controllers\Report;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use App\Http\Controllers\Controller;
use App\Http\Models\Cluster\Cluster;
use App\Http\Models\GeneralSetting\GeneralSetting;
use App\Http\Models\Inventory\ReceiptOfGoodsRequest;

class UsedInventoryController extends Controller
{
    public function index()
    {
        return view('report.used_inventory', [
            'clusters' => Cluster::selectClusterBySession(),
            'company_logo' => GeneralSetting::getCompanyLogo(),
            'company_name' => GeneralSetting::getCompanyName()
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
            0 => 'receipt_of_goods_request.id'
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

        $filter = $request->only(['sDate', 'eDate', 'used_inventory', 'daterange', 'cluster']);

        $res = ReceiptOfGoodsRequest::datatables($start, $limit, $order, $dir, $search, $filter, $session);
        //return $res;

        $data = [];

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData['id'] = $row['id'];
                $nestedData['date'] = $row['date'];
                $nestedData['number'] = $row['number'];
                $nestedData['cluster_name'] = $row['cluster_name'];
                $nestedData['block'] = $row['block'];
                $nestedData['unit_number'] = $row['unit_number'];
                $nestedData['surface_area'] = $row['surface_area'];
                $nestedData['building_area'] = $row['building_area'];
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

    public function generatePdf(Request $request)
    {

        $data = ReceiptOfGoodsRequest::generatePdf($request);
        $startDate = Carbon::parse($data['startDate'])->format('d-m-Y');
        $endDate = Carbon::parse($data['endDate'])->format('d-m-Y');
        $filename = 'Used Inventory per '.$startDate. ' - '. $endDate;
 
        $pdf = PDF::setOptions(['isRemoteEnabled' => true])
        ->loadview('report.used_inventory_pdf', [
            'datas' => $data['receipts'],
            'title' => $filename,
            'header' => GeneralSetting::getPdfHeaderImage(),
            'footer' => GeneralSetting::getPdfFooterImage(),
            'company_name' => GeneralSetting::getCompanyName(),
            'company_logo' => GeneralSetting::getCompanyLogo(),
        ]);
        return $pdf->download($filename.'.pdf');
    }
}
