<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Cluster\Cluster;
use App\Http\Models\Inventory\Inventories;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;

class StockOpnameController extends Controller
{
    public function index()
    {
        return view('report.stock_opname', [
            'clusters' => Cluster::selectClusterBySession(),
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
            0 => 'inventories.id'
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

        $filter_cluster = $request->filter_cluster;

        $res = Inventories::datatables($start, $limit, $order, $dir, $search, $filter, $filter_cluster, $session);

        $data = [];

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData['id'] = $row['id'];
                $nestedData['name'] = $row['name'];
                $nestedData['stock'] = floatval($row['stock']);
                $nestedData['unit_name'] = $row['unit_name'];
                $nestedData['brand'] = $row['brand'] ?? '-';
                $nestedData['type'] = $row['type'];
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
        $cluster = $request->cluster_pdf;

        $name_cluster = Cluster::where('id', $cluster)->pluck('name')->first();
        $date = Carbon::now()->format('d M Y');
        $filename = 'Stock Opname per '.$date.' '.$name_cluster;
        $pdf = PDF::setOptions(['isRemoteEnabled' => true])
        ->loadview('report.stock_opname_pdf', [
            'data' => Inventories::generatePdf($cluster),
            'title' => $filename
        ]);
        return $pdf->download($filename.'.pdf');
    }
}
