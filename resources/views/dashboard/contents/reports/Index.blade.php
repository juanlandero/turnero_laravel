@section('title', 'Reportes')
@section('subtitle', 'Listado de reportes')
@section('icon', 'fa fa-chart-line')

@section('stylesheets')
    <link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
@endsection

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
            </div>
            <div class="tile-body">
                Genere un reporte con los todos los datos de su sucursal.

                <form id="general-form" action="{{ route('general.report') }}" formtarget="_blank" target="_blank" method="POST" class="row mt-3">
                    @csrf
                    <div class="form-group col-md-6">
                        <div class="input-daterange input-group" id="datepicker">
                            <div class="input-group-prepend"><span class="input-group-text">Del</span></div>
                            <input type="text" class="input-sm form-control" name="dateStart" placeholder="Fecha inicial" />
                            <div class="input-group-append"><span class="input-group-text">al</span></div>
                            <input type="text" class="input-sm form-control" name="dateEnd" placeholder="Fecha final" />
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" name="excel">¿Exportar en Excel?
                            </label>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <button class="btn btn-primary icon-btn float-right" type="submit"><i class="fa fa-file"></i>Generar</button>
                    </div>
                </form>
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
                            @foreach ($advisers as $adviser)
                                <option value="{{ $adviser->id }}">{{ $adviser->name." ".$adviser->first_name." ".$adviser->second_name }}</option>
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
<script type="text/javascript" src="{{ asset('js/plugins/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript">
    $('#general-form .input-daterange').datepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        todayHighlight: true
    });

</script>
@endsection

@include('dashboard.components.Navbar')
@include('dashboard.components.Sidebar')
@include('dashboard.components.Scripts')
@include('dashboard.components.Stylesheets')

@extends('dashboard.components.Main')