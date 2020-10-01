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

        $objShift = Shift::where('shifts.id', $idShift)
                        ->first();


        return ['shift' => $objShift];
    }
}
