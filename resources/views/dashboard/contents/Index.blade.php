@section('title', 'Inicio')
@section('subtitle', 'Dashboard')
@section('icon', 'fa fa-tachometer-alt')
@section('user_type', 'Supervisor')

@section('content')


<div class="row">
    <div class="col-md-6 col-lg-3">
        <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
            <div class="info">
                <h4>Total</h4>
                <p><b>{{ $widget[3] }}</b></p>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="widget-small info"><i class="icon fas fa-smile fa-3x"></i>
            <div class="info">
                <h4>Atendidos</h4>
                <p><b id="attended">{{ $widget[0] }}</b></p>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="widget-small danger"><i class="icon fas fa-running fa-3x"></i>
            <div class="info">
                <h4>Abandonados</h4>
                <p><b id="abandoned">{{ $widget[1] }}</b></p>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="widget-small warning"><i class="icon fas fa-people-arrows fa-3x"></i>
            <div class="info">
            <h4>Reasignados</h4>
            <p><b id="reassigned">{{ $widget[2] }}</b></p>
            </div>
        </div>
    </div>
</div>

<div class="row" style="min-height: 300px">
    <div class="col-md-6 col-sm-12">
        <div class="tile justify-content-center ">
            <div class="tile-title-w-btn">
                <h3 class="title">{{ $userType }}</h3>
                <div class="btn-group">
                    <a class="btn btn-outline-primary" href="{{ URL::to('/dashboard/logout') }}" title="Cerrar sesión">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>

            <div class="tile-body text-center">
                <img class="app-sidebar__user-aatar" src="{{ asset('img/user.png') }}" alt="User Image" width="30%"/>
                <h4 class="mt-3">{{ $user->name." ".$user->first_name." ".$user->second_name }}</h4>
                <h4><small class="text-muted">{{ $user->email }}</small></h4>
            </div>
            <div class="tile-footer text-center">                
                @if (sizeof($specialities) < 1)
                    <span class="badge  badge-warning mx-1" style="font-size: 14px">Sin especialidades</span>
                @else
                    @foreach ($specialities as $speciality)
                        <span class="badge  badge-warning mx-1" style="font-size: 14px">{{ $speciality->name }}</span>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6 col-sm-12">
        <div class="tile">
          <h3 class="tile-title">Total de turnos por especialidades</h3>
          <div style="min-height: 300">
            <canvas id="myChart" width="400" height="240"></canvas>
        </div>
        </div>
    </div>
</div>




@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('js/plugins/chart.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/axios.js') }}"></script>
<script>
    var ctx = document.getElementById('myChart').getContext('2d')

    axios.get('dashboard/data-chart')
        .then(function (response) {
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: response.data['label'],
                    datasets: [{
                        label: 'Turnos por especialidades',
                        data: response.data['data'],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            })
        })
        .catch(function (error) {
            console.log(error)
        })
</script>
@endsection

@include('dashboard.components.Navbar')
@include('dashboard.components.Sidebar')
@include('dashboard.components.Scripts')
@include('dashboard.components.Stylesheets')

@extends('dashboard.components.Main')