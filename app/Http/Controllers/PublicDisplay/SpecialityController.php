<?php

namespace App\Http\Controllers\PublicDisplay;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\SpecialityTypeUser;
use App\SpecialityType;
use App\UserOffice;
use App\Office;
use App\User;



class SpecialityController extends Controller
{
    public function getSpeciality(){
        $arrSpecialities = array();
        $officeId = session()->get('NUM_OFFICE');

        //BUSCAMOS EL CANAL DE ESTA PÁGINA
        $objChannel = Office::select('menu_channel')
                ->where([
                    ['id', $officeId],
                    ['is_active', 1],
                ])
                ->first();

        // BUSCAMOS LOS USARIOS DE SUCURSAL
        $objUserOffices = UserOffice::select('user_id', 'office_id')
                                        ->where('office_id', $officeId)
                                        ->get();

        // BUSCAMOS LAS ESPECIALIDADES DE CADA USUARIO 
        foreach ($objUserOffices as $user) {
            $duplicateSpeciality = false;

            // UN USUARIO PUEDE TENER MAS DE UNA ESPECIALIDAD
            $objSpecialityUser = SpecialityTypeUser::join('speciality_types', 'speciality_type_users.speciality_type_id', '=', 'speciality_types.id')
                                                ->where('speciality_type_users.user_id', $user->user_id)
                                                ->select(
                                                    'speciality_types.id AS speciality_id',
                                                    'speciality_types.name AS speciality_name',
                                                    'speciality_types.class_icon'
                                                )
                                                ->get();

            // SI EL SUSUARIO TIENE MAS DE UNA ESPECIALIDAD SE DEBE BUSCAR QUE NO SE DUPLIQUEN EN LA 
            // REPRESENTACION DE LA PANTALLA
            foreach ($objSpecialityUser as $specialityUser) {
                foreach ($arrSpecialities as $speciality) {
                    if ($specialityUser->speciality_id == $speciality['id']) {
                        $duplicateSpeciality = true;
                    }
                }

                // SOLO SE INSERTA EN EL ARRAY SU NO ESTA DUPLICADO EL VALOR
                if (!$duplicateSpeciality) {
                    array_push($arrSpecialities, array(
                        'id' => $specialityUser->speciality_id,
                        'speciality' => $specialityUser->speciality_name,
                        'class_btn' => $specialityUser->class_icon
                    ));
                } else {
                    $duplicateSpeciality = false;
                }
            }            
        }

        return [
            'specialities'  => $arrSpecialities,
            'channel'       => $objChannel->menu_channel
        ];
    }
}
