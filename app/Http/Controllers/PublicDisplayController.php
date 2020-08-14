<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicDisplayController extends Controller
{
    public function numberDisplay(){
        return view('public_display/public');
    }
}
