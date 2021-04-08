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
        <div class="user-info" style="background: url('https://diakad.sgp1.digitaloceanspaces.com/{{auth_data()->sekolah_data->nm_singkat_sekolah}}/global/user-img-background') no-repeat no-repeat;">
            <div class="image">
                @if(!empty(auth_data()->pengguna->path_foto_pengguna))
                <img src="{{Storage::disk('spaces')->url(auth_data()->pengguna->path_foto_pengguna)}}" height="50" />
                @else
                <img src="https://ui-avatars.com/api/?size=100&name={{auth_data()->pengguna->nm_pengguna}}" height="50" />
                @endif
            </div>
            <div class="info-container">
                <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{auth_data()->pengguna->nm_pengguna}}
                </div>
                <div class="email">{{auth_data()->pengguna->username}}</div>
                @if(!empty(auth_data()->nm_anak_murid))
                <small style="font-size: x-small; color: white;">(Siswa) {{auth_data()->nm_anak_murid}}</small>
                @endif
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
                @foreach(get_moduls() as $modul)
                <li id="modul-item-{{$modul->route}}" class="modul-item">
                    @if(!empty($modul->page))
                    <a class="target-link" href="{{url(Request::segment(1).'#'.$modul->page)}}" class="menu-toggle waves-effect waves-block">
                    @else
                    <a href="javascript:void(0);" class="menu-toggle waves-effect waves-block">
                    @endif
                        <span>{{$modul->nm_modul}}</span>
                    </a>
                    @if(count($modul->menus))
                    <ul class="ml-menu">
                        @foreach($modul->menus as $menu)
                        <li id="menu-item-{{$modul->route}}-{{$menu->page}}" class="menu-item">
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
                Made with <span style="color: #e25555;">&hearts;</span> by <a href="https://dsmartedu.com">@dsmartedu</a>
            </div>
        </div>
        <!-- #Footer -->
    </aside>
    <!-- #END# Left Sidebar -->
</section>