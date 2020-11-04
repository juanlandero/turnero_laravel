@section('title', 'Inicio')
@section('subtitle', 'Dashboard')
@section('icon', 'fa fa-tachometer-alt')

@section('content')

@endsection

@section('scripts')

@endsection

@include('dashboard.components.Navbar')
@include('dashboard.components.Sidebar')
@include('dashboard.components.Scripts')
@include('dashboard.components.Stylesheets')

@extends('dashboard.components.Main')