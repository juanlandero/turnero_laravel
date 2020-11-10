@section('title', 'Asesores')
@section('subtitle', 'Lista de Asesores')
@section('icon', 'fa fa-user')
@section('breadcrumb')
    <li class="breadcrumb-item active"><a href="#">Asesores</a></li>
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
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-title">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <a href="/dashboard/users-advisers/create" class="btn btn-primary" role="button">Nuevo usuario</a>
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
                                <th>Apellidos</th>
                                <th>Email</th>
                                <th>Fecha Creación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lstUsers as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->first_name }} {{ $item->second_name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                            <button class="btn btn-primary" type="button"><i class="fa fa-cogs fa-lg"></i></button>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="/dashboard/users-advisers/speciality/{{ $item->id }}">Especialidad</a>
                                                    <a class="dropdown-item" href="/dashboard/users-advisers/edit/{{ $item->id }}">Editar</a>
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
    </div>
</div>
@endsection

@section('scripts')

@endsection

@include('dashboard.components.Navbar')
@include('dashboard.components.Sidebar')
@include('dashboard.components.Scripts')
@include('dashboard.components.Stylesheets')

@extends('dashboard.components.Main')