<?php

namespace App\Http\Controllers\Dashboard;

use Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\UserCompanyDepartment;
use App\Http\Models\GeneralSetting\GeneralSetting;

class DashboardController extends Controller
{
	// D:\Projects\php\sedata\vendor\laravel\framework\src\Illuminate\Contracts\Session\Session.php
    public function index()
    {
        return view('dashboard.index', [
            'company_logo' => GeneralSetting::getCompanyLogo(),
            'company_name' => GeneralSetting::getCompanyName()
        ]);
    }
}
