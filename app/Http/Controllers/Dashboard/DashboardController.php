<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OfficeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\SpecialityTypeUser;
use App\UserOffice;
use App\Client;
use App\Shift;
use App\User;


class DashboardController extends Controller
{
    public function adminShift(){
        return view('dashboard.contents.shifts.Index', ['id' => Auth::id()]);
    }

    public function getDataPanel(){
        $arrSpecialities = array();
        $countShift = 0;

        $channel = UserOffice::join('offices', 'user_offices.office_id', '=', 'offices.id')
                            ->join('users', 'user_offices.user_id', 'users.id')
                            ->where([
                                ['user_offices.user_id', Auth::id()],
                            ])
                            ->select(
                                'offices.menu_channel',
                                'offices.panel_channel',
                                'offices.id as office_id'
                                )
                            ->first();

        $userSpecialities = SpecialityTypeUser::join('speciality_types', 'speciality_type_users.speciality_type_id', 'speciality_types.id')
                                                ->where('speciality_type_users.user_id', Auth::id())
                                                ->select(
                                                    'speciality_type_users.speciality_type_id',
                                                    'speciality_types.name'
                                                )
                                                ->orderBy('speciality_type_users.speciality_type_id', 'ASC')
                                                ->get();
                    
        foreach ($userSpecialities as $speciality) {

            $countShift = Shift::where([
                                    ['shifts.speciality_type_id', $speciality->speciality_type_id],
                                    ['shifts.shift_status_id', 1],
                                    ['shifts.office_id', $channel->office_id],
                                    ['shifts.is_active', 1],
                                ])
                                ->count();

            array_push($arrSpecialities, array(
                'speciality_type_id'    => $speciality->speciality_type_id,
                'name'                  => $speciality->name,
                'total'                 => $countShift
            ));
        }

        return [
            'channel'       => $channel,
            'idUser'        => Auth::id(),
            'idOffice'      => $channel->office_id,
            'specialities'  => $arrSpecialities
        ];
    }

    public function getShiftAdvisor(Request $request){
        $type = $request->input('type');
        $objShift = null;
        $arrShift = array();
        $numberClient = 0;
        $nameClient = 'Visitante';

        switch ($type) {
            case 1:
                $idUser = $request->input('userId');

                $objShift = Shift::join('shift_types', 'shifts.shift_type_id', '=', 'shift_types.id')
                                ->join('speciality_types', 'shifts.speciality_type_id', '=', 'speciality_types.id')
                                ->where([
                                    ['shifts.user_advisor_id', $idUser],
                                    ['shifts.shift_status_id', '<>',3],
                                    ['shifts.end_shift', '=', null],
                                    ['shifts.created_at', 'like', OfficeController::setDate().'%'],
                                    ['shifts.is_active', 1]
                                ])
                                ->select(
                                    'shifts.id',
                                    'shifts.shift_status_id',
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
                        'status'        => $shift->shift_status_id,
                        'shift'         => $shift->shift,
                        'shift_type'    => $shift->shift_type,
                        'speciality'    => $shift->speciality,
                        'time'          => $shift->time,
                        'number_client' => $numberClient,
                        'name_client'   => $nameClient,
                        'sex_client'    => $shift->sex_client
                    ));
                }

                break;

            case 2:
                $shiftId = $request->input('shiftId');

                $objShift = Shift::join('shift_types', 'shifts.shift_type_id', '=', 'shift_types.id')
                                ->join('speciality_types', 'shifts.speciality_type_id', '=', 'speciality_types.id')
                                ->where('shifts.id', $shiftId)
                                ->select(
                                    'shifts.id',
                                    'shifts.shift',
                                    'shifts.shift_status_id',
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
                    'status'        => $objShift->shift_status_id,
                    'shift'         => $objShift->shift,
                    'shift_type'    => $objShift->shift_type,
                    'speciality'    => $objShift->speciality,
                    'time'          => $objShift->time,
                    'number_client' => $numberClient,
                    'name_client'   => $nameClient,
                    'sex_client'    => $objShift->sex_client
                ));

                break;
            
            default:
                $arrShift = array();
                break;
        }

        return $arrShift;
    }

    public function getAdvisers (Request $request) {
        $return = 0;
        $userId = Auth::id();
        $shiftId = $request->input('shift_id');

        $canReassined = Shift::where([
                            ['id', $shiftId],
                            ['is_reassigned', 1],
                            ['is_active', 1]
                        ])
                        ->get();

        if ($canReassined->count() > 0) {
            $alert = [
                'type' => 'warning',
                'text' => 'No puedes reasignar 2 veces este turno.',
                'icon' => 'fas fa-exclamation-triangle',
            ];

            return ['alert' => $alert, 'success' => 0];
        } else {
            $office = UserOffice::where('user_id', Auth::id())->first();
            
            if (Auth::user()->user_type_id != 3) {
                $user = Shift::find($shiftId);
                $userId = $user->user_advisor_id;
            }

            $objAdvisers = UserOffice::join('users', 'user_offices.user_id', '=', 'users.id')
                                        ->where([
                                            ['user_offices.office_id', $office->office_id],
                                            ['user_offices.user_id', '<>', $userId],
                                            ['user_offices.is_active', 1],
                                            ['users.user_type_id', 3]
                                        ])
                                        ->select(
                                            'user_offices.id as user_office',
                                            'user_offices.user_id as user',
                                            'user_offices.box_id as box',
                                            'users.user_type_id',
                                            'users.name',
                                            'users.first_name',
                                            'users.second_name',
                                        )
                                        ->get();
                                        
            if ($objAdvisers->count() > 0) {
                $return = ['objAdvisers' => $objAdvisers, 'success' => 1];
            } else {
                $alert = [
                    'type' => 'warning',
                    'text' => 'No hay más asesores en línea.',
                    'icon' => 'fas fa-users-slash',
                ];
                return ['alert' => $alert, 'success' => 0];
            }
        }

        return $return;
    }
}
