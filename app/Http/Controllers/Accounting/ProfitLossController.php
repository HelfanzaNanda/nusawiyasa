<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfitLossController extends Controller
{
    public function index()
    {
        return view('accounting.profit_loss.'.__FUNCTION__);
    }
}