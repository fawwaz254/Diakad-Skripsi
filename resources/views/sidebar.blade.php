@php
    $theme_name = Request::segment(1);
    $route_modul = Request::segment(2);
    $route_menu = Request::segment(3);

    $path = Request::fullUrl();
@endphp

<section>
    <!-- Left Sidebar -->
    <aside id="leftsidebar" class="sidebar">
        <!-- User Info -->
        <div class="user-info" style="background: url('https://diakad.sgp1.digitaloceanspaces.com/{{$sekolah->nm_singkat_sekolah}}/global/user-img-background') no-repeat no-repeat;">
            <div class="image">
                <img src="https://ui-avatars.com/api/?size=100&name={{$auth_data->pengguna->nm_pengguna}}" height="50" />
            </div>
            <div class="info-container">
                <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{$auth_data->pengguna->nm_pengguna}}</div>
                <div class="email">{{$auth_data->pengguna->username}}</div>
                <div class="btn-group user-helper-dropdown">
                    <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                    <ul class="dropdown-menu pull-right">
                        <li><a class="target-link" href="{{url(Request::segment(1).'#profile')}}"><i class="material-icons">person</i>Profile</a></li>
                        <li><a class="target-link" href="{{url(Request::segment(1).'#password')}}"><i class="material-icons">lock</i>Password</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="{{url(Request::segment(1).'/signout')}}"><i class="material-icons">input</i>Sign Out</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- #User Info -->
        <!-- Menu -->
        <div class="menu">
            <ul class="list">
                <li class="header">MAIN NAVIGATION</li>
                <li id="modul-item-welcome" class="modul-item">
                    <a class="target-link menu-toggle waves-effect waves-block" href="{{url(Request::segment(1).'#welcome')}}">
                        <i class="material-icons">home</i>
                        <span>Home</span>
                    </a>
                </li>
                @foreach($auth_data->moduls as $modul)
                <li id="modul-item-{{$modul->route}}" class="modul-item">
                    @if(!empty($modul->page))
                    <a class="target-link" href="{{url(Request::segment(1).'#'.$modul->page)}}" class="menu-toggle waves-effect waves-block">
                    @else
                    <a href="javascript:void(0);" class="menu-toggle waves-effect waves-block">
                    @endif
                        <span>{{$modul->nm_modul}}</span>
                    </a>
                    @if($auth_data->menus->where('id_modul', $modul->id_modul)->first())
                    <ul class="ml-menu">
                        @foreach($auth_data->menus->where('id_modul', $modul->id_modul)->all() as $menu)
                        <li id="menu-item-{{$menu->page}}" class="menu-item">
                            @if(!empty($menu->page))
                            <a class="target-link" href="{{url(Request::segment(1).'#'.$modul->route.'/'.$menu->page)}}" class="waves-effect waves-block">
                            @else
                            <a href="javascript:void(0);" class="waves-effect waves-block">
                            @endif
                                {{$menu->nm_menu}}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </li>
                @endforeach
            </ul>
        </div>
        <!-- #Menu -->
        <!-- Footer -->
        <div class="legal">
            <div class="copyright">
                Copyright &copy;2018 
            </div>
            <div class="version">
                Made with <span style="color: #e25555;">&hearts;</span> by @solusimaster
            </div>
        </div>
        <!-- #Footer -->
    </aside>
    <!-- #END# Left Sidebar -->
</section>