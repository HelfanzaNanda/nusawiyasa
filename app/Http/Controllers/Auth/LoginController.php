<?php

namespace App\Http\Controllers\Auth;

use Session;
use App\Http\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\GeneralSetting\GeneralSetting;

class LoginController extends Controller
{
    public function __construct()
    {
        if (Session::get('_login')) {
            return redirect('/');
        }
    }

    public function index()
    {
        return view('auth.login', [
            'company_logo' => GeneralSetting::getCompanyLogo(),
            'company_name' => GeneralSetting::getCompanyName()
        ]);
    }

    public function login(Request $request)
    {
        $params = $request->all();
        return Users::login_web($params, $request->method(), $request);
    }

    public function authorizes(Request $request)
    {
        $params = $request->all();
        return Users::authorize($params, $request->method(), $request);
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();
        
        return redirect('/');
    }

    public function forgotPassword(Request $request)
    {
        $params = $request->all();
        return Users::resetPassword($params);
    }
}
