<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SpecialityType;
use App\Client;
use App\Shift;
use App\Events\MenuGeneratorMsg;
use App\Events\AdminPanelMsg;


class ShiftController extends Controller
{
    public function generateTicketNumber($speciality){
        $numberTicket = "";
        //OBTENIENDO LA LETRA DE LA ESPECIALIDAD PARA GENERAR EL NÚMERO
        $objSpeciality = SpecialityType::where('id', $speciality)->select('name', 'id')->first();
        $letter = substr($objSpeciality->name, 0, -(strlen($objSpeciality->name)-1));

        $number = (Shift::where([
                                    ['office_id', session()->get('NUM_OFFICE')],
                                    ['created_at', 'like', session()->get('DATE').'%']
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
        $sex = $request->input('sex');
        $channel = $request->input('channel');
        $typeTicket = 1;

        if ($clientNumber != null) {
            $dataClient = Client::where([
                                        ['client_number', $clientNumber],
                                        ['is_active', 1]
                                ])->first();
            
            $sex = $dataClient->sex;
            $typeTicket = 2;
        }

        $newTicket = new Shift();

        $newTicket->shift               = ShiftController::generateTicketNumber($specialityId);
        $newTicket->shift_type_id       = $typeTicket;
        $newTicket->speciality_type_id  = $specialityId;
        $newTicket->office_id           = session()->get('NUM_OFFICE');
        $newTicket->sex_client          = $sex;
        $newTicket->shift_status_id     = 1;
        $newTicket->user_advisor_id     = AdvisorController::selectAdvisor($specialityId);
        $newTicket->has_incident        = 0;
        $newTicket->is_active           = 1;
        $newTicket->save();

        $idTicket = $newTicket->id;
        $idUser = $newTicket->user_advisor_id;

        event(new MenuGeneratorMsg($channel, $idTicket, $idUser));

        return ['id' => $newTicket->id];
    }

    public function nextShift(Request $request){

        $shiftId = $request->input('shiftId');
        $panelChannel = $request->input('panel_channel');

        event(new AdminPanelMsg($panelChannel, $shiftId));

        return true;
    }

    public function changeStatusShift(Request $request){

        $shiftId = $request->input('shiftId');
        $statusId = $request->input('typeStatus');

        $status = Shift::where('id', $shiftId)->first();
        
        if ($status->count() > 0) {
            
            $status->shift_status_id = $statusId;
            $status->save();
        }

        return $status;
    }
}
