<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shift;

class ShiftController extends Controller
{
    public function create(Request $request){

        $specialityId = $request->input('speciality');
        $clientNumber = $request->input('client_number');
        $sex = $request->input('sex');

        $newTicket = new Shift();

        $newTicket->shift = "E002";
        $newTicket->shift_type_id = 1;
        $newTicket->speciality_type_id = $specialityId;
        $newTicket->office_id = 1;
        $newTicket->shift_status_id = 1;
        $newTicket->user_advisor_id = 1;
        $newTicket->has_incident = 0;
        $newTicket->is_active = 1;
        $newTicket->save();

        return ['id' => $newTicket->id];
    }
}
