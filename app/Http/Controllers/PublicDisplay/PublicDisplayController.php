<?php

namespace App\Http\Controllers\PublicDisplay;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OfficeController;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use App\Office;
use App\Shift;
use App\Ad;

class PublicDisplayController extends Controller
{
    public function index(){
        return response()->view('Index')->withCookie(Cookie::forget('OFFICE'));
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
            return response()->json(['success' => 'true', 'text' => 'public'])->cookie('OFFICE', $result->id, 600);
        } else {
            return ['success' => 'false', 'text' => '¡Error! Código incorrecto'];
        }
    }

    public function shiftSelector(){
        return view('public/ShiftSelector');
    }

    public function numberDisplay(){
        $officeId = Cookie::get('OFFICE');

        $objAds = Ad::where([
                        ['office_id', $officeId],
                        ['is_active', 1]
                    ])
                    ->get();

        return view('public/NumberDisplay', ['ads' => $objAds]);
    }

    public function getListShifts(){
        $channel = Office::select(
                                'menu_channel',
                                'panel_channel'
                        )
                        ->where('id', Cookie::get('OFFICE'))
                        ->first();

        $listShift = Shift::join('users', 'shifts.user_advisor_id', '=', 'users.id')
                            ->join('user_offices', 'users.id', '=', 'user_offices.user_id')
                            ->join('boxes', 'user_offices.box_id', 'boxes.id')
                            ->where([
                                ['shifts.is_active', 1],
                                ['shifts.shift_status_id', '<', 3],
                                ['shifts.created_at', 'like', OfficeController::setDate().'%']
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

    public function getShift(Request $request){
        $shiftId = $request->input('shiftId');

        $listShift = Shift::join('users', 'shifts.user_advisor_id', '=', 'users.id')
                            ->join('user_offices', 'users.id', '=', 'user_offices.user_id')
                            ->join('boxes', 'user_offices.box_id', 'boxes.id')
                            ->where([
                                ['shifts.shift_status_id', '<', 3],
                                ['shifts.is_active', 1],
                                ['shifts.created_at', 'like', OfficeController::setDate().'%'],
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
