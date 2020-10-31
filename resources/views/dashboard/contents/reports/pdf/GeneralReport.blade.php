<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte General</title>
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

        <!-- NUMEROS GENERALES -->
        <div class="barra">
            <p class="seccion">TURNOS DE HOY</p>
        </div>
        <table style="margin-bottom: 40px;">
            <tr>
                @foreach ($shifts as $shift)
                    <td class="titulo1" width="25%" style="text-align: center">{{ $shift['count'] }}</td>
                @endforeach
            </tr>
            <tr>
                @foreach ($shifts as $shift)
                    <td class="subtitulo1" width="25%" style="text-align: center">{{ $shift['type'] }}</td>
                @endforeach
            </tr>
        </table>

        <!-- TABLA DE ESPECIALIDADES -->
        <div class="barra">
            <p class="seccion">POR ESPECIALIDADES</p>
        </div>
        <table class="tabla" style="margin-bottom: 40px;">
            <tr class="titulo-tabla" >
                <th>Especialidad</th>
                <th>Atendidos</th>
                <th>Abandonado</th>
                <th>Total</th>
            </tr>
            @foreach ($specialities as $speciality)
                <tr>
                    <td>{{ $speciality['type'] }}</td>
                    @foreach ($speciality['shifts'] as $shift)
                        <td width="25%">{{ $shift['quantity'] }}</td>
                    @endforeach
                </tr>
            @endforeach
        </table>

        <div class="barra">
            <p class="seccion">POR ASESOR</p>
        </div>
        <table class="tabla" style="margin-bottom: 40px;">
            <tr class="titulo-tabla" >
                <th>Asesor</th>
                <th>E-mail</th>
                <th>Caja</th>
                <th>Total</th>
            </tr>
            @foreach ($advisors as $advisor)
                <tr>
                    <td>{{ $advisor['user'] }}</td>
                    <td>{{ $advisor['mail'] }}</td>
                    <td>{{ $advisor['box'] }}</td>
                    <td>{{ $advisor['quantity'] }}</td>
                </tr>
            @endforeach
        </table>
	</div>

</body>
</html>