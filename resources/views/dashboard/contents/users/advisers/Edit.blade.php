@section('title', 'Asesores')
@section('subtitle', 'Modificar Asesor')
@section('icon', 'fa fa-user')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('user-advisers.index') }}">Asesores</a></li>
    <li class="breadcrumb-item active"><a href="#">Modificar asesor</a></li>
@endsection

@section('content')
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
            {!! Form::open(['route' => 'user-adviser-update', 'method' => 'PUT']) !!}
                <input type="hidden" name="hddIdUser" value="{{$objUser->id}}" />
                <div class="tile">
                    <div class="tile-body">
                        <div class="form-group">
                            <label class="control-label">Nombre</label>
                            <input class="form-control" name="txtName" type="text" placeholder="Ingresa un nombre" value="{{$objUser->name}}" required autocomplete="off"/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Apellido Paterno</label>
                            <input class="form-control" name="txtFirstName" type="text" placeholder="Ingresa el apellido paterno" value="{{$objUser->first_name}}" required autocomplete="off"/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Apellido Materno</label>
                            <input class="form-control" name="txtSecondName" type="text" placeholder="Ingresa el apellido materno" value="{{$objUser->second_name}}" required autocomplete="off"/>
                        </div>
                        <div class="form-group">
                            <label for="exampleSelect1">Sucursal</label>
                            <select class="form-control" name="cmbOffice" required>
                                @foreach ($lstOffices as $item)
                                    @if($objUser->userOffice->office_id == $item->id)
                                        <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                    @else
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleSelect1">Caja</label>
                            <select class="form-control" name="cmbBox" required>
                                @foreach ($lstBoxes as $item)
                                    @if($objUser->userOffice->box_id == $item->id)
                                        <option value="{{ $item->id }}" selected>{{ $item->box_name }}</option>
                                    @else
                                        <option value="{{ $item->id }}">{{ $item->box_name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Email</label>
                            <input class="form-control" name="email" type="text" placeholder="Ingresa un correo para el usuario" value="{{$objUser->email}}" required autocomplete="off"/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Nueva Contraseña</label>
                            <input class="form-control" name="txtPassword" type="password" placeholder="Ingresa una contraseña para el usuario" />
                        </div>
                    </div>
                    <div class="tile-footer">
                        <button class="btn btn-primary" type="submit">
                            <i class="fa fa-fw fa-lg fa-check-circle"></i>Guardar
                        </button>&nbsp;&nbsp;&nbsp;
                        <a class="btn btn-secondary" href="{{ route('user-advisers.index') }}">
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