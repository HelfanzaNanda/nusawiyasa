<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Models\Accounting\AccountingJournal;
use App\Http\Models\Accounting\AccountingMaster;
use App\Http\Models\Ref\DefaultAccount;
use DB;
use Illuminate\Http\Request;

class BalanceSheetController extends Controller
{
    public function index()
    {
        return view('accounting.balance_sheet.'.__FUNCTION__);
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

        $default_profit_loss_account = DefaultAccount::where('key', 'profit_loss')->value('value');

        $journalData = [];
        $journalData2 = [];

        foreach ($getAccount as $key => $val) {
            $splitCOA = explode(".", $val['accounting_code']);

            if ($splitCOA[0] == 1 || $splitCOA[0] == 2 || $splitCOA[0] == 3) {
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
                        $getJournal->whereBetween(DB::raw("DATE(accounting_journals.date)"), [$request['sDate'], $request['eDate']]);
                    }

                    foreach ($getJournal->get() as $journ) {
                        $checkBalance = $journ->debit - $journ->credit;
                        $balanceRev += $checkBalance;
                    }    

                    if ($val['type'] == 1) {
                        $realBalance = $balanceRev < 0 ? $balanceRev : abs($balanceRev);
                    } else {
                        $realBalance = $balanceRev < 0 ? abs($balanceRev) : $balanceRev;
                    }

                    $journalParam['coa'] = $val['coa'];
                    $journalParam['sub_coa'] = $splitCOA[0];
                    $journalParam['name'] = $val['name'];
                    $journalParam['accounting_code'] = $val['accounting_code'];
                    $journalParam['total'] = $realBalance;
                    $journalData[] = $journalParam;
                }
            } 

            if ($splitCOA[0] == 4 || $splitCOA[0] == 5 || $splitCOA[0] == 6 || $splitCOA[0] == 7) {
                if (strlen($val['coa']) > 1) {
                    $balanceRev2 = 0;

                    $getJournal2 = AccountingJournal::select([
                                    'accounting_ledgers.debit',
                                    'accounting_ledgers.credit',
                                    'accounting_journals.date'
                                ])
                                ->join('accounting_ledgers', 'accounting_journals.id', '=', 'accounting_ledgers.accounting_journal_id')
                                ->join('accounting_masters', 'accounting_masters.coa', '=', 'accounting_ledgers.coa')
                                ->where('accounting_ledgers.coa', $val['coa']);

                    if (empty($request['sDate']) && empty($request['eDate'])) {
                        $getJournal2->whereMonth('accounting_journals.date', '=', date('m'))
                                ->whereYear('accounting_journals.date', '=', date('Y'));
                    } else {
                        $getJournal2->whereBetween(DB::raw("DATE(accounting_journals.date)"), [$request['sDate'], $request['eDate']]);
                    }

                    foreach ($getJournal2->get() as $journ2) {
                        $checkBalance2 = $journ2->debit - $journ2->credit;
                        $balanceRev2 += $checkBalance2;
                    }    

                    if ($val['type'] == 1) {
                        $realBalance2 = $balanceRev2 < 0 ? (-1 * abs($balanceRev2)) : abs($balanceRev2);
                    } else {
                        $realBalance2 = $balanceRev2 < 0 ? abs($balanceRev2) : (-1 * abs($balanceRev2));
                    }

                    $journalParam2['sub_coa'] = $splitCOA[0];
                    $journalParam2['name'] = $val['name'];
                    $journalParam2['accounting_code'] = $val['accounting_code'];
                    $journalParam2['total'] = $realBalance2;
                    $journalData2[] = $journalParam2;
                }
            }
        }

        $profitSum = 0;
        $lossSum = 0;

        foreach ($journalData2 as $pl) {
            if ($pl['sub_coa'] == 4 || $pl['sub_coa'] == 6) {
                $profitSum += $pl['total'];
            }

            if ($pl['sub_coa'] == 5 || $pl['sub_coa'] == 7) {
                $lossSum += $pl['total'];
            }
        }

        $profitLossSum = $profitSum - $lossSum;

        return response()->json([
            'status' => 'success',
            'data' => $journalData,
            'profitLoss' => $profitLossSum > 0 ? abs($profitLossSum) : (-1 * abs($profitLossSum)),
            'default_profit_loss_account' => $default_profit_loss_account
        ]);
    }
}