<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UserOffice;
use App\User;
use App\Shift;


class DashboardController extends Controller
{
    public function adminShift(){
        return view('dashboard.contents.AdminShift', ['id' => Auth::id()]);
    }

    public function getUser(){

        $channel = UserOffice::join('offices', 'user_offices.office_id', '=', 'offices.id')
                            ->where('user_offices.user_id', Auth::id())
                            ->select(
                                'offices.channel'
                                )
                            ->first();

        return ['channel' => $channel->channel, 'idUser' => Auth::id()];
    }

    public function getShiftAdvisor(Request $r){

        $idShift = $r->input('shiftId');

        $objShift = Shift::join('shift_types', 'shifts.shift_type_id', '=', 'shift_types.id')
                        ->join('speciality_types', 'shifts.speciality_type_id', '=', 'speciality_types.id')
                        ->where('shifts.id', $idShift)
                        ->select(
                            'shifts.id',
                            'shifts.shift',
                            'shift_types.shift_type',
                            'speciality_types.name as speciality',
                            'shifts.created_at as time'
                        )
                        ->first();


        return ['shift' => $objShift];
    }
}
