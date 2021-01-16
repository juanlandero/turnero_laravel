<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\PublicDisplay\SpecialityController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\SpecialityType;
use App\SpecialityTypeUser;
use App\UserOffice;
use App\ShiftStatus;
use App\Incident;
use App\Office;
use App\Shift;
use PDF;

class ReportsController extends Controller
{
    public function index(){
        $officeId = UserOffice::where('user_id', Auth::id())->select('office_id')->first();
        $objAdvisor = UserOffice::join('users', 'user_offices.user_id', 'users.id')
                                ->where([
                                    ['office_id', $officeId->office_id],
                                    ['users.user_type_id', 3]
                                ])
                                ->select(
                                    'users.id',
                                    'users.name',
                                    'users.first_name',
                                    'users.second_name'
                                )
                                ->get();

        return view('dashboard.contents.reports.Index', ['advisors' => $objAdvisor]);
    }

    public function generalReport(){
        $return = null;
        $arrShift = array();
        $arrSpeciality = array();
        $arrSpecialities = array();
        $arrUserOffices = array();
        $objStatus = array(
            array(
                'id'              => 1,
                'shift_status'    => 'En Espera'
            ),
            array(
                'id'              => 2,
                'shift_status'    => 'Atendido'
            ),
            array(
                'id'              => 3,
                'shift_status'    => 'Abandonado'
            ),
            array(
                'id'              => 4,
                'shift_status'    => 'Reasignado'
        ));

       $objOffice = Office::join('user_offices', 'offices.id', 'user_offices.office_id')
                            ->where('user_offices.user_id', Auth::id())
                            ->select(
                                'offices.id',
                                'offices.name',
                                'offices.address',
                                'offices.phone',
                            )
                            ->first();

        $officeId = $objOffice->id;

        // PRIMERA TABLA                            
        foreach ($objStatus as $status) {
            $countShitf = Shift::where([
                                    ['shifts.office_id', $officeId],
                                    ['shifts.is_active', 1],
                                    ['shifts.created_at', 'like', OfficeController::setDate()."%"]
                                ])//Espera
                                ->when($status['id'] == 1, function($query){
                                    return $query->where('shifts.shift_status_id', 1);
                                })//Atendido
                                ->when($status['id'] == 2, function($query){
                                    return $query->where('shifts.shift_status_id', 2);
                                })//Abandonado
                                ->when($status['id'] == 3, function($query){
                                    return $query->where('shifts.shift_status_id', 3);
                                })//Reasignado
                                ->when($status['id'] == 4, function($query){
                                    return $query->where('shifts.is_reassigned', true);
                                })
                                ->get();

            array_push($arrShift, array(
                'type' => $status['shift_status'],
                'count' => $countShitf->count()
            ));
            
        }

        // TABLA POR ESPECIALIDADES
        // BUSCAMOS LOS USARIOS DE SUCURSAL
        $objUserOffices = UserOffice::join('users', 'user_offices.user_id', 'users.id')
                                        ->select(
                                            'user_offices.user_id',
                                            'user_offices.office_id'
                                        )
                                        ->where([
                                            ['user_offices.office_id', $officeId],
                                            ['users.user_type_id', 3]
                                        ])
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

        $statusId = [2, 3];
        foreach ($arrSpecialities as $speciality) {
            $arrStatus = array();
            $turn = 0;
            foreach ($statusId as $index => $id) {
                $turn++;            
                $countStatus = collect(DB::select(DB::raw('SELECT
                                                                COUNT(shifts.shift_status_id) as quantity,
                                                                shift_status.shift_status,
                                                                shift_status.id
                                                            FROM shifts
                                                            JOIN shift_status ON shifts.shift_status_id = shift_status.id
                                                            WHERE shifts.shift_status_id = '.$id.'
                                                                AND shifts.created_at like "'.OfficeController::setDate().'%"
                                                                AND shifts.office_id = '.$officeId.'
                                                                AND shifts.is_active = 1
                                                                AND shifts.speciality_type_id = '.$speciality['id'])));
                array_push($arrStatus, array(
                    'id'        => $countStatus[0]->id,
                    'type'      => $countStatus[0]->shift_status,
                    'quantity'  => $countStatus[0]->quantity,
                ));
                if (sizeof($statusId) == $turn) {
                    $total = $arrStatus[0]['quantity'] + $arrStatus[1]['quantity'];
                    array_push($arrStatus, array(
                        'id'        => 0,
                        'type'      => 'Total',
                        'quantity'  => $total,
                    ));
                }
            }

            array_push($arrSpeciality, array(
                'type' => $speciality['speciality'],
                'shifts' => $arrStatus
                
            ));
        }

        // TABLA POR ASESORES
        $objUserOffices = UserOffice::join('users', 'user_offices.user_id', 'users.id')
                                        ->join('boxes', 'user_offices.box_id', '=', 'boxes.id')
                                        ->where([
                                            ['user_offices.office_id', $officeId],
                                            ['users.user_type_id', 3],
                                            ['users.is_active', 1]
                                        ])
                                        ->select(
                                            'user_offices.office_id',
                                            'user_offices.user_id',
                                            'users.name',
                                            'users.first_name',
                                            'users.second_name',
                                            'users.email',
                                            'boxes.box_name'
                                        )
                                        ->get();

        foreach ($objUserOffices as $index => $userOffice) {
            $shiftsUserOffice = Shift::where([
                                            ['shifts.user_advisor_id', $objUserOffices[$index]->user_id],
                                            ['shifts.is_active', 1],
                                            ['shifts.created_at', 'like', OfficeController::setDate()."%"]

                                        ])
                                        ->count();

            array_push($arrUserOffices, array(
                'user'      => $userOffice->name." ".$userOffice->first_name." ".$userOffice->second_name,
                'mail'      => $userOffice->email,
                'box'       => $userOffice->box_name,
                'quantity'  => $shiftsUserOffice
            ));            
        }

        $pdf = PDF::loadView('dashboard.contents.reports.pdf.GeneralReport', [
                                                                                'office'        => $objOffice,
                                                                                'shifts'        => $arrShift,
                                                                                'specialities'  => $arrSpeciality,
                                                                                'advisors'      => $arrUserOffices
                                                                            ]);
        $pdf->setPaper('A4');
        $return = $pdf->stream();

        return $return;
    }

    public function shiftReport(){
        $objOffice = Office::join('user_offices', 'offices.id', 'user_offices.office_id')
                            ->where('user_offices.user_id', Auth::id())
                            ->select(
                                'offices.id',
                                'offices.name',
                                'offices.address',
                                'offices.phone',
                            )
                            ->first();
        $officeId = $objOffice->id;
        
        $objShifts = Shift::join('shift_types', 'shifts.shift_type_id', 'shift_types.id')
                            ->join('shift_status', 'shifts.shift_status_id', 'shift_status.id')
                            ->join('speciality_types', 'shifts.speciality_type_id', 'speciality_types.id')
                            ->join('users', 'shifts.user_advisor_id', 'users.id')
                            ->where([
                                ['shifts.is_active', 1],
                                ['shifts.office_id', $officeId],
                                ['shifts.created_at', 'like', OfficeController::setDate()."%"]
                            ])
                            ->select(
                                'shifts.id',
                                'shifts.shift_status_id',
                                'shifts.shift',
                                'shift_types.shift_type',
                                'speciality_types.name as speciality',
                                'users.email',
                                'shifts.is_reassigned',
                                'shifts.created_at',
                                'shifts.start_shift',
                                'shifts.end_shift',
                                DB::raw('TIMESTAMPDIFF(SECOND,shifts.start_shift,shifts.end_shift) as minute'),
                                DB::raw('TIMESTAMPDIFF(SECOND,shifts.created_at,shifts.start_shift) as wait')
                            )
                            ->get();

        $pdf = PDF::loadView('dashboard.contents.reports.pdf.ShiftReport', [
                                                                                'office'    => $objOffice,
                                                                                'shifts'    => $objShifts,
                                                                            ]);
        $pdf->setPaper('A4');
        $return = $pdf->stream();

        return $return;
    }

    public function advisorReport(Request $request){
        $userAdvisorId = $request->input('advisor');

        $objOffice = Office::join('user_offices', 'offices.id', 'user_offices.office_id')
                            ->join('users', 'user_offices.user_id', 'users.id')
                            ->where('user_offices.user_id', $userAdvisorId)
                            ->select(
                                'offices.id',
                                'offices.name',
                                'offices.address',
                                'offices.phone',
                                'users.name as advisor',
                                'users.first_name',
                                'users.second_name',
                                'users.email'
                            )
                            ->first();
        $officeId = $objOffice->id;
        
        $objShifts = Shift::join('shift_types', 'shifts.shift_type_id', 'shift_types.id')
                            ->join('shift_status', 'shifts.shift_status_id', 'shift_status.id')
                            ->join('speciality_types', 'shifts.speciality_type_id', 'speciality_types.id')
                            ->join('users', 'shifts.user_advisor_id', 'users.id')
                            ->where([
                                ['shifts.is_active', 1],
                                ['shifts.office_id', $officeId],
                                ['shifts.user_advisor_id', $userAdvisorId],
                                ['shifts.created_at', 'like', OfficeController::setDate()."%"]
                            ])
                            ->select(
                                'shifts.id',
                                'shifts.shift_status_id',
                                'shifts.shift',
                                'shift_types.shift_type',
                                'speciality_types.name as speciality',
                                'shifts.created_at',
                                'shifts.start_shift',
                                'shifts.end_shift',
                                DB::raw('TIMESTAMPDIFF(SECOND,shifts.start_shift,shifts.end_shift) as minute'),
                                DB::raw('TIMESTAMPDIFF(SECOND,shifts.created_at,shifts.start_shift) as wait')
                            )
                            ->get();

        $objReassignedShifts = Incident::join('shifts', 'incidents.shift_id', 'shifts.id')
                                        ->join('users', 'shifts.user_advisor_id', 'users.id')
                                        ->join('shift_types', 'shifts.shift_type_id', 'shift_types.id')
                                        ->join('speciality_types', 'shifts.speciality_type_id', 'speciality_types.id')
                                        ->where([
                                            ['incidents.created_at', 'like', OfficeController::setDate().'%'],
                                            ['incidents.user_reassigned_id', $userAdvisorId],
                                            ['incidents.is_active', 1]
                                        ])
                                        ->select(
                                            'shifts.id',
                                            'shifts.shift',
                                            'users.email',
                                            'shift_types.shift_type',
                                            'speciality_types.name as speciality',
                                            'shifts.created_at',
                                            'shifts.start_shift',
                                            'shifts.end_shift',
                                            DB::raw('TIMESTAMPDIFF(SECOND,shifts.start_shift,shifts.end_shift) as minute')
                                            // DB::raw('TIMESTAMPDIFF(SECOND,shifts.created_at,shifts.start_shift) as wait')
                                        )
                                        ->get();

        $objRaceivedShifts = Shift::join('incidents', 'shifts.id', 'incidents.shift_id')
                                    ->join('shift_types', 'shifts.shift_type_id', 'shift_types.id')
                                    ->join('speciality_types', 'shifts.speciality_type_id', 'speciality_types.id')
                                    ->join('users', 'incidents.user_reassigned_id', 'users.id')
                                    ->where([
                                        ['shifts.is_reassigned', 1],
                                        ['shifts.user_advisor_id', $userAdvisorId]
                                    ])
                                    ->select(
                                        'shifts.id',
                                        'shifts.shift',
                                        'users.email',
                                        'shift_types.shift_type',
                                        'speciality_types.name as speciality',
                                        'shifts.created_at',
                                        'shifts.start_shift',
                                        'shifts.end_shift',
                                        DB::raw('TIMESTAMPDIFF(SECOND,shifts.start_shift,shifts.end_shift) as minute')
                                    )
                                    ->get();

        $pdf = PDF::loadView('dashboard.contents.reports.pdf.AdvisorReport', [
                                                                                'office'        => $objOffice,
                                                                                'shifts'        => $objShifts,
                                                                                'reassigned'    => $objReassignedShifts,
                                                                                'received'      => $objRaceivedShifts
                                                                            ]);
        $pdf->setPaper('A4');
        $return = $pdf->stream();

        return $return;
    }
}
