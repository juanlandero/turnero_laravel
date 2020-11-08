@section('dashboard.components.Navbar')
    <!-- Navbar-->
    <header class="app-header">
        <!-- Sidebar toggle button-->
        <a href="#" class="app-nav__item" data-toggle="sidebar" aria-label="Hide Sidebar"><i class="fas fa-bars fa-lg"></i></a>
  
        <!-- Navbar Right Menu-->
        <ul class="app-nav">
            
            <!-- User Menu-->
            <li class="dropdown">
                <a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu">
                    <i class="fa fa-user fa-lg"></i>
                </a>
                <ul class="dropdown-menu settings-menu dropdown-menu-right">
                    <li><a class="dropdown-item" href="{{ URL::to('/dashboard/logout') }}"><i class="fa fa-sign-out fa-lg"></i> Cerrar Sesión</a></li>
                </ul>
            </li>
        </ul>
    </header>
@endsection