@section('title', 'Anuncios')
@section('subtitle', 'Lista de Anuncios')
@section('icon', 'fas fa-photo-video')
@section('breadcrumb')
    <li class="breadcrumb-item active"><a href="#">Anuncios</a></li>
@endsection
    
@section('content')
<div class="row">
    <div class="col-10">
        <div class="tile">

            <div class="tile-title">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <a href="{{ route('ad.create') }}" class="btn btn-primary" role="button">Nuevo anuncio</a>
                    </div>
                </div>
            </div>
            <div class="tile-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="officeTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Orden</th>
                                <th>Duración (seg)</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ads as $ad)
                                <tr>
                                    <td>{{ $ad->id }}</td>
                                    <td><img src="{{Storage::url($ad->path)}}" width="40%"></td>
                                    <td>{{ $ad->order }}</td>
                                    <td>{{ $ad->duration/1000 }}</td>
                                    <td class="text-center">
                                        <a class="btn btn-primary btn-sm" href="/dashboard/carousel/delete/{{ $ad->id }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>

                                        {{-- <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                            <button class="btn btn-primary" type="button"><i class="fa fa-cogs fa-lg"></i></button>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="/dashboard/carousel/edit/{{ $ad->id }}">Desactivar</a>
                                                    <a class="dropdown-item" href="/dashboard/carousel/delete/{{ $ad->id }}">Eliminar</a>
                                                </div>
                                            </div>
                                        </div> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    {{-- <div class="col-5">
        <img src="{{ asset('img/carousel/carousel/1.jpeg') }}" class="d-block w-100" alt="...">

    </div> --}}

    <div class="col-7">
        <div class="tile">
            <div id="carouselExampleInterval" class="carousel slide carousel-fade" data-ride="carousel">
                <div class="carousel-inner">
            
                    @foreach ($ads as $ad)
                        <div class="carousel-item {{ $ad->is_first }}">
                            <img src="{{Storage::url($ad->path)}}" class="d-block w-100" alt="...">
                        </div>
                    @endforeach
                  
                </div>
            </div>
        </div>
    </div>
   
</div>
@endsection

@section('scripts')
    <!-- Data table plugin-->
    <script type="text/javascript" src="{{ asset('js/plugins/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/plugins/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript">
        $('#officeTable').DataTable( {
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "zeroRecords": "No existen registros",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "No hay registros disponibles",
                "infoFiltered": "(filtrado de _MAX_ total registros)"
            }
        });
    </script>
@endsection

@include('dashboard.components.Navbar')
@include('dashboard.components.Sidebar')
@include('dashboard.components.Scripts')
@include('dashboard.components.Stylesheets')

@extends('dashboard.components.Main')
