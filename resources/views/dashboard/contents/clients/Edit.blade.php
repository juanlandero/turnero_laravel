@section('title', 'Clientes')
@section('subtitle', 'Editar de Clientes')
@section('icon', 'fas fa-address-book')
@section('breadcrumb')
    <li class="breadcrumb-item "><a href="{{ route('clients.index') }}">Clientes</a></li>
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
            {!! Form::open(['route' => 'client-update', 'method' => 'PUT']) !!}
                <div class="tile">
                    <div class="tile-body">
                        <input type="hidden" name="idClient" value="{{ $objClient->id }}">
                        <div class="form-group">
                            <label class="control-label">Nombre</label>
                            <input class="form-control" name="txtName" type="text" value="{{ $objClient->name }}" placeholder="Ingresa el nombre del cliente" required autocomplete="off"/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Apellido paterno</label>
                            <input class="form-control" name="txtFirstName" type="text" value="{{ $objClient->first_name }}" placeholder="Ingresa el primer apellido del cliente" required autocomplete="off"/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Apellido materno</label>
                            <input class="form-control" name="txtSecondName" type="text" value="{{ $objClient->second_name }}" placeholder="Ingresa el segundo apellido del cliente" required autocomplete="off"/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Número de cliente</label>
                            <input class="form-control" name="txtNumberClient" type="text" value="{{ $objClient->client_number }}" placeholder="Ingresa el número del cliente" required autocomplete="off"/>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Sexo</label>

                            @if ($objClient->sex == "M")
                                <div class="form-check">
                                    <label class="form-check-label">
                                    <input class="form-check-input" id="optionsRadios2" type="radio" name="raSex" value="F">Femenino
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                    <input class="form-check-input" id="optionsRadios2" type="radio" name="raSex" value="M" checked>Masculino
                                    </label>
                                </div>
                            @else
                                <div class="form-check">
                                    <label class="form-check-label">
                                    <input class="form-check-input" id="optionsRadios2" type="radio" name="raSex" value="F" checked>Femenino
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                    <input class="form-check-input" id="optionsRadios2" type="radio" name="raSex" value="M">Masculino
                                    </label>
                                </div>
                            @endif

                            
                        </div>
                    </div>
                    <div class="tile-footer">
                        <button class="btn btn-primary" type="submit">
                            <i class="fa fa-fw fa-lg fa-check-circle"></i>Guardar
                        </button>&nbsp;&nbsp;&nbsp;
                        <a class="btn btn-secondary" href="{{ route('clients.index') }}">
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