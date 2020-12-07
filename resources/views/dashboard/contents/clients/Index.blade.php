@section('title', 'Clientes')
@section('subtitle', 'Listado de Clientes')
@section('icon', 'fas fa-address-book')
@section('breadcrumb')
    <li class="breadcrumb-item active"><a href="#">Clientes</a></li>
@endsection

@section('content')

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

<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-title">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <a href="{{ route('clients.create') }}" class="btn btn-primary" role="button">Nuevo cliente</a>
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
                                <th>Número de cliente</th>
                                <th>Sexo</th>
                                <th>Oficina de registro</th>
                                <th>Resgistrado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lstClients as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name." ".$item->first_name." ".$item->second_name }}</td>
                                    <td>{{ $item->client_number }}</td>
                                    <td>{{ $item->sex }}</td>
                                    <td>{{ $item->office }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                            <button class="btn btn-primary" type="button"><i class="fa fa-cogs fa-lg"></i></button>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="/dashboard/clients/edit/{{ $item->id }}">Editar</a>
                                                    <a class="dropdown-item" href="#" onclick="modal({{ $item->id }})">Eliminar</a>
                                                </div>
                                            </div>
                                        </div>
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

@include('dashboard.contents.clients.modal.Delete')
    
@endsection

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
            
        function modal(client){
            $('#confirm-delete-modal').modal('show')
            $('#btnYes').attr('href', 'clients/delete/'+client)
        };
    </script>
@endsection

@include('dashboard.components.Navbar')
@include('dashboard.components.Sidebar')
@include('dashboard.components.Scripts')
@include('dashboard.components.Stylesheets')

@extends('dashboard.components.Main')