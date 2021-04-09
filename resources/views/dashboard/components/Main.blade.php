<!DOCTYPE html>
<html lang="es">
    <head>
        <title>{{$_PAGE_TITLE}} | @yield('title', '*** TITLE ***')</title>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        @yield('dashboard.components.Stylesheets')
        @yield('stylesheets')
    </head>
    <body class="app sidebar-mini">
        @yield('dashboard.components.Navbar')

        @yield('dashboard.components.Sidebar')

        <main class="app-content">
            <div class="app-title">
                <div>
                    <h1><i class="@yield('icon')"></i> @yield('title', '*** TITLE ***')</h1>
                    <p>@yield('subtitle', '*** SUBTITLE ***')</p>
                </div>
            
                <ul class="app-breadcrumb breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}"><i class="fa fa-home fa-lg"></i></a></li>
                    @section('breadcrumb')
                    @show
                </ul>
            </div>
            @yield('content', '*** CONTENT ***')
        </main>

        @yield('dashboard.components.Scripts')
    </body>
</html>