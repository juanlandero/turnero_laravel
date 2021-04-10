@section('title', 'Turnos')
@section('subtitle', 'Reasignar Turnos')
@section('icon', 'fa fa-ticket-alt')
@section('breadcrumb')
    <li class="breadcrumb-item active"><a href="#">Reasignar Turnos</a></li>
@endsection

@section('stylesheets')
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
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

<div id="app-panel-tickets">
    <!-- Datos del turno -->
    
    <div class="row" id="app-reassigned-tickets">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="officeTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Turno</th>
                                    <th>Especialidad</th>
                                    <th>Asesor</th>
                                    <th>Estado</th>
                                    <th>Reasignado</th>
                                    <th>Creado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lstShifts as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->shift }}</td>
                                        <td>{{ $item->speciality }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->status }}</td>
                                        <td class="text-center">
                                            @if ($item->is_reassigned)
                                                <i class="fas fa-check fa-lg"></i>
                                            @endif
                                        </td>
                                        <td>{{ substr($item->created_at, 11) }}</td>
                                        <td class="text-center">
                                            @if (!$item->is_reassigned  && $item->status_id == 1)
                                                <button class="btn btn-primary btn-sm" v-on:click="getListAdvisers({{ $item->id }})" title="Reasignar">
                                                    <i class="fas fa-retweet"></i>
                                                </button>
                                            @endif
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

    @include('dashboard.modals.ReassignmentShift')
</div>

@endsection

@section('scripts')
    <script src="{{ asset('js/plugins/bootstrap-notify.min.js') }}"></script>

    <script src="{{ asset('js/axios.js') }}"></script>
    <script src="{{ asset('js/vue.js') }}"></script>
    <script src="{{ asset('js/dashboard/reasignar.js') }}"></script>
@endsection

@include('dashboard.components.Navbar')
@include('dashboard.components.Sidebar')
@include('dashboard.components.Scripts')
@include('dashboard.components.Stylesheets')

@extends('dashboard.components.Main')