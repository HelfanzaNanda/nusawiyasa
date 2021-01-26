<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Models\Ref\Province;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Models\Cluster\Cluster;
use App\Http\Models\Customer\Customer;
use App\Http\Models\Ref\RefGeneralStatuses;
use App\Http\Models\Purchase\PurchaseOrders;
use Barryvdh\DomPDF\Facade as PDF;

class OutstandingPOController extends Controller
{
    public function index()
    {
        return view('report.outstanding_po',[
            'clusters' => Cluster::selectClusterBySession(),
        ]);
    }

    public function create()
    {

    }

    public function insertData(Request $request)
    {
        $params = $request->all();

        return Customer::createOrUpdate($params, $request->method(), $request);
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

        $filter = $request->only(['sDate', 'eDate', 'outstanding_po', 'daterange', 'cluster']);

        $res = PurchaseOrders::datatables($start, $limit, $order, $dir, $search, $filter, $session);
        //return json_encode($res);
        //$res = PurchaseOrders::datatables($start, $limit, $order, $dir, $search, $filter, $outstanding_po, $filters, $session);

        $data = [];

        $status_collection = RefGeneralStatuses::get();

        $type = '';

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData['id'] = $row['id'];
                $nestedData['number'] = $row['number'];
                $nestedData['fpp_number'] = $row['request_number']  ?? '-';
                if ($row['type'] == 'non_rap') {
                    $type = 'NON RAP';
                } else if ($row['type'] == 'rap') {
                    $type = 'RAP';
                } else if ($row['type'] == 'disposition') {
                    $type = 'DISPOSISI';
                }

                $nestedData['type'] = $type;
                $nestedData['date'] = $row['date'];
                $nestedData['status'] = $status_collection->where('id', $row['status'])->values()[0]['name'];
                $nestedData['total'] = number_format(floatval($row['total']));
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

    public function generatePdf(Request $request)
    {
        // return view('report.outstanding_po_pdf', [
        //     'datas' => PurchaseOrders::generatePdf($request)
        // ]);

        $filename = 'Outstanding PO per '.$request->daterange_pdf;
        $pdf = PDF::setOptions(['isRemoteEnabled' => true])
        ->loadview('report.outstanding_po_pdf', [
            'datas' => PurchaseOrders::generatePdf($request),
            'title' => $filename
        ]);
        return $pdf->download($filename.'.pdf');
    }
}
