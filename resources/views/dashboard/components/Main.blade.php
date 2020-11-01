<!DOCTYPE html>
<html lang="es">
    <head>
        <title>{{$_PAGE_TITLE}} | @yield('title', '*** TITLE ***')</title>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        @yield('dashboard.components.Stylesheets')
        @yield('stylesheets')
    </head>
    <body class="app sidebar-mini">
        @yield('dashboard.components.Navbar')

        @yield('dashboard.components.Sidebar')

        <main class="app-content">
            @yield('content', '*** CONTENT ***')
        </main>

        @yield('dashboard.components.Scripts')
    </body>
</html>