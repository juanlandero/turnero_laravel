@section('title', 'Especialidades')
@section('subtitle', 'Modificar Especialidad')
@section('icon', 'fa fa-rocket')
@section('breadcrumb')
    <li class="breadcrumb-item "><a href="{{ route('specialties.index') }}">Especialidades</a></li>
    <li class="breadcrumb-item active"><a href="#">Modificar</a></li>
@endsection

@section('content')
    @if(Session::has('error_message'))
    <div class="row">
        <div class="col-lg-4">
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
        <div class="col-lg-4">
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
            {!! Form::open(['route' => 'specialty-update', 'method' => 'PUT']) !!}
                <input type="hidden" name="hddIdSpecialty" value="{{$objSpecialty->id}}" />
                <div class="tile">
                    <div class="tile-body">
                        <div class="form-group">
                            <label class="control-label">Nombre</label>
                            <input class="form-control" name="name" type="text" placeholder="Ingresa el nombre de la especialidad" value="{{$objSpecialty->name}}" required autocomplete="off"/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Descripción</label>
                            <textarea class="form-control" name="txtDescription" rows="2" placeholder="Ingresa una descripción de la especialidad" autocomplete="off">{!!$objSpecialty->description!!}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Icono</label>
                            <input class="form-control" name="txtIcon" type="text" placeholder="fa fa-ejemplo" value="{{$objSpecialty->class_icon}}" required autocomplete="off"/>
                            <small class="form-text text-muted">Para consultar el listado de iconos disponibles entra a <a href="https://fontawesome.com/icons">Font Awesome</a></small>
                        </div>
                        </div>
                    </div>
                    <div class="tile-footer">
                        <button class="btn btn-primary" type="submit">
                            <i class="fa fa-fw fa-lg fa-check-circle"></i>Guardar
                        </button>&nbsp;&nbsp;&nbsp;
                        <a class="btn btn-secondary" href="{{ route('specialties.index') }}">
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