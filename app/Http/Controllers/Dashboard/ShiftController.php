<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\Dashboard;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Events\MenuGeneratorMsg;
use App\Events\AdminPanelMsg;
use App\SpecialityType;
use App\UserOffice;
use App\Incident;
use App\Client;
use App\Shift;

class ShiftController extends Controller
{
    public function generateTicketNumber($speciality){
        $numberTicket = "";
        //OBTENIENDO LA LETRA DE LA ESPECIALIDAD PARA GENERAR EL NÚMERO
        $objSpeciality = SpecialityType::where('id', $speciality)->select('name', 'id')->first();
        $letter = substr($objSpeciality->name, 0, -(strlen($objSpeciality->name)-1));

        $number = (Shift::where([
                                    ['office_id', session('OFFICE')],
                                    ['created_at', 'like', OfficeController::setDate().'%']
                                ])->count())+1;

        switch (strlen($number)) {
            case 1:
                $numberTicket = $letter . "00" . $number;
                break;
            
            case 2:
                $numberTicket = $letter . "0" . $number;
                break;

            case 3:
                $numberTicket = $letter . $number;
                break;
                
            default:
                $numberTicket = $letter . $number . "--Error code--";
                break;
        }

        return $numberTicket;
    }

    public function create(Request $request){

        $specialityId = $request->input('speciality');
        $clientNumber = $request->input('client_number');
        $clientSex = $request->input('sex');
        $channel = $request->input('channel');
        $typeTicket = 1;
        $ticket = [];

        if ($clientNumber != null) {
            $dataClient = Client::where([
                                        ['client_number', $clientNumber],
                                        ['is_active', 1]
                                ])->first();
            
            $clientSex = $dataClient->sex;
            $typeTicket = 2;
        }

        $newTicket = new Shift();

        $newTicket->shift               = ShiftController::generateTicketNumber($specialityId);
        $newTicket->shift_type_id       = $typeTicket;
        $newTicket->speciality_type_id  = $specialityId;
        $newTicket->office_id           = session('OFFICE');
        $newTicket->shift_status_id     = 1;
        $newTicket->user_advisor_id     = AdvisorController::selectAdviser();
        $newTicket->sex_client          = $clientSex;
        $newTicket->number_client       = $clientNumber;
        $newTicket->is_reassigned       = 0;
        $newTicket->is_active           = 1;

        if ($newTicket->save()) {
            event(new MenuGeneratorMsg($channel, $newTicket->id, $newTicket->speciality_type_id, 1));
        } else {
            $ticket = [
                'success'   => false,
                'text'     => 'Error al guardar el ticket'
            ];
        }

        $timeTicket = Shift::select(
                            'shifts.created_at as hora'
                        )
                        ->where('shifts.id', $newTicket->id)
                        ->first();

        $ticket = [
            'success'       => true,
            'shift'         => $newTicket->shift,
            'speciality'    => $newTicket->specialityType['name'],
            'hora'          => $timeTicket->hora
        ];

        return ['ticket' => $ticket];
    }

    public function arraySort($array, $on) {
        foreach ($array as $value) {
            $lowerArray[] = strtolower($value[$on]);
        }

        asort($lowerArray);

        foreach ($lowerArray as $key => $v) {
            $newArray[] = $array[$key];
        }

        return $newArray;
    }

