<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UserOffice;
use App\User;
use App\Shift;
use App\Client;


class DashboardController extends Controller
{
    public function adminShift(){
        return view('dashboard.contents.AdminShift', ['id' => Auth::id()]);
    }

    public function getUser(){

        $channel = UserOffice::join('offices', 'user_offices.office_id', '=', 'offices.id')
                            ->where('user_offices.user_id', Auth::id())
                            ->select(
                                'offices.menu_channel',
                                'offices.panel_channel'
                                )
                            ->first();

        return ['channel' => $channel, 'idUser' => Auth::id()];
    }

    public function getShiftAdvisor(Request $r){

        $type = $r->input('type');
        $objShift = null;
        $arrShift = array();
        $numberClient = 0;
        $nameClient = 'Visitante';

        if ($type == 1) {
            $idUser = $r->input('userId');

            $objShift = Shift::join('shift_types', 'shifts.shift_type_id', '=', 'shift_types.id')
                            ->join('speciality_types', 'shifts.speciality_type_id', '=', 'speciality_types.id')
                            ->where([
                                ['shifts.user_advisor_id', $idUser],
                                ['shifts.shift_status_id', '<', 3],
                                ['shifts.created_at', 'like', session()->get('DATE').'%'],
                                ['shifts.is_active', 1]
                            ])
                            ->select(
                                'shifts.id',
                                'shifts.shift',
                                'shifts.sex_client',
                                'shifts.number_client',
                                'shift_types.shift_type',
                                'speciality_types.name as speciality',
                                'shifts.created_at as time'
                            )
                            ->orderBy('shifts.id', 'asc')
                            ->get();


            foreach ($objShift as $shift) {
                
                if ($shift->number_client != null) {

                    $objClient = Client::where('client_number', $shift->number_client)->first();
                    
                    $numberClient = $objClient->client_number;
                    $nameClient = $objClient->name . " " . $objClient->first_name . " " . $objClient->second_name;
                }

                array_push($arrShift, array(
                    'id'            => $shift->id,
                    'shift'         => $shift->shift,
                    'shift_type'    => $shift->shift_type,
                    'speciality'    => $shift->speciality,
                    'time'          => $shift->time,
                    'number_client' => $numberClient,
                    'name_client'   => $nameClient,
                    'sex_client'    => $shift->sex_client
                ));
            }


        } elseif ($type == 2) {
            $idShift = $r->input('shiftId');

            $objShift = Shift::join('shift_types', 'shifts.shift_type_id', '=', 'shift_types.id')
                            ->join('speciality_types', 'shifts.speciality_type_id', '=', 'speciality_types.id')
                            ->where('shifts.id', $idShift)
                            ->select(
                                'shifts.id',
                                'shifts.shift',
                                'shift_types.shift_type',
                                'speciality_types.name as speciality',
                                'shifts.number_client',
                                'shifts.sex_client',
                                'shifts.created_at as time'
                            )
                            ->first();

            if ($objShift->number_client != null) {

                $objClient = Client::where('client_number', $objShift->number_client)->first();
                
                $numberClient = $objClient->client_number;
                $nameClient = $objClient->name . " " . $objClient->first_name . " " . $objClient->second_name;
            }

            array_push($arrShift, array(
                'id'            => $objShift->id,
                'shift'         => $objShift->shift,
                'shift_type'    => $objShift->shift_type,
                'speciality'    => $objShift->speciality,
                'time'          => $objShift->time,
                'number_client' => $numberClient,
                'name_client'   => $nameClient,
                'sex_client'    => $objShift->sex_client
            ));
        }

        // return ['shift' => $objShift, 'S2' =>$arrShift];
        return $arrShift;
    }
}
