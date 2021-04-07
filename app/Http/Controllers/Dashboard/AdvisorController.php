<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;
use App\Http\Controllers\OfficeController;
use App\Events\UserOnlineMsg;
use App\SpecialityTypeUser;
use App\UserOffice;
use App\Office;
use App\Shift;
use App\User;


class AdvisorController extends Controller
{

    static function selectAdviser($specialityId) {
        $officeId = session('OFFICE');
        $advisersData = AdvisorController::adviserAvialable($officeId, $specialityId);

        if ($advisersData['adviserCount'] > 1) {
            // MÁS DE UN ASESOR EN LÍNEA

            $adviserSort = AdvisorController::arraySort($advisersData['arrAdvisers'], 'total_shifts');

         
            $return = $adviserSort[0]['id'];
        } else {
            // SOLO UN ASESOR EN LÍNEA CON LA ESPECIALIDAD
            $return = $advisersData['arrAdvisers'][0]['id'];
        }
        
        return $return;
    }

    static function arraySort($array, $on) {
        foreach ($array as $value) {
            $lowerArray[] = strtolower($value[$on]);
        }

        asort($lowerArray);

        foreach ($lowerArray as $key => $v) {
            $newArray[] = $array[$key];
        }

        return $newArray;
    }

    static function adviserAvialable($officeId, $specialityId) {
        $arrAdvisers = array();

        // SE BUSCAN LOS ASESORES DISPONIBLES CON LA ESPECIALIDAD INDICADA
        $objAdvisers = User::join('user_offices','users.id', '=', 'user_offices.user_id')
                                ->join('speciality_type_users','users.id', '=', 'speciality_type_users.user_id')
                                ->select(
                                    'users.id',
                                    'users.name',
                                    'users.is_active',
                                    'user_offices.office_id',
                                    'speciality_type_users.speciality_type_id'
                                )
                                ->where([
                                    ['user_offices.office_id', $officeId],
                                    ['speciality_type_users.speciality_type_id', $specialityId],
                                    ['user_offices.is_active', 1],
                                    ['users.user_type_id', 3]
                                ])
                                ->get();

        // SE CUENTA EL TOTAL DE TURNOS EN ESPERA DE CADA ASESOR Y SE GENEREA EL ARRAY A DEVOLVER
        foreach ($objAdvisers as $adviser) {
            $shiftAdviserCount = Shift::where([
                                ['shifts.user_advisor_id', $adviser->id],
                                ['shifts.shift_status_id', 1],
                                ['shifts.created_at', 'like', OfficeController::setDate().'%'],
                                ['shifts.is_active', 1]
                            ])
                            ->count();

            array_push($arrAdvisers, array(
                'id'                    => $adviser->id,
                'adviser'               => $adviser->name,
                'total_shifts'          => $shiftAdviserCount
            ));
        }

        return [
            'office_id'         => $officeId,
            'speciality_id'     => $specialityId,
            'adviserCount'      => $objAdvisers->count(),
            'arrAdvisers'       => $arrAdvisers, 
        ];
    } 

    public function userStatusOn(){
        $userId = Auth::id();

        $channel = Office::join('user_offices', 'offices.id', '=', 'user_offices.office_id')
                        ->where('user_offices.user_id', $userId)
                        ->select(
                            'offices.user_channel',
                            'offices.id as office'
                        )
                        ->first();

        $changeStatus = UserOffice::where([
                            ['office_id', $channel->office],
                            ['user_id', $userId]
                        ])
                        ->first();

        $changeStatus->is_active = 1;

        if ($changeStatus->save()) {
            $return = [
                'type' => 'success',
                'text' => 'Conectado. Ya puedes recibir turnos',
                'icon' => 'far fa-check-circle',
            ];
            event(new UserOnlineMsg($channel->user_channel, 1));
        } else {
            $return = [
                'type' => 'info',
                'text' => 'Error al conectar',
                'icon' => 'far fa-times-circle',
            ];
        }

        return $return;
    }

    public static function userStatusOff(){
        $userId = Auth::id();

        $channel = Office::join('user_offices', 'offices.id', '=', 'user_offices.office_id')
                        ->where('user_offices.user_id', $userId)
                        ->select(
                            'offices.user_channel',
                            'offices.id as office'
                        )
                        ->first();

        $changeStatus = UserOffice::where([
                            ['office_id', $channel->office],
                            ['user_id', $userId]
                        ])
                        ->first();

        if ($changeStatus->is_active) {
            $changeStatus->is_active = 0;
            $changeStatus->save();
            event(new UserOnlineMsg($channel->user_channel, 1));
        }

        return true;
    }

    public function break(Request $request){
        $case = $request->input('case');
        $userId = Auth::id();
        $return = null;

        $channel = Office::join('user_offices', 'offices.id', '=', 'user_offices.office_id')
                            ->where('user_offices.user_id', $userId)
                            ->select(
                                'offices.user_channel',
                                'offices.id as office'
                            )
                            ->first();

        $changeStatus = UserOffice::where([
                                        ['office_id', $channel->office],
                                        ['user_id', $userId]
                                    ])
                                    ->first();

        switch ($case) {
            case 1:
                // DESDE INICIO DEL SERVICIO, SOLO SE COMPRUEBA EL ESTADO DEL USUARIO
                $return = [
                    'case' => 1,
                    'state' => $changeStatus->is_active,
                    'btnText' => (($changeStatus->is_active == 1)? 'Desconectar':'Conectar'),
                    'btnType' => (($changeStatus->is_active == 1)? 'btn-outline-danger':'btn-outline-success')
                ];
                break;

            case 2:
                //DESDE EL BOTÓN, SOLO CAMBIAMOS EL ESTADO DEL USUARIO
                if ($changeStatus->is_active == true) {

                    $changeStatus->is_active = 0;
                    $changeStatus->save();
        
                    $return = [
                        'case' => 2,
                        'state' => $changeStatus->is_active,
                        'text' => '<b>Desconectado</b>: No se reciben turnos',
                        'type' => 'info',
                        'icon' => 'far fa-times-circle',
                        'btnText' => 'Conectar',
                        'btnType' => 'btn-outline-success'
                    ];
                } else {
                    $changeStatus->is_active = 1;
                    $changeStatus->save();
        
                    $return = [
                        'case' => 2,
                        'state' => $changeStatus->is_active,
                        'text' => '<b>Conectado</b>: Disponible para atender',
                        'type' => 'info',
                        'icon' => 'far fa-check-circle',
                        'btnText' => 'Desconectar',
                        'btnType' => 'btn-outline-danger'
        
                    ];
                }

                event(new UserOnlineMsg($channel->user_channel, 1));

                break;
            
            default:
                break;
        }
        return $return;
    }

    public function reassined(){
        $office = UserOffice::where('user_id', Auth::id())->first();

        $shifts = Shift::join('speciality_types', 'shifts.speciality_type_id', 'speciality_types.id')
                        ->join('shift_status', 'shifts.shift_status_id', 'shift_status.id')
                        ->join('users', 'shifts.user_advisor_id', 'users.id')
                        ->where([
                            ['shifts.office_id', $office->office_id],
                            ['shifts.is_active', true],
                            ['shifts.created_at', 'like', OfficeController::setDate().'%']
                        ])
                        ->select(
                            'shifts.id',
                            'shifts.shift',
                            'speciality_types.name as speciality',
                            'shift_status.id as status_id',
                            'shift_status.shift_status as status',
                            'users.email',
                            'shifts.is_reassigned',
                            'shifts.created_at',
                        )
                        ->get();

        return view('dashboard.contents.shifts.Reassigned', ['lstShifts' => $shifts]);
    }
}
