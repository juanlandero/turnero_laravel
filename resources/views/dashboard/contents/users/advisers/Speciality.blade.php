@section('title', 'Asesores')
@section('subtitle', 'Asignar Especialidad')
@section('icon', 'fa fa-user')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('user-advisers.index') }}">Asesores</a></li>
    <li class="breadcrumb-item active"><a href="#">Agregar Especialidad</a></li>
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
        <div class="col-sm-6 col-md-6 col-lg-6">
            {!! Form::open(['route' => 'speciality-store']) !!}
                <div class="tile">
                    <h3 class="line-head">Agregar especialidad</h3>
                    <div class="tile-body">
                        <div class="row">
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            @php
                                $ban = 0;
                                foreach ($specialityType as $key => $speciality) {
                                    foreach ($specialities as $key => $speciality2) {
                                        if ($speciality->id == $speciality2->speciality_type_id) {
                                            $ban = 1;
                                        }
                                    }
                                    if ($ban) {
                                        echo '<div class="col-md-6">
                                                <div class="animated-checkbox">
                                                    <label>
                                                        <input type="checkbox" name="'.$speciality->id.'"  disabled="">
                                                        <span class="label-text">'.$speciality->name.'</span>
                                                    </label>
                                                </div>
                                            </div>';
                                    } else{
                                        echo '<div class="col-md-6">
                                                <div class="animated-checkbox">
                                                    <label>
                                                        <input type="checkbox" name="'.$speciality->id.'">
                                                        <span class="label-text">'.$speciality->name.'</span>
                                                    </label>
                                                </div>
                                            </div>';
                                    }
                                    $ban = 0;
                                }
                            @endphp
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

        <div class="col-sm-6 col-md-6 col-lg-6">
            <div class="tile">
                <h3 class="line-head">Usuario</h3>
                <div class="tile-body">                
                    <h5 class="card-title mt4">{{ $user->name." ".$user->first_name." ".$user->second_name }}</h5>
                    <h6 class="card-subtitle text-muted mb-3">{{ $user->email}}</h6>
                    <h6 class="card-subtitle text-muted mb-4">{{ $user->user_type }}</h6>
                    <hr>
                    <h5 class="card-subtitle myr-2">Especialidades asignadas</h5>
                    @if (sizeof($specialities) < 1)
                        <span class="badge badge-warning mx-1" style="font-size: 14px">Sin especialidades</span>
                    @else
                        @foreach ($specialities as $speciality)
                            <span class="badge badge-warning mx-1 my-2" style="font-size: 14px">
                                {{ $speciality->name }}
                                <a href="#" onclick="modal({{ $speciality->id }})" title="Eliminar"><i class="far fa-times-circle"></i></a>
                            </span>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        
    </div>

    @include('dashboard.contents.users.advisers.modal.Delete')
@endsection

@section('scripts')
<script>
    function modal(speciality){
        $('#confirm-delete-modal').modal('show')
        $('#btnYes').attr('href', 'delete/'+speciality)
    };
</script>
@endsection

@include('dashboard.components.Navbar')
@include('dashboard.components.Sidebar')
@include('dashboard.components.Scripts')
@include('dashboard.components.Stylesheets')

@extends('dashboard.components.Main')