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
                <p><a class="btn btn-primary icon-btn" href="{{ route('general.report') }}"><i class="fa fa-plus"></i>Generar</a></p>
            </div>
            <div class="tile-body">
                Genere un reporte con los todos los datos de su sucursal.
            </div>
        </div>
    </div>

    {{-- <div class="col-md-12">
        <div class="tile">
            <div class="tile-title">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <a href="/dashboard/specialties/create" class="btn btn-primary" role="button">Nueva especialidad</a>
                    </div>
                </div>
            </div>
            <div class="tile-body">

                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="dataTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lstSpecialties as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                            <button class="btn btn-primary" type="button"><i class="fa fa-cogs fa-lg"></i></button>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="/dashboard/specialties/edit/{{ $item->id }}">Editar</a>
                                                    <a class="dropdown-item" href="#">Eliminar</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> --}}
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