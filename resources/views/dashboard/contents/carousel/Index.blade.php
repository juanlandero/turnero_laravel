@section('title', 'Anuncios')
@section('subtitle', 'Lista de Anuncios')
@section('icon', 'fas fa-photo-video')
@section('breadcrumb')
    <li class="breadcrumb-item active"><a href="#">Anuncios</a></li>
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
                                        <a class="btn btn-primary btn-sm" onclick="modal({{ $ad->id }})" href="#">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

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
@include('dashboard.contents.carousel.modal.Delete')
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

        function modal(ad){
            $('#confirm-delete-modal').modal('show')
            $('#btnYes').attr('href', 'ads/delete/'+ad)
        };
    </script>
@endsection

@include('dashboard.components.Navbar')
@include('dashboard.components.Sidebar')
@include('dashboard.components.Scripts')
@include('dashboard.components.Stylesheets')

@extends('dashboard.components.Main')
