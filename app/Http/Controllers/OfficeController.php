<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Office;


class OfficeController extends Controller
{
    static function setDate(){
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
