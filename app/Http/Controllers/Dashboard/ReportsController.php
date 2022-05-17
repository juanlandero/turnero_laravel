<?php

namespace App\Http\Controllers\Dashboard;

use PDF;
use App\Shift;
use App\Office;
use App\Incident;
use App\UserOffice;
use App\ShiftStatus;
use App\SpecialityType;
use App\SpecialityTypeUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\GeneralReportExport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\PublicDisplay\SpecialityController;

class ReportsController extends Controller
{
    public function index()
    {
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

        return view('dashboard.contents.reports.Index', ['advisers' => $objAdvisor]);
    }

    public function generalReport(Request $request)
    {
        $initialDate = null;
        $finalDate = null;
        $isExcelFile = false;
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
                'shift_status'    => 'Total'
            )
        );

        if (isset($request->dateStart))
            $initialDate = $this->dateFormat($request->dateStart);

        if (isset($request->dateEnd))
            $finalDate = $this->dateFormat($request->dateEnd);

        if (!is_null($initialDate) && !is_null($finalDate))
            $fecha = "{$request->dateStart} al {$request->dateEnd}";
        else
            $fecha = $this->fechaCastellano(date('Y-m-d'));

        if (isset($request->excel))
            $isExcelFile = true;

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

        // ***** PRIMERA TABLA *****
        foreach ($objStatus as $status) {
            $shiftsQry = Shift::where([
                ['shifts.office_id', $officeId],
                ['shifts.is_active', 1],
            ]) //Espera
                ->when($status['id'] == 1, function ($query) {
                    return $query->where('shifts.shift_status_id', 1);
                }) //Atendido
                ->when($status['id'] == 2, function ($query) {
                    return $query->where('shifts.shift_status_id', 2);
                }) //Abandonado
                ->when($status['id'] == 3, function ($query) {
                    return $query->where('shifts.shift_status_id', 3);
                }) //Reasignado
                ->when($status['id'] == 4, function ($query) {
                    return $query->where('shifts.is_active', true);
                });

            if (!is_null($initialDate) && !is_null($finalDate)) {
                $shiftsQry->where('shifts.created_at', '>=', $initialDate);
                $shiftsQry->where('shifts.created_at', '<=', $finalDate);
            } else {
                $shiftsQry->where('shifts.created_at', 'like', OfficeController::setDate() . "%");
            }

            $shiftsColl = $shiftsQry->get();

            array_push($arrShift, array(
                'type' => $status['shift_status'],
                'count' => $shiftsColl->count()
            ));
        }

        // ***** TABLA POR ESPECIALIDADES *****
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

        $statusId = [1, 2, 3];
        foreach ($arrSpecialities as $speciality) {
            $arrStatus = array();
            foreach ($statusId as $index => $id) {
                $shiftsQry = Shift::where([
                    'shifts.shift_status_id' => $id,
                    'shifts.office_id' => $officeId,
                    'shifts.is_active' => 1,
                    'shifts.speciality_type_id' => $speciality['id']
                ]);

                if (!is_null($initialDate) && !is_null($finalDate)) {
                    $shiftsQry->where('shifts.created_at', '>=', $initialDate);
                    $shiftsQry->where('shifts.created_at', '<=', $finalDate);
                } else {
                    $shiftsQry->where('shifts.created_at', 'like', OfficeController::setDate() . "%");
                }

                $countShifts = $shiftsQry->count();
                $typeStatus = ShiftStatus::find($id);

                array_push($arrStatus, array(
                    'id'        => $typeStatus->id,
                    'type'      => $typeStatus->shift_status,
                    'quantity'  => (($countShifts > 0) ? $countShifts : 0)
                ));
            }

            $total = $arrStatus[0]['quantity'] + $arrStatus[1]['quantity'] + $arrStatus[2]['quantity'];
            array_push($arrStatus, array(
                'id'        => 0,
                'type'      => 'Total',
                'quantity'  => $total,
            ));

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
            $shiftsQry = Shift::where([
                'shifts.user_advisor_id' => $objUserOffices[$index]->user_id,
                'shifts.is_active' => 1,
            ]);

            if (!is_null($initialDate) && !is_null($finalDate)) {
                $shiftsQry->where('shifts.created_at', '>=', $initialDate);
                $shiftsQry->where('shifts.created_at', '<=', $finalDate);
            } else {
                $shiftsQry->where('shifts.created_at', 'like', OfficeController::setDate() . "%");
            }

            $shiftsUserOffice = $shiftsQry->count();

            array_push($arrUserOffices, array(
                'user'      => $userOffice->name . " " . $userOffice->first_name . " " . $userOffice->second_name,
                'mail'      => $userOffice->email,
                'box'       => $userOffice->box_name,
                'quantity'  => $shiftsUserOffice
            ));
        }

        if (!$isExcelFile) {
            $pdf = PDF::loadView('dashboard.contents.reports.pdf.GeneralReport', [
                'fechaReporte'  => $fecha,
                'office'        => $objOffice,
                'shifts'        => $arrShift,
                'specialities'  => $arrSpeciality,
                'advisers'      => $arrUserOffices
            ]);
            $pdf->setPaper('A4');
            return $pdf->stream();
        } else {
            $reportExcel = new GeneralReportExport();
            $reportExcel->setParameters(
                $fecha,
                $objOffice,
                $arrShift,
                $arrSpeciality,
                $arrUserOffices
            );
            return $reportExcel->download('reporte_general.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        }
    }

    public function shiftReport()
    {
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
                ['shifts.created_at', 'like', OfficeController::setDate() . "%"]
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

    public function advisorReport(Request $request)
    {
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
                ['shifts.created_at', 'like', OfficeController::setDate() . "%"]
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
                ['incidents.created_at', 'like', OfficeController::setDate() . '%'],
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

    private function dateFormat($paramDate, $format = "Y-m-d")
    {
        $newFormat = "";
        switch ($format) {
            case "Y-m-d":
                $arrDate = explode("/", $paramDate);
                $newFormat = "{$arrDate[2]}-{$arrDate[1]}-{$arrDate[0]}";
                break;
        }

        return $newFormat;
    }

    private function fechaCastellano($fecha)
    {
        $fecha = substr($fecha, 0, 10);
        $numeroDia = date('d', strtotime($fecha));
        $dia = date('l', strtotime($fecha));
        $mes = date('F', strtotime($fecha));
        $anio = date('Y', strtotime($fecha));
        $dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
        $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
        $nombredia = str_replace($dias_EN, $dias_ES, $dia);
        $meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
        return $nombredia . " " . $numeroDia . " de " . $nombreMes . " de " . $anio;
    }
}
