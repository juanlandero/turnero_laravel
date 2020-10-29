@section('title', 'Reportes')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-line-chart"></i> Reportes</h1>
        <p>Listado de reportes</p>
    </div>

    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">Reportes</a></li>
        <li class="breadcrumb-item active"><a href="#">Listado</a></li>
    </ul>
</div>

@if(Session::has('success_message'))
<div class="row">
    <div class="col-lg-6">
        <div class="bs-component">
            <div class="alert alert-dismissible alert-success">
                <button class="close" type="button" data-dismiss="alert">×</button>
                <strong>{{ Session::get('success_title' )}}</strong> {{ Session::get('success_message' )}}
            </div>
        </div>
    </div>
</div>
@endif

<div class="row">

    <div class="col-md-6">
        <div class="tile">
            <div class="tile-title-w-btn">
                <h3 class="title">Reporte general</h3>
                <p><a class="btn btn-primary icon-btn" href="{{ route('general.report') }}"><i class="fa fa-file"></i>Generar</a></p>
            </div>
            <div class="tile-body">
                Genere un reporte con los todos los datos de su sucursal.
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="tile">
            <div class="tile-title-w-btn">
                <h3 class="title">Reporte de turnos</h3>
                <p><a class="btn btn-primary icon-btn" href="{{ route('shift.report') }}"><i class="fa fa-file"></i>Generar</a></p>
            </div>
            <div class="tile-body">
                Genere un reporte con los turnos generados durante el día.
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <!-- Data table plugin-->
    <script type="text/javascript" src="{{ asset('js/plugins/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/plugins/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript">
        $('#dataTable').DataTable( {
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "zeroRecords": "No existen registros",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "No hay registros disponibles",
                "infoFiltered": "(filtrado de _MAX_ total registros)"
            }
        });
    </script>
@endsection

@include('dashboard.components.Navbar')
@include('dashboard.components.Sidebar')
@include('dashboard.components.Scripts')
@include('dashboard.components.Stylesheets')

@extends('dashboard.components.Main')