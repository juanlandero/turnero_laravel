<?php

namespace App\Http\Controllers;

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
        $office_id = session('NUM_OFFICE');

        $userOffices = UserOffice::join('offices', 'user_offices.office_id', '=', 'offices.id')
                                        ->select('user_id', 'offices.id as office')
                                        ->where('offices.id', $office_id)
                                        ->get();

        foreach ($userOffices as $user) {
            $duplicateSpeciality = false;

            $specialityUser = SpecialityTypeUser::join('speciality_types', 'speciality_type_users.speciality_type_id', '=', 'speciality_types.id')
                                                ->where('speciality_type_users.user_id', $user->user_id)
                                                ->select(
                                                    'speciality_types.id AS speciality_id',
                                                    'speciality_types.name AS speciality_name',
                                                    'speciality_types.class_icon'
                                                )
                                                ->first();

            foreach ($arrSpecialities as $speciality) {
                if ($specialityUser->speciality_id == $speciality['id']) {
                    $duplicateSpeciality = true;
                }
            }

            if (!$duplicateSpeciality) {
                array_push($arrSpecialities, array(
                    'id' => $specialityUser->speciality_id,
                    'speciality' => $specialityUser->speciality_name,
                    'class_btn' => $specialityUser->class_icon
                ));
            }
        }

        return $arrSpecialities;
    }
}
