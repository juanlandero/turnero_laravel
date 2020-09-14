<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Office;


class OfficeController extends Controller
{
    public function verifyOffice(Request $request){
        $numberOffice = $request->input('office');

        $result = Office::where([
                                ['office_key', $numberOffice],
                                ['is_active', 1]
                            ])->first();

        if ($result != null) {
            session()->put('NUM_OFFICE', $result->id);
            session()->put('DATE', OfficeController::setDate());

            return ['success' => 'true', 'text' => 'public/shift'];
        } else {
            return ['success' => 'false', 'text' => '¡Error! Código incorrecto'];
        }
    }


    public function setDate(){
        $date = getdate();

        $today = $date['year'] . "-" . $date['mon'] . "-" . $date['mday'];

        if (strlen($date['mon']) == 1) {
            $today = $date['year'] . "-0" . $date['mon'] . "-" . $date['mday'];
            
        } elseif (strlen($date['mday']) == 1) {
            $today = $date['year'] . "-" . $date['mon'] . "-0" . $date['mday'];
        } 
    
        return $today;
    }
}
