@section('dashboard.components.Sidebar')
    <!-- Sidebar menu-->
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar">
        <div class="app-sidebar__user">
            <img class="app-sidebar__user-avatar" src="https://s3.amazonaws.com/uifaces/faces/twitter/jsa/48.jpg" alt="User Image" />
            <div>
                <p class="app-sidebar__user-name">{{ Auth::user()->name }}</p>
                <p class="app-sidebar__user-designation">Administrador</p>
            </div>
        </div>

        <ul class="app-menu">
            <li>
                <a
                    @if(Request::path() == 'dashboard') {!!'class="app-menu__item active"' !!} @else {!!'class="app-menu__item"' !!} @endif
                    href="{{URL::to('dashboard')}}"
                >
                    <i class="app-menu__icon fa fa-dashboard"></i>
                    <span class="app-menu__label">Dashboard</span>
                </a>
            </li>

            @foreach ($_PRIVILEGES_MENU_ as $itemMenu)
                <li class="treeview">
                    <a class="app-menu__item" href="#" data-toggle="treeview">
                        <i class="app-menu__icon {{ $itemMenu['category']['icon'] }}"></i>
                        <span class="app-menu__label">{{ $itemMenu['category']['privilege_category'] }}</span>
                        <i class="treeview-indicator fa fa-angle-right"></i>
                    </a>

                    @if( sizeof($itemMenu['privileges']) > 0 )
                        <ul class="treeview-menu">
                            @foreach ($itemMenu['privileges'] as $itemPrivilege)
                                <li>
                                    <a @if(Request::path() == ('dashboard/') . $itemPrivilege['menu_url'] ) {!!'class="treeview-item active"' !!} @else {!!'class="treeview-item"' !!} @endif
                                        href="{{URL::to('dashboard/' . $itemPrivilege['menu_url']) }}"
                                    >
                                        <i class="icon fa fa-circle-o"></i> {{ $itemPrivilege['description'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </aside>
@endsection