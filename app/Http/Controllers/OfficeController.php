<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Office;


class OfficeController extends Controller
{
    static function setDate(){
        $date = getdate();
        $month = $date['mon'];
        $day = $date['mday'];

        if (strlen($date['mon']) == 1)
            $month = "0" . $date['mon'];
            
        if (strlen($date['mday']) == 1)
            $day = "0" . $date['mday'];

        $today = $date['year'] . "-" . $month . "-" . $day;
    
        return $today;
    }
}
