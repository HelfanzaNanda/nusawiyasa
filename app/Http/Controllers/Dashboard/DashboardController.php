<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Models\UserCompanyDepartment;
use Illuminate\Http\Request;
use Session;

class DashboardController extends Controller
{
	// D:\Projects\php\sedata\vendor\laravel\framework\src\Illuminate\Contracts\Session\Session.php
    public function index()
    {
        return view('dashboard.index');
    }
}
