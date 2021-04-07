<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OfficeController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\SpecialityTypeUser;
use App\Shift;
use App\User;
use Auth;

class IndexController extends Controller
{
    public function index() {
        $objUser = null;
        $objSpecialities = null;
        $widget = [];

        if(!Auth::check()) {
            return Redirect('dashboard/login');
        } else {
            $userType = 0;

            switch (Auth::user()->user_type_id) {
                case 1:
                    $userType = "Administrador";
                    break;
                case 2:
                    $userType = "Supervisor";
                    break;
                case 3:
                    $userType = "Asesor";
                    break;
                
                default:
                    $userType = "No definido";
                    break;
            }

            $objSpecialities = SpecialityTypeUser::join('speciality_types', 'speciality_type_users.speciality_type_id', 'speciality_types.id')
                                                ->where('speciality_type_users.user_id', Auth::id())
                                                ->select(
                                                    'speciality_types.name'
                                                )
                                                ->get();
    

            for ($i=0; $i < 2; $i++) { 
                $objShifts = Shift::where([
                                            ['shifts.is_active', 1],
                                            ['shifts.shift_status_id', $i+2],
                                            ['shifts.user_advisor_id', Auth::id()],
                                            ['shifts.created_at', 'like', OfficeController::setDate()."%"]
                                        ])
                                        ->count();
                $widget[$i] = $objShifts;
            }
            $widget[2] = Shift::where([
                                        ['shifts.is_active', 1],
                                        ['shifts.is_reassigned', 1],
                                        ['shifts.user_advisor_id', Auth::id()],
                                        ['shifts.created_at', 'like', OfficeController::setDate()."%"]
                                    ])
                                    ->count();

            $widget[3] = Shift::where([
                                        ['shifts.is_active', 1],
                                        ['shifts.user_advisor_id', Auth::id()],
                                        ['shifts.created_at', 'like', OfficeController::setDate()."%"]
                                    ])
                                    ->count();
        }

        return view('dashboard.contents.Index', [
                                                    'user'          => Auth::user(),
                                                    'userType'      => $userType,
                                                    'specialities'  => $objSpecialities,
                                                    'widget'        => $widget
                                                ]);
    }

    public function getDataChart(){
        $data = [];
        $label = [];

        $objSpecialities = SpecialityTypeUser::join('speciality_types', 'speciality_type_users.speciality_type_id', 'speciality_types.id')
                                                ->where('speciality_type_users.user_id', Auth::id())
                                                ->select(
                                                    'speciality_types.id',
                                                    'speciality_types.name'
                                                )
                                                ->get();
        
        foreach ($objSpecialities as $index => $speciality) {
            $specialityCount = Shift::where([
                                        ['shifts.user_advisor_id', Auth::id()],
                                        ['shifts.speciality_type_id', $speciality->id],
                                        ['shifts.created_at', 'like', OfficeController::setDate().'%']
                                    ])
                                    ->count();

            $data[$index]   = $specialityCount;
            $label[$index]  = $speciality->name;
        }

        return ['label' => $label, 'data' => $data];
    }
}
