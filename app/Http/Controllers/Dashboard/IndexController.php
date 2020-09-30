<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class IndexController extends Controller
{
    public function index() {
        if(!Auth::check()) {
            return Redirect('dashboard/login');
        }

        return view('dashboard.contents.Index');
    }
}
