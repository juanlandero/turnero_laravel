<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte Detallado de Ventas</title>
    <link rel="stylesheet" type="text/css" href="css/pdf.css" media="screen">
    <style>
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    @php
    //sdd($datos);
    function fechaCastellano ($fecha) {
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
        return $nombredia." ".$numeroDia." de ".$nombreMes." de ".$anio;
    }              
    @endphp
	<div class="container">
        <!-- LOGO Y TITULO -->
		<table>
            <tr>
				<td width="30%" style="text-align: left">
					<img src="img/madero-logo.jpeg" width="200px"/>
				</td>
				
                <td width="70%" class="titulo" style="text-align: right">REPORTE DE SUCURSAL</td>
            </tr>
        </table>
        
        <!-- DATOS DE LA SUCURSAL -->
        <table style="margin: 40px">
            <tr>
                <th class="info-titulo1">Sucursal</th>
                <td class="info-titulo">{{ $office->name }}</td>
            </tr>
            <tr>
                <th class="info-titulo1">Dirección</th>
                <td class="info-titulo">{{ $office->address }}</td>
            </tr>
            <tr>
                <th class="info-titulo1">Fecha y hora</th>
                <td class="info-titulo">{{fechaCastellano(date('Y-m-d'))}}</td>
            </tr>
        </table>

        <!-- TABLA DE ESPERA -->
        <div class="barra">
            <p class="seccion">TURNOS EN ESPERA</p>
        </div>
        <table class="tabla" style="margin-bottom: 40px;">
            <tr class="titulo-tabla" >
                <th>Turno</th>
                <th>Tipo</th>
                <th>Especialidad</th>
                <th>Usuario</th>
                <th>Generado</th>
            </tr>
            @foreach ($shifts as $shift)
                @if ($shift['shift_status_id'] == 1)
                    <tr>
                        <td>{{ $shift['shift'] }}</td>
                        <td>{{ $shift['shift_type'] }}</td>
                        <td>{{ $shift['speciality'] }}</td>
                        <td>{{ $shift['email'] }}</td>
                        <td>{{ substr($shift['created_at'], 11) }}</td>
                    </tr>
                @endif
            @endforeach
        </table>

        <!-- TABLA DE ATENDIDOS -->
        <div class="barra">
            <p class="seccion">TURNOS ATENDIDOS</p>
        </div>
        <table class="tabla" style="margin-bottom: 40px;">
            <tr class="titulo-tabla" >
                <th>Turno</th>
                <th>Tipo</th>
                <th>Especialidad</th>
                <th>Generado</th>
                <th>Inicio</th>
                <th>Termino</th>
                <th>Tiempo</th>
            </tr>
            @foreach ($shifts as $shift)
                @if ($shift['shift_status_id'] == 3)
                    <tr>
                        <td>{{ $shift['shift'] }}</td>
                        <td>{{ $shift['shift_type'] }}</td>
                        <td>{{ $shift['speciality'] }}</td>
                        <td>{{ substr($shift['created_at'], 11) }}</td>
                        <td>{{ $shift['start_shift'] }}</td>
                        <td>{{ $shift['end_shift'] }}</td>
                        @php
                            $minute = round($shift['minute']/60);
                            $seconds = $shift['minute']%60;

                            if ($shift['start_shift'] != null && $shift['end_shift'] != null) {
                                
                                if (strlen($minute) == 1) {
                                    $minute = "0".$minute;
                                }
                                if (strlen($seconds) == 1) {
                                    $seconds = "0".$seconds;
                                }
                                echo '<td>'.$minute.":".$seconds.'</td>';
                            }
                        @endphp
                    </tr>
                @endif
            @endforeach
        </table>

         <!-- TABLA DE ABANDONADOS -->
         <div class="barra">
            <p class="seccion">TURNOS REASIGNADOS</p>
        </div>
        <table class="tabla" style="margin-bottom: 40px;">
            <tr class="titulo-tabla" >
                <th>Turno</th>
                <th>Tipo</th>
                <th>Especialidad</th>
                <th>Generado</th>
                <th>Inicio</th>
            </tr>
            @foreach ($shifts as $shift)
                @if ($shift['shift_status_id'] == 2 || $shift['has_incident'] == 1) 
                    <tr>
                        <td>{{ $shift['shift'] }}</td>
                        <td>{{ $shift['shift_type'] }}</td>
                        <td>{{ $shift['speciality'] }}</td>
                        <td>{{ substr($shift['created_at'], 11) }}</td>
                        <td>{{ $shift['start_shift'] }}</td>
                    @endif
                </tr>
            @endforeach
        </table>

         <!-- TABLA DE REASIGNADOS -->
         <div class="barra">
            <p class="seccion">TURNOS ABANDONADOS</p>
        </div>
        <table class="tabla" style="margin-bottom: 40px;">
            <tr class="titulo-tabla" >
                <th>Turno</th>
                <th>Tipo</th>
                <th>Especialidad</th>
                <th>Generado</th>
                <th>Atención</th>
                <th>Esperó</th>
            </tr>
            @foreach ($shifts as $shift)
                @if ($shift['shift_status_id'] == 4)
                    <tr>
                        <td>{{ $shift['shift'] }}</td>
                        <td>{{ $shift['shift_type'] }}</td>
                        <td>{{ $shift['speciality'] }}</td>
                        <td>{{ substr($shift['created_at'], 11) }}</td>
                        <td>{{ $shift['start_shift'] }}</td>
                        @php
                            $minute = round($shift['wait']/60);
                            $seconds = $shift['wait']%60;

                            if ($shift['created_at'] != null && $shift['start_shift'] != null) {
                                
                                if (strlen($minute) == 1) {
                                    $minute = "0".$minute;
                                }
                                if (strlen($seconds) == 1) {
                                    $seconds = "0".$seconds;
                                }
                                echo '<td>'.$minute.":".$seconds.'</td>';
                            }
                        @endphp
                    </tr>
                @endif
            @endforeach
        </table>
	</div>

</body>
</html>