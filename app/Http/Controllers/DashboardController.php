<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function adminShift(){
        return view('dashboard.contents.AdminShift');
    }
}
