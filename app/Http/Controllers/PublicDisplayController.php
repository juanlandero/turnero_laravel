<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SpecialityType;
use App\Shift;

class PublicDisplayController extends Controller
{
    public function numberDisplay(){
        return view('display/NumberDisplay');
    }

    public function shiftSelector(){
        return view('display/ShiftSelector');
    }

    public function getSpeciality(){
        $speciality = SpecialityType::all();

        return $speciality;
    }

    public function getListShifts(){
        $listShift = Shift::where([
                                ['shift_status_id', 1],
                                ['is_active', 1],
                            ])->get();

        return ['listShift' => $listShift];
    }
}
