<?php

namespace App\Http\Controllers\GeneralAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Cluster\Cluster;
use App\Http\Models\GeneralAdmin\WageSubmission;
use App\Http\Models\GeneralSetting\GeneralSetting;
use App\Http\Models\GeneralAdmin\WageSubmissionDetail;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;

class WageSubmissionController extends Controller
{
    public function index()
    {
        return view('general_admin.wage_submission.'.__FUNCTION__, [
            'clusters' => Cluster::selectClusterBySession(),
            'company_logo' => GeneralSetting::getCompanyLogo(),
            'company_name' => GeneralSetting::getCompanyName()
        ]);
    }

    public function store(Request $request)
    {
        $params = $request->all();
        //return $params;
        return WageSubmission::createOrUpdate($params, $request->method(), $request);
    }

    public function delete($id)
    {
        WageSubmission::destroy($id);
        WageSubmissionDetail::where('wage_submission_id', $id)->delete();
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
            0 => 'wage_submissions.id'
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

        $filter = $request->only(['sDate', 'eDate', 'isPk']);

        $res = WageSubmission::datatables($start, $limit, $order, $dir, $search, $filter, $session);

        $data = [];

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData['id'] = $row['id'];
				$nestedData['number'] = $row['number'];
				$nestedData['date'] = date('d M Y', strtotime($row['date']));
				$nestedData['total'] = number_format(floatval($row['total']));
                $nestedData['cluster_name'] = $row['cluster_name'];
                $nestedData['action'] = '';
                $nestedData['action'] .='<div class="dropdown dropdown-action">';
                $nestedData['action'] .='<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>';
                $nestedData['action'] .='<div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(159px, 32px, 0px);">';
                $nestedData['action'] .='<a href="#" data-wage="'.$row.'" class="dropdown-item" id="edit" data-id="'.$row['id'].'"><i class="fa fa-pencil m-r-5"></i> Edit</a>';
                $nestedData['action'] .='<a href="'.url('/salary-submission/cetak/'.$row['id']).'" target="_blank" class="dropdown-item" id="print" ><i class="fa fa-print m-r-5"></i> Cetak</a>';
                $nestedData['action'] .='<a href="#" class="dropdown-item" id="delete" data-id="'.$row['id'].'"><i class="fa fa-trash-o m-r-5"></i> Delete</a>';
                $nestedData['action'] .='</div>';
                $nestedData['action'] .='</div>';
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

    public function edit($id)
    {
        return WageSubmission::getData($id);
    }

    public function generatePdf($id)
    {
        
        // return view('general_admin.wage_submission.pdf', [
        //     'data' => WageSubmission::getData($id),
        //     'header' => GeneralSetting::getPdfHeaderImage(),
        //     'footer' => GeneralSetting::getPdfFooterImage(),
        //     'company_name' => GeneralSetting::getCompanyName(),
        //     'company_logo' => GeneralSetting::getCompanyLogo(),
        // ]);

        $pdf = PDF::setOptions(['isRemoteEnabled' => true])
        ->loadview('general_admin.wage_submission.pdf', [
            'data' => WageSubmission::getData($id),
            'header' => GeneralSetting::getPdfHeaderImage(),
            'footer' => GeneralSetting::getPdfFooterImage(),
            'company_name' => GeneralSetting::getCompanyName(),
            'company_logo' => GeneralSetting::getCompanyLogo(),
        ]);
        return $pdf->download('DIPOSISI PENGAJUAN UPAH BORONGAN.pdf');
    }
}