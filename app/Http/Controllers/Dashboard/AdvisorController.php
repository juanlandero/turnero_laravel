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
    static function selectAdvisor($specialityId){
        $officeId = session('OFFICE');
        $arrayAdvisor = array();
        
        // OBTENIENDO EL NÚMERO TOTAL DE ASESORES QUE TIENE LA SUCURSAL
        $objAdvisorCount = User::join('user_offices','users.id', '=', 'user_offices.user_id')
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

        if ($objAdvisorCount->count() > 1) {
            // echo "1. Mas de un asesor <br/>";
            // BUSCA LOS ASESORES QUE TIENEN TURNOS Y ELIGE UN ASESOR PARA ASIGNARLE EL NUEVO TURNO
            $objAdvisorWithShift = Shift::join('users', 'shifts.user_advisor_id', '=', 'users.id')
                                ->join('user_offices', 'users.id', '=', 'user_offices.user_id')
                                ->where([
                                    ['user_offices.office_id', $officeId],
                                    ['shifts.speciality_type_id', $specialityId],
                                    ['shifts.created_at', 'like', OfficeController::setDate().'%']
                                ])
                                ->select(
                                    'shifts.id',
                                    'shifts.shift',
                                    'shifts.shift_type_id',
                                    'shifts.created_at',
                                    'user_offices.office_id',
                                    'user_offices.user_id',
                                    'users.name'
                                )
                                ->orderBy('shifts.id', 'desc')
                                ->limit(sizeof($objAdvisorCount))
                                ->get();

            if ($objAdvisorWithShift->count() == 0) {
                // echo "2. Los asesores no tiene turnos<br/>";
                $return = $objAdvisorCount[0]->id;
            } else {
                // SE PASA A UN ARRAY PARA ELIMINAR DUPLICADOS
                // echo "2. Los asesores tiene turnos<br/>";
                foreach ($objAdvisorWithShift as $advisor) {
                    array_push($arrayAdvisor, $advisor->user_id );
                }

                $arrayAdvisor = array_unique($arrayAdvisor);

                // EN CASO DE NO TENER DUPLICADOS SE ENVÍA EL ID DE UN ASESOR QUE NO HA TENIDO TURNOS
                if (count($arrayAdvisor) == $objAdvisorCount->count()) {
                    // echo "3. Todos los asesores estan trabando correctamente<br/>";
                    $return = $objAdvisorWithShift[count($arrayAdvisor)-1]->user_id;

                } else {
                    // echo "3. Hay asesores que no han recibido turnos<br/>";
                    // SI SE ELIMINAN DUPLICADOS SE BUSCA AL ASESOR QUE NO HA RECIBIDO TURNOS TOMANDOLO DEL PRIMERO OBJTETO
                    $existId = false;
                    $choicedId = 0;
                    foreach ($objAdvisorCount as $advisor) {
                        // echo "Asesor: ".$advisor->id."<br/>";

                        foreach ($arrayAdvisor as $idAdvisor) {
                            // echo "AT: ".$idAdvisor."<br/>";
                            if ($idAdvisor == $advisor->id) {
                                $existId = true;
                                // echo "Coincide: ".$existId."* <br/>";
                            }
                        }

                        if ($existId == true) {
                            $existId = false;
                        } else {
                            $choicedId = $advisor->id;
                            break;
                            // echo "Elegido: ".$choicedId."...<br/>";
                        }
                    }
                    $return = $choicedId;
                }
            }

        } else {
            // echo "1. Solo un asesor registrado<br/>";
            // SI LA SUCURSAL SOLO TIENE UN ASESOR CON ESA ESPECIALIDAD LO ASIGNA
            $return = $objAdvisorCount[0]->id;
        }
        
        return $return;
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
}
