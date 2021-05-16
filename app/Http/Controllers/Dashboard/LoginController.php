<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Dashboard\AdvisorController;
use App\Http\Controllers\Controller;
use App\Library\Errors;
use Auth;
use Session;
use Redirect;

class LoginController extends Controller
{
    const DASHBOARD_SESSION = 'bdc49af8b451637f4d1a714fb922e731';

    public function index() {
        return view('dashboard.contents.Login');
    }

    public function store(Request $request) {
        if(Auth::attempt([  'email'     => $request['txtEmail'],
                            'password'  => $request['txtPassword'],
                            'is_active' => 1])) {
            
            Session::put('_DASHBOARD_SESSION_', $this::DASHBOARD_SESSION);
            return Redirect('dashboard');
        } else {
            Session::flash("login_error_title", Errors::LOGIN_01_TITLE);
            Session::flash("login_error_message", Errors::LOGIN_01_MESSAGE);
            return Redirect('dashboard/login');
        }
    }

    public function logout() {
        if(Auth::check()) {
            if (Auth::user()->user_type_id == 3) {
                AdvisorController::userStatusOff();
            }
            
            Auth::logout();
            Session::flush();
        }
        return Redirect('dashboard/login');
   }
}
