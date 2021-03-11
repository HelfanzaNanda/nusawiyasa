<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Cluster\Cluster;
use App\Http\Models\Accounting\AccountingLedger;
use App\Http\Models\Accounting\AccountingJournal;
use App\Http\Models\GeneralSetting\GeneralSetting;

class GeneralLedgerController extends Controller
{
    public function index()
    {
        return view('accounting.general_ledger.'.__FUNCTION__, [
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
            0 => 'accounting_journals.id'
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

        $res = AccountingJournal::datatables($start, $limit, $order, $dir, $search, $filter, $session);

        $data = [];

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData['id'] = $row['id'];
				$nestedData['ref'] = $row['ref'];
				$nestedData['description'] = $row['description'];
				$nestedData['type'] = $row['type'];
				$nestedData['date'] = date('d M Y', strtotime($row['date']));
				$nestedData['total'] = number_format(floatval($row['total']));
                $nestedData['cluster_name'] = $row['cluster_name'];
                $nestedData['action'] = '';
                $nestedData['action'] .='<div class="dropdown dropdown-action">';
                $nestedData['action'] .='<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>';
                $nestedData['action'] .='<div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(159px, 32px, 0px);">';
                $nestedData['action'] .='<a href="#" data-journal="'.$row.'" class="dropdown-item" id="edit" data-id="'.$row['id'].'"><i class="fa fa-pencil m-r-5"></i> Edit</a>';
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

    public function store(Request $request)
    {
    	$total = 0;

        $saveJournal = new AccountingJournal;
        $saveJournal->ref = $request->ref;
        $saveJournal->description = $request->description;
        $saveJournal->type = 5;
        $saveJournal->date = $request->date;
        $saveJournal->cluster_id = $request->cluster_id;
        if ($saveJournal->save()) {
            for ($i = 0; $i < count($request->coa); $i++){
            	$total += $request->debit[$i];
                $saveGenLedger = new AccountingLedger;
                $saveGenLedger->accounting_journal_id = $saveJournal->id;
                $saveGenLedger->coa = $request->coa[$i];
                $saveGenLedger->debit = $request->debit[$i];
                $saveGenLedger->credit = $request->credit[$i];
                $saveGenLedger->cluster_id = $request->cluster_id;
                $saveGenLedger->save();
            }
        }

        AccountingJournal::where('id', $saveJournal->id)->update([
        	'total' => $total
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data Jurnal Umum Telah Berhasil Disimpan',
            'data' => $saveJournal
        ]);
    }

    public function edit($id)
    {
        return AccountingJournal::where('id', $id)->with(['accountingLedgers' => function($query){
            $query->with('accountingMaster');
        }])->first();
    }

    public function update(Request $request, $id)
    {
        $total = 0;
        $accountingJournal = AccountingJournal::where('id', $id)->first();
        $accountingJournal->update([
            'date' => $request->date,
            'description' => $request->description,
            'ref' => $request->ref,
            'cluster_id' => $request->cluster_id
        ]);
        $accountingJournal->accountingLedgers()->delete();
        for ($i=0; $i < count($request->coa); $i++) { 
            $total += $request->debit[$i];
            $accountingJournal->accountingLedgers()->create([
                'coa' => $request->coa[$i],
                'debit' => $request->debit[$i],
                'credit' => $request->credit[$i],
                'cluster_id' => $request->cluster_id
            ]);
        }
        $accountingJournal->update([
            'total' => $total
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Data Jurnal Umum Telah Berhasil DIUpdate',
        ]);
    }

    public function delete($id)
    {
        $accountingJournal = AccountingJournal::where('id', $id)->first();
        $accountingJournal->accountingLedgers()->delete();
        $accountingJournal->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data Jurnal Umum Telah Berhasil Di Hapus',
        ]);
    }
}