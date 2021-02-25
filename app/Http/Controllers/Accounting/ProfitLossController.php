<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Accounting\AccountingMaster;
use App\Http\Models\Accounting\AccountingJournal;
use App\Http\Models\GeneralSetting\GeneralSetting;

class ProfitLossController extends Controller
{
    public function index()
    {
        return view('accounting.profit_loss.'.__FUNCTION__, [
            'company_logo' => GeneralSetting::getCompanyLogo(),
            'company_name' => GeneralSetting::getCompanyName()
        ]);
    }

    public function get(Request $request)
    {
        $getAccount = AccountingMaster::select(
                        'accounting_masters.coa AS coa',
                        'accounting_masters.sub_coa AS sub_coa',
                        'accounting_masters.name AS name',
                        'accounting_masters.type AS type',
                        'accounting_masters.accounting_code AS accounting_code'
                    )
                    ->get();

        $journalData = [];

        foreach ($getAccount as $key => $val) {
            $splitCOA = explode(".", $val['accounting_code']);
            if ($splitCOA[0] == 4 || $splitCOA[0] == 5 || $splitCOA[0] == 6 || $splitCOA[0] == 7) {
                if (strlen($val['coa']) > 1) {
                    $balanceRev = 0;

                    $getJournal = AccountingJournal::select([
                                    'accounting_ledgers.debit',
                                    'accounting_ledgers.credit',
                                    'accounting_journals.date'
                                ])
                                ->join('accounting_ledgers', 'accounting_journals.id', '=', 'accounting_ledgers.accounting_journal_id')
                                ->join('accounting_masters', 'accounting_masters.coa', '=', 'accounting_ledgers.coa')
                                ->where('accounting_ledgers.coa', $val['coa']);

                    if (empty($request['sDate']) && empty($request['eDate'])) {
                        $getJournal->whereMonth('accounting_journals.date', '=', date('m'))
                                ->whereYear('accounting_journals.date', '=', date('Y'));
                    } else {
			            $startDate = Carbon::parse(substr($request['daterange'], 0, 10))->format('Y-m-d');
			            $endDate = Carbon::parse(substr($request['daterange'], 12))->format('Y-m-d');
                        $getJournal->whereBetween(DB::raw("DATE(accounting_journals.date)"), [$startDate, $endDate]);
                    }

                    foreach ($getJournal->get() as $journ) {
                        $checkBalance = $journ->debit - $journ->credit;
                        $balanceRev += $checkBalance;
                    }    

                    if ($val['type'] == 1) {
                        $realBalance = $balanceRev < 0 ? (-1 * abs($balanceRev)) : abs($balanceRev);
                    } else {
                        $realBalance = $balanceRev < 0 ? abs($balanceRev) : (-1 * abs($balanceRev));
                    }

                    $journalParam['sub_coa'] = $splitCOA[0];
                    $journalParam['name'] = $val['name'];
                    $journalParam['accounting_code'] = $val['accounting_code'];
                    $journalParam['total'] = $realBalance;
                    $journalData[] = $journalParam;
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $journalData
        ]);
    }
}