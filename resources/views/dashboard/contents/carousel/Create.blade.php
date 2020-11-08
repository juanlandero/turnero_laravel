@section('title', 'Anuncios')
@section('subtitle', 'Nuevo Anuncio')
@section('icon', 'fas fa-photo-video')
@section('breadcrumb')
    <li class="breadcrumb-item "><a href="{{ route('ads.index') }}">Anuncios</a></li>
    <li class="breadcrumb-item active"><a href="#">Crear</a></li>
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
                {{-- @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach --}}
            </ul>
          </div>
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-sm-12 col-md-8 col-lg-6">
        <div class="tile">

            <form  method="POST" enctype="multipart/form-data" action="{{ route('ad.store') }}">
                @csrf
                <div class="form-group">
                    <label class="control-label">Nombre</label>
                    <input class="form-control" name="txtName" type="text" placeholder="Ingresa el nombre del archivo" required autocomplete="off"/>
                </div>

                <div class="form-group">
                    <label class="control-label">Posición</label>
                    <input class="form-control" name="intOrder" type="number" placeholder="Ingresa la posición que ocupará este elemento" required />
                </div>

                <div class="form-group">
                    <label class="control-label">Diración</label>
                    <input class="form-control" name="intDuration" type="number" placeholder="Segundos que será visible este elemento" required />
                </div>

                <label class="control-label">Imagen</label>
                <div class="custom-file">
                    <input type="file" name="imgAd" class="custom-file-input" id="customFileLang" lang="es">
                    <label class="custom-file-label" for="customFileLang">Seleccionar Archivo</label>
                  </div>

                <div class="tile-footer">
                    <button class="btn btn-primary" type="submit">
                        <i class="fa fa-fw fa-lg fa-check-circle"></i>Guardar
                    </button>&nbsp;&nbsp;&nbsp;
                    <a class="btn btn-secondary" href="{{ route('ads.index') }}">
                        <i class="fa fa-fw fa-lg fa-times-circle"></i>Cancelar
                    </a>
                </div>
            </form>            
        </div>

    </div>
</div>
@endsection

@include('dashboard.components.Navbar')
@include('dashboard.components.Sidebar')
@include('dashboard.components.Scripts')
@include('dashboard.components.Stylesheets')

@extends('dashboard.components.Main')