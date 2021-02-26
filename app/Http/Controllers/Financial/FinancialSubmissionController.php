<?php

namespace App\Http\Controllers\Financial;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Models\Inventory\InventoryUnits;
use Barryvdh\DomPDF\Facade as PDF;
use App\Http\Controllers\Controller;
use App\Http\Models\Cluster\Cluster;
use App\Http\Models\Financial\FinancialSubmission;
use App\Http\Models\GeneralSetting\GeneralSetting;

class FinancialSubmissionController extends Controller
{
    public function index(){
        return view('financial.index', [
            'company_logo' => GeneralSetting::getCompanyLogo(),
            'company_name' => GeneralSetting::getCompanyName()
        ]);
    }

    public function datatable(Request $request){
        $_login = session()->get('_login');
        $_id = session()->get('_id');
        $_name = session()->get('_name');
        $_email = session()->get('_email');
        $_username = session()->get('_username');
        $_phone = session()->get('_phone');
        $_role_id = session()->get('_role_id');
        $_role_name = session()->get('_role_name');

        $columns = [
            0 => 'financial_submissions.id'
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
        $res = FinancialSubmission::datatables($start, $limit, $order, $dir, $search, $filter);
        $data = [];

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData['id'] = $row['id'];
                $nestedData['date'] = $row['date'];
                $nestedData['number'] = $row['number'];
                $nestedData['total'] = 'Rp.'.$row['total'];
                $nestedData['cluster'] = $row['cluster']['name'];
                $nestedData['created_by_user_id'] = ($row['createdByUser']!= null) ? $row['createdByUser']['name'] : '-';
                $nestedData['approved_by_user_id'] = ($row['approvedByUser']!= null) ? $row['approvedByUser']['name'] : '-';
                $nestedData['received_by_user_id'] = ($row['receivedByUser']!= null) ? $row['receivedByUser']['name'] : '-';

                $nestedData['action'] = '';
                $nestedData['action'] .='        <div class="dropdown dropdown-action">';
                $nestedData['action'] .='            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>';
                $nestedData['action'] .='            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(159px, 32px, 0px);">';
                $nestedData['action'] .='                <a class="dropdown-item" href="'.route('financial.edit', $row['id']).'"><i class="fa fa-pencil m-r-5"></i> Edit</a>';
                $nestedData['action'] .='                <a class="dropdown-item" id="delete" href="#" data-toggle="modal" data-target="#delete_approve" data-id="'.$row['id'].'"><i class="fa fa-trash-o m-r-5"></i> Delete</a>';
                $nestedData['action'] .='                <a class="dropdown-item"href="'.route('financial.pdf', $row['id']).'"><i class="fa fa-info m-r-5"></i> Cetak</a>';
                $nestedData['action'] .='            </div>';
                $nestedData['action'] .='        </div>';
                $data[] = $nestedData;
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
    }

    public function create(Request $request){
        return view('financial.create',  [
            'id' => session()->get('_id'),
            'clusters' => Cluster::all(),
            'units' => InventoryUnits::where('is_active', true)->get(),
            'company_logo' => GeneralSetting::getCompanyLogo(),
            'company_name' => GeneralSetting::getCompanyName()
        ]);
    }

    public function store(Request $request){
        $params = $request->all();
        return FinancialSubmission::createOrUpdate($params, $request->method(), $request);
    }

    public function edit(Request $request, $id){
        //$id = session()->get('_id');
        $financial = FinancialSubmission::where('id', $id)->first();
        $clusters = Cluster::all();
        $units = InventoryUnits::where('is_active', true)->get();
        $no = 1;
        
        $company_logo =  GeneralSetting::getCompanyLogo();
        $company_name =  GeneralSetting::getCompanyName();
        return view('financial.edit', compact(['financial', 'id', 'clusters', 'no', 'company_name', 'company_logo', 'units']));
    }

    public function delete(Request $request, $id){
        FinancialSubmission::destroy($id);
        return response()->json([
            'message' => 'data berhasil dihapus',
            'status' => 'success'
        ]);
    }

    public function pdf($id){
        $data = FinancialSubmission::where('id', $id)->first();
        //return view('financial.pdf', compact('data'));
        $customPaper = array(0,0,360,360);
        $pdf = PDF::setOptions([
            'isRemoteEnabled' => true, 
            'isHtml5ParserEnabled' => true, 
            'setPaper' => $customPaper
        ])
        ->loadview('financial.pdf', [
            'data' => $data,
            'header' => GeneralSetting::getPdfHeaderImage(),
            'footer' => GeneralSetting::getPdfFooterImage()
        ]);
        return $pdf->stream($data['number'].'-'.Carbon::now().'.pdf');
    }
}
