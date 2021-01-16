@section('title', 'Reportes')
@section('subtitle', 'Listado de reportes')
@section('icon', 'fa fa-chart-line')
@section('breadcrumb')
    <li class="breadcrumb-item active"><a href="#">Reportes</a></li>
@endsection

@section('content')

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

    <div class="col-md-6">
        <div class="tile">
            <form method="POST" action="{{ route('advisor.report') }}">
                @csrf
                <div class="tile-title-w-btn">
                    <h3 class="title">Reporte de asesor</h3>
                    <p><button class="btn btn-primary icon-btn" type="submit"><i class="fa fa-file"></i>Generar</button></p>
                </div>
                <div class="tile-body">
                    Genere un reporte con los turnos atendidos por un asesor.
                    <div class="form-group">
                        <label for="exampleSelect1">Selecciones un asesor</label>
                        <select class="form-control" id="exampleSelect1" name="advisor">
                            @foreach ($advisors as $advisor)
                                <option value="{{ $advisor->id }}">{{ $advisor->name." ".$advisor->first_name." ".$advisor->second_name }}</option>    
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
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