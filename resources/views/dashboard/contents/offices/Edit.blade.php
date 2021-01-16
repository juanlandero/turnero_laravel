@section('title', 'Sucursales')
@section('subtitle', 'Modificar sucursal')
@section('icon', 'fas fa-map-marker-alt')
@section('breadcrumb')
    <li class="breadcrumb-item "><a href="{{ route('offices.index') }}">Sucursales</a></li>
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
            {!! Form::open(['route' => 'office-update', 'method' => 'PUT']) !!}
                <input type="hidden" name="hddIdOffice" value="{{$objOffice->id}}" />
                <div class="tile">
                    <div class="tile-body">
                        <div class="form-group">
                            <label class="control-label">Nombre</label>
                            <input class="form-control" name="txtName" type="text" placeholder="Ingresa el nombre de la sucursal" value="{{$objOffice->name}}" required autocomplete="off"/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Dirección</label>
                            <textarea class="form-control" name="txtAddress" rows="4" placeholder="Ingresa la dirección de la sucursal" required autocomplete="off">{!!$objOffice->address!!}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Teléfono</label>
                            <input class="form-control" name="txtPhone" type="text" placeholder="Número teléfonico de la sucursal" value="{{$objOffice->phone}}" autocomplete="off"/>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Clave sucursal</label>
                            <input class="form-control" name="office_key" type="text" placeholder="Clave para accesar al apartado público del turnero" value="{{$objOffice->office_key}}" required autocomplete="off"/>
                        </div>
                        <div class="form-group">
                            <label for="exampleSelect1">Municipio</label>
                            <select class="form-control" name="cmbMunicipality">
                                @foreach ($lstMunicipalities as $item)
                                    @if($objOffice->municipality_id == $item->id)
                                        <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                    @else
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="tile-footer">
                        <button class="btn btn-primary" type="submit">
                            <i class="fa fa-fw fa-lg fa-check-circle"></i>Guardar
                        </button>&nbsp;&nbsp;&nbsp;
                        <a class="btn btn-secondary" href="/dashboard/offices">
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