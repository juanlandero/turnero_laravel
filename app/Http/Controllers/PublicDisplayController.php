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

    public function getDataOffice(){
        $officeId = session()->get('NUM_OFFICE');

        $channel = Office::select('menu_channel')->where('id', $officeId)->first();

        return $channel;
    }

    public function numberDisplay(){
        return view('display/NumberDisplay');
    }

    public function getListShifts(){
        
        $channel = Office::select(
                                'menu_channel',
                                'panel_channel'
                        )->where('id', session()->get('NUM_OFFICE'))->first();

        $listShift = Shift::join('users', 'shifts.user_advisor_id', '=', 'users.id')
                            ->join('user_offices', 'users.id', '=', 'user_offices.user_id')
                            ->join('boxes', 'user_offices.box_id', 'boxes.id')
                            ->where([
                                ['shifts.shift_status_id', 1],
                                ['shifts.is_active', 1],
                                ['shifts.created_at', 'like', session()->get('DATE').'%']
                            ])
                            ->select(
                                'shifts.id',
                                'shifts.shift',
                                'shifts.shift_status_id',
                                'shifts.is_active',
                                'shifts.created_at',
                                'boxes.box_name'
                            )
                            ->orderBy('shifts.id', 'asc')
                            ->get();

        return ['listShift' => $listShift, 'channel' => $channel];
    }

    public function getShift(Request $r){

        $shiftId = $r->input('shiftId');

        $listShift = Shift::join('users', 'shifts.user_advisor_id', '=', 'users.id')
                            ->join('user_offices', 'users.id', '=', 'user_offices.user_id')
                            ->join('boxes', 'user_offices.box_id', 'boxes.id')
                            ->where([
                                ['shifts.shift_status_id', 1],
                                ['shifts.is_active', 1],
                                ['shifts.created_at', 'like', session()->get('DATE').'%'],
                                ['shifts.id', $shiftId]
                            ])
                            ->select(
                                'shifts.id',
                                'shifts.shift',
                                'shifts.shift_status_id',
                                'shifts.is_active',
                                'shifts.created_at',
                                'boxes.box_name'
                            )
                            ->first();

        return ['shift' => $listShift];
    }
}
