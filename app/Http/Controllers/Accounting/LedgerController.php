<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Accounting\AccountingLedger;
use App\Http\Models\GeneralSetting\GeneralSetting;

class LedgerController extends Controller
{
    public function index()
    {
        return view('accounting.ledger.'.__FUNCTION__, [
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

        $filter = $request->only(['sDate', 'eDate', 'coa', 'daterange']);

        $res = AccountingLedger::datatables($start, $limit, $order, $dir, $search, $filter, 'ledger');

        $data = [];

        $balance= 0;
        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $checkBalance = $row->debit - $row->credit;
                $balance += $checkBalance;

                $nestedData['id'] = $row->id;
                $nestedData['created_at'] = $row['created_at']->toDateString();
                $nestedData['description'] = $row->description;
                $nestedData['ref'] = $row->ref;
                $nestedData['debit'] = $row->debit;
                $nestedData['credit'] = $row->credit;
                $nestedData['saldo'] = abs($balance)." (".($balance < 0 ? 'K' : 'D').")" ;

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
}