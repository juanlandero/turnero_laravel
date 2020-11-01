@section('title', 'Usuarios Administradores')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-globe"></i> Usuario</h1>
        <p>Nuevo administrador</p>
    </div>

    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fas fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">Usuarios</a></li>
        <li class="breadcrumb-item active"><a href="#">Crear administrador</a></li>
    </ul>
</div>

@if(Session::has('error_message'))
<div class="row">
    <div class="col-lg-6">
        <div class="bs-component">
          <div class="alert alert-dismissible alert-danger">
            <button class="close" type="button" data-dismiss="alert">×</button>
            <strong>{{ Session::get('error_title' )}}</strong> {{ Session::get('error_message' )}}
          </div>
        </div>
    </div>
</div>
@endif

@if($errors->any())
<div class="row">
    <div class="col-lg-6">
        <div class="bs-component">
          <div class="alert alert-dismissible alert-danger">
            <button class="close" type="button" data-dismiss="alert">×</button>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
          </div>
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-sm-12 col-md-8 col-lg-6">
        {!! Form::open(['route' => 'user-admin-store']) !!}
            <div class="tile">
                <div class="tile-body">
                    <div class="form-group">
                        <label class="control-label">Nombre</label>
                        <input class="form-control" name="txtName" type="text" placeholder="Ingresa un nombre" required />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Apellido Paterno</label>
                        <input class="form-control" name="txtFirstName" type="text" placeholder="Ingresa el apellido paterno" required />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Apellido Materno</label>
                        <input class="form-control" name="txtSecondName" type="text" placeholder="Ingresa el apellido materno" required />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Email</label>
                        <input class="form-control" name="txtEmail" type="text" placeholder="Ingresa un correo para el usuario" required />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Contraseña</label>
                        <input class="form-control" name="txtPassword" type="password" placeholder="Ingresa una contraseña para el usuario" required />
                    </div>
                </div>
                <div class="tile-footer">
                    <button class="btn btn-primary" type="submit">
                        <i class="fa fa-fw fa-lg fa-check-circle"></i>Guardar
                    </button>&nbsp;&nbsp;&nbsp;
                    <a class="btn btn-secondary" href="/dashboard/users-admins">
                        <i class="fa fa-fw fa-lg fa-times-circle"></i>Cancelar
                    </a>
                </div>
            </div>
        {!! Form::close() !!}
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