    public function nextShift(Request $request){
        $userSpecialities = $request->input('specialities');
        $panelChannel = $request->input('panel_channel');
        $officeId = $request->input('office');
        $arrShifts = array();
        $return = null;

        $shifts = Shift::join('speciality_types', 'shifts.speciality_type_id', 'speciality_types.id')
                    ->join('shift_types', 'shifts.shift_type_id', 'shift_types.id')
                    ->where([
                        ['shifts.is_active', 1],
                        ['shifts.shift_status_id', 1],
                        ['shifts.office_id', $officeId],
                        ['shifts.created_at', 'like', OfficeController::setDate().'%']
                    ])
                    ->select(
                        'shifts.id',
                        'shifts.shift',
                        'shifts.shift_type_id',
                        'shift_types.shift_type as type',
                        'shifts.speciality_type_id',
                        'speciality_types.name as speciality',
                        'shifts.created_at',
                        'shifts.number_client'
                    )
                    ->orderBy('shifts.id', 'ASC')
                    ->get();

        if (sizeof($shifts) > 0) {
            $i = 0;
            $index = null;
            $shiftFound = false;
            $shiftSelectedId = 0;

            while ($i < sizeof($shifts) && $shiftFound == false) { 
                foreach ($userSpecialities as $speciality) {
                    if ($speciality['speciality_type_id'] == $shifts[$i]->speciality_type_id) {
                        $shiftFound = true;
                        $index = $i;
                        $shiftSelectedId = $shifts[$i]->id;
                    }
                }
                $i++;
            }

            if ($shiftSelectedId != 0) {

                $newStateShift = Shift::find($shiftSelectedId);
                $newStateShift->shift_status_id = 2;
                $newStateShift->user_advisor_id = Auth::id();
                $newStateShift->start_shift = now();

                try {
                    if ($newStateShift->save()) {
                        $client = "Visitante";
                        $number = "0";
                        $sex = "N/A";

                        if ($shifts[$index]->number_client != null) {
                            $clientData = Client::where('client_number', $shifts[$index]->number_client)->first();

                            $client = $clientData->name. " " . $clientData->first_name. " " . $clientData->second_name;
                            $number = $clientData->client_number;
                            $sex = $clientData->sex;
                        }
                        
                        $shiftReturn = [
                            'id'        => $shifts[$index]->id,
                            'shift'     => $shifts[$index]->shift,
                            'type'      => $shifts[$index]->type,
                            'speciality'=> $shifts[$index]->speciality,
                            'generate'  => $shifts[$index]->created_at->toDateTimeString(),
                            'client'    => $client,
                            'number'    => $number,
                            'sex'       => $sex
                        ];

                        $box = UserOffice::join('boxes', 'user_offices.box_id', 'boxes.id')
                                            ->where('user_offices.user_id', Auth::id())
                                            ->first();

                        event(new AdminPanelMsg($panelChannel, $newStateShift->id, $shifts[$index]->speciality_type_id, $box->box_name));

                        $return = [
                            'state' => true,
                            'text'  => 'Turno iniciado - '.substr($newStateShift->start_shift, 11, 19),
                            'type'  => 'info',
                            'icon'  => 'fas fa-info-circle',
                            'shift' => $shiftReturn
                        ];
                    } 
                } catch (\Throwable $th) {
                    $return = [
                        'state' => false,
                        'text' => 'No se pudo iniciar el turno. Recargue la pagina',
                        'type' => 'danger',
                        'icon' => 'far fa-times-circle'
                    ];
                }
            } else {
                $return = [
                    'state' => false,
                    'text' => 'No hay turnos por el momento.',
                    'type' => 'warning',
                    'icon' => 'fas fa-exclamation-triangle'
                ];
            }

        } else {
            $return = [
                'state' => false,
                'text' => 'No hay turnos por el momento.',
                'type' => 'warning',
                'icon' => 'fas fa-exclamation-triangle'
            ];
        }

        return $return;
    }

    public function reassignmentShift(Request $request){
        $shiftId    = $request->input('shift_id');
        $reciveId   = $request->input('recive_id');
        $sendId     = $request->input('send_id');
        $channel    = $request->input('menu_channel');

        // CAMBIO DE USUARIO
        $reassignment = Shift::where('id', $shiftId)->first();
        $reassignment->shift_status_id = 1;
        $reassignment->user_advisor_id = $reciveId;
        $reassignment->start_shift = null;
        $reassignment->is_reassigned = 1;

        // REGISTRO DEL INCIDENTE CON EL USUARIO QUE ESTA HACIENDO LA REASIGNACIÓN
        $objIncident = new Incident();
        $objIncident->shift_id = $shiftId;
        $objIncident->incident_type_id =  1;
        $objIncident->user_reassigned_id = $sendId;        
        $objIncident->is_active = 1;

        try {
            if ($reassignment->save() && $objIncident->save()) {
                // !** Revisar por que cambiaron los parametros
                event(new MenuGeneratorMsg($channel, $objIncident->shift_id, $reassignment->user_advisor_id, 0));
        
                $return = [
                    'state' => true,
                    'text' => 'EL turno <b>'.$reassignment->shift.'</b> ha sido reasignado',
                    'type' => 'success',
                    'icon' => 'fa fa-exchange-alt'
                ];
            }
        } catch (\Throwable $th) {
            $return = [
                'state' => false,
                'text' => 'Error!',
                'type' => 'danger',
                'icon' => 'far fa-times-circle'
            ];
        }
        
        return $return;
    }

    public function changeStatusShift(Request $request){

        $shiftId = $request->input('shiftId');
        $statusId = $request->input('typeStatus');
        $shiftText = '<b>Error.</b> No se pudo realizar la acción';
        $shiftIcon = 'far fa-times-circle';
        $shiftType = 'danger';

        $status = Shift::where('id', $shiftId)->first();
        
        if ($status->count() > 0) {
            if ($status->shift_status_id != 3) {
                $status->shift_status_id = $statusId;
                $status->end_shift = now();

                try {
                    if ($status->save()) {
                        if ($statusId == 2) {
                            $shiftText = "Turno <b>finalizado</b>";
                            $shiftIcon = 'far fa-check-circle';
                            $shiftType = 'success';
                        } elseif ($statusId == 3) {
                            $shiftText = "Turno <b>abandonado</b>";
                            $shiftIcon = "fas fa-walking";
                        }
                    } 
    
                } catch (\Throwable $th) {
                    $shiftText = "No se puede modificar.";
                }
            }            
        }

        return [
            'text' => $shiftText,
            'type' => $shiftType,
            'icon' => $shiftIcon
        ];
    }
}
