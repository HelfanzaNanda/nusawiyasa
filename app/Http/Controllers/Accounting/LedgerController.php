<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    public function index()
    {
        return view('accounting.ledger.'.__FUNCTION__);
    }
}