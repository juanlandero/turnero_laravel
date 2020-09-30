@section('title', 'Inicio')

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-dashboard"></i> Inicio</h1>
        <p>Panel inicial del dashboard</p>
    </div>

    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-body">Información de tickets</div>
        </div>
    </div>
</div>
@endsection

@include('dashboard.components.Navbar')
@include('dashboard.components.Sidebar')
@include('dashboard.components.Scripts')
@include('dashboard.components.Stylesheets')

@extends('dashboard.components.Main')