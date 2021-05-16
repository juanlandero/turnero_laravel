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
            session()->put('OFFICE', $result->id);
            return ['success' => 'true', 'text' => 'public'];
        } else {
            return ['success' => 'false', 'text' => '¡Error! Código incorrecto'];
        }
    }

    public function shiftSelector(){
        return view('public.ShiftSelector');
    }

    public function numberDisplay(){
        $officeId = session('OFFICE');

        $objAds = Ad::where([
                        ['office_id', $officeId],
                        ['is_active', 1]
                    ])
                    ->orderBy('order', 'asc')
                    ->get();

        return view('public.NumberDisplay', ['ads' => $objAds]);
    }

    public function getListShifts(){
        $channel = Office::select(
                                'menu_channel',
                                'panel_channel'
                            )
                            ->where('id', session('OFFICE'))
                            ->first();

        $listShift = Shift::where([
                                ['shifts.is_active', 1],
                                ['shifts.shift_status_id', 1],
                                ['shifts.office_id', session('OFFICE')],
                                ['shifts.created_at', 'like', OfficeController::setDate().'%']
                            ])
                            ->select(
                                'shifts.id',
                                'shifts.shift',
                                'shifts.shift_status_id',
                                'shifts.is_active',
                                'shifts.created_at',
                            )
                            ->orderBy('shifts.id', 'asc')
                            ->get();

        return ['listShift' => $listShift, 'channel' => $channel];
    }

    public function getShift(Request $request){
        $shiftId = $request->input('shiftId');

        $newShift = Shift::where([
                                ['shifts.shift_status_id', 1],
                                ['shifts.is_active', 1],
                                ['shifts.created_at', 'like', OfficeController::setDate().'%'],
                                ['shifts.id', $shiftId]
                            ])
                            ->select(
                                'shifts.id',
                                'shifts.shift',
                                'shifts.shift_status_id',
                                'shifts.is_active',
                                'shifts.created_at'
                            )
                            ->first();

        return ['shift' => $newShift];
    }
}
