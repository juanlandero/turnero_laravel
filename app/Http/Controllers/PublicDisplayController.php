<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shift;
use App\Office;

class PublicDisplayController extends Controller
{
    public function index(){
        return view('Index');
    }

    public function shiftSelector(){
        return view('display/ShiftSelector');
    }

    public function numberDisplay(){
        return view('display/NumberDisplay');
    }

    public function getListShifts(){
        $listShift = Shift::where([
                                ['shift_status_id', 1],
                                ['is_active', 1],
                            ])->get();

        return ['listShift' => $listShift];
    }
}
