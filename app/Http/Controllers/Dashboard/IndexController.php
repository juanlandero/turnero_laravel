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
            $objUser = User::join('user_types', 'users.user_type_id', 'user_types.id')
                            ->where('users.id', Auth::id())
                            ->select(
                                'users.name',
                                'users.first_name',
                                'users.second_name',
                                'users.email',
                                'user_types.user_type'
                            )
                            ->first();

            $objSpecialities = SpecialityTypeUser::join('speciality_types', 'speciality_type_users.speciality_type_id', 'speciality_types.id')
                                                ->where('speciality_type_users.user_id', Auth::id())
                                                ->select(
                                                    'speciality_types.name'
                                                )
                                                ->get();
    

            for ($i=0; $i < 2; $i++) { 
                $objShifts = Shift::where([
                                            // ['shifts.office_id', $officeId],
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
                                                    'user'          => $objUser,
                                                    'specialities'  => $objSpecialities,
                                                    'widget'        => $widget
                                                ]);
    }

    public function getDataChart(){
        $data = [];
        $label = [];
        $chart = collect(DB::select(DB::raw('SELECT 
                                                COUNT(shifts.speciality_type_id) AS total,
                                                speciality_types.name
                                            FROM shifts 
                                            JOIN speciality_types ON shifts.speciality_type_id = speciality_types.id
                                            WHERE shifts.user_advisor_id = '.Auth::id().'
                                            GROUP BY shifts.speciality_type_id')));

        foreach ($chart as $index => $value) {
            $data[$index]   = $value->total;
            $label[$index]  = $value->name;
        }


        return [$label, $data];
    }
}
