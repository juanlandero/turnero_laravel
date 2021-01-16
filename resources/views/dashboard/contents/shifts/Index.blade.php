@section('title', 'Turnos')
@section('subtitle', 'Panel de Turnos')
@section('icon', 'fa fa-ticket-alt')
@section('breadcrumb')
    <li class="breadcrumb-item active"><a href="#">Turnos</a></li>
@endsection

@section('stylesheets')
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animate.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/plugins/pusher7.min.js') }}"></script>
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
    <div class="row" v-if="isActive">
        <!-- Panel izquierdo -->
        <div class="col-md-4">
            <button class="btn btn-block mb-2" :class="userStatus.btnType" type="button" v-on:click="userBreak(2)" v-bind:disabled="disabledButtons.buttonConnect">
                <i class="fas fa-circle" style="margin-right: 5px;"></i>
                ${ userStatus.text }
            </button>

            <div class="tile p-0 mb-0" style="height: 380px; overflow-y: scroll;">
                <h4 class="tile-title folder-head text-center">En espera</h4>
                <div class="tile-body">
                    <div class="row text-center my-2">
                        <div class="col-4"><h6>Turno</h6></div>
                        <div class="col-4"><h6>Tipo</h6></div>
                        <div class="col-4 text-truncate"><h6>Especialidad</h6></div>								
                    </div>

                    <item-my-list v-for="myShift in shiftList"
                        v-bind:key = "myShift.id"
                        :id = "myShift.id"
                        :shift = "myShift.shift"
                        :type = "myShift.shift_type"
                        :speciality = "myShift.speciality"
                    ></item-my-list>
                </div>
            </div>
        </div>

        <!-- Panel derecho -->
        <div class="col-md-8">
            <div class="tile mb-0">
                <div class="tile-title-w-btn">
                    <h3 class="title">Turno actual</h3>
                    <p>
                        <button class="btn btn-info icon-btn" v-on:click="nextShift()" v-bind:disabled="disabledButtons.buttonNext">
                            <i class="fa fa-step-forward"></i> Siguiente turno
                        </button>
                    </p>
                </div>

                <div class="row text-center my-3">
                    <div class="col-4">
                        <h3>${ attending.shift }</h3>
                        <p>TURNO</p>
                    </div>
                    <div class="col-4">
                        <h3>${ attending.type }</h3>
                        <p>TIPO</p>
                    </div>
                    <div class="col-4">
                        <h3>${ attending.speciality }</h3>
                        <p>ESPECIALIDAD</p>
                    </div>
                </div>

                <div class="row line-head"></div>

                <div class="row text-center my-3">
                    <div class="col-12">
                        <h3>${ attending.client }</h3>
                        <p>CLIENTE</p>
                    </div>
                    <div class="col-4">
                        <h3>${ attending.number }</h3>
                        <p>NÚMERO</p>
                    </div>
                    <div class="col-4">
                        <h3>${ attending.sex }</h3>
                        <p>SEXO</p>
                    </div>
                    <div class="col-4">
                        <h3>${ attending.time }</h3>
                        <p>GENERADO</p>
                    </div>
                </div>

                <div class="row ">
                    <div class="col-4">
                        <button class="btn btn-outline-danger btn-block" type="button" v-on:click="changeStatusShift(3)" v-bind:disabled="disabledButtons.buttonAbandoned">
                            <i class="fas fa-running"></i> Abandonado
                        </button>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-outline-warning btn-block" type="button" v-on:click="changeStatusShift(2)" v-bind:disabled="disabledButtons.buttonFinalized">
                            <i class="fa fa-thumbs-up"></i> Finalizar
                        </button>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-outline-secondary btn-block" type="button" v-on:click="getListAdvisors()" v-bind:disabled="disabledButtons.buttonReassigned">
                            <i class="fa fa-people-arrows"></i> Reasignar
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
    
    <div class="row align-items-center justify-content-center" v-else style="height: 300px">
        <div class="col-5 ">
            <button class="btn btn-primary btn-lg btn-block" type="button" v-on:click="setServiceOn()">Iniciar servicio</button>
        </div>
    </div>

    @include('dashboard.modals.ReassignmentShift')
</div>

@endsection

@section('scripts')
    <script src="{{ asset('js/plugins/bootstrap-notify.min.js') }}"></script>

    <script src="{{ asset('js/axios.js') }}"></script>
    <script src="{{ asset('js/vue.js') }}"></script>
    <script src="{{ asset('js/dashboard/panel.js') }}"></script>
@endsection

@include('dashboard.components.Navbar')
@include('dashboard.components.Sidebar')
@include('dashboard.components.Scripts')
@include('dashboard.components.Stylesheets')

@extends('dashboard.components.Main')