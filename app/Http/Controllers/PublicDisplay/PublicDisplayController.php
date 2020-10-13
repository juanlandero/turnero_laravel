<?php

namespace App\Http\Controllers\PublicDisplay;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Shift;
use App\Office;

class PublicDisplayController extends Controller
{
    public function index(){
        return view('Index');
    }

    public function publicMenu() {
        return view('public.PublicMenu');
    }

    public function verifyAccess(Request $request){
        $numberOffice = $request->input('office');

        $result = Office::where([
                                ['office_key', $numberOffice],
                                ['is_active', 1]
                            ])->first();

        if ($result != null) {
            session()->put('NUM_OFFICE', $result->id);
            session()->put('DATE', \App\Http\Controllers\OfficeController::setDate());

            return ['success' => 'true', 'text' => 'public'];
        } else {
            return ['success' => 'false', 'text' => '¡Error! Código incorrecto'];
        }
    }

    public function shiftSelector(){
        return view('public/ShiftSelector');
    }

    public function numberDisplay(){
        return view('public/NumberDisplay');
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
                                ['shifts.shift_status_id', '<', 3],
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
                                ['shifts.shift_status_id', '<', 3],
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
