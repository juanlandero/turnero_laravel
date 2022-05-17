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
    <div class="container">
        <!-- LOGO Y TITULO -->
       <table>
            <tr>
               <td width="30%" style="text-align: left">
                   <img src="img/madero-logo.jpeg" width="200px"/>
               </td>
                <td width="70%" class="titulo" style="text-align: right">REPORTE DE GENERAL</td>
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
                <th class="info-titulo1">Fecha</th>
                <td class="info-titulo">{{ $fechaReporte }}</td>
            </tr>
        </table>

        <!-- NUMEROS GENERALES -->
        <div class="barra">
            <p class="seccion">TOTAL DE TURNOS</p>
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
                <th>En espera</th>
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
            @foreach ($advisers as $adviser)
                <tr>
                    <td>{{ $adviser['user'] }}</td>
                    <td>{{ $adviser['mail'] }}</td>
                    <td>{{ $adviser['box'] }}</td>
                    <td>{{ $adviser['quantity'] }}</td>
                </tr>
            @endforeach
        </table>
	</div>

</body>
</html>