<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BalanceSheetController extends Controller
{
    public function index()
    {
        return view('accounting.balance_sheet.'.__FUNCTION__);
    }
}