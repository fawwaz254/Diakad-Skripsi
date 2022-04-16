@php
$theme_name = Request::segment(1);
$route_modul = Request::segment(2);
$route_menu = Request::segment(3);

$path = Request::fullUrl();





$role_aktif = auth_data()->role_aktif->id_role;
$category_file_role = category_file_role($role_aktif);
$id_pengguna = auth_data()->pengguna->id_pengguna;
$id_guru_mgmp = id_guru($id_pengguna);

// $detail_kelas = get_keterangan_kelas($id_pengguna);







@endphp

<section>
    <!-- Left Sidebar -->
    <aside id="leftsidebar" class="sidebar">
        <!-- User Info -->
        <div class="user-info"
            style="background: url('https://diakad.sgp1.digitaloceanspaces.com/{{ auth_data()->sekolah_data->nm_singkat_sekolah }}/global/user-img-background') no-repeat no-repeat;">
            <div class="image">
                @if (!empty(auth_data()->pengguna->path_foto_pengguna))
                    <img src="{{ Storage::disk('spaces')->url(auth_data()->pengguna->path_foto_pengguna) }}"
                        height="50" />
                @else
                    <img src="https://ui-avatars.com/api/?size=100&name={{ auth_data()->pengguna->nm_pengguna }}"
                        height="50" />
                @endif
            </div>
            <div class="info-container">
                <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ auth_data()->pengguna->nm_pengguna }}
                </div>
                <div class="email">{{ auth_data()->pengguna->username }}</div>
                @if (!empty(auth_data()->nm_anak_murid))
                    <small style="font-size: x-small; color: white;">(Siswa) {{ auth_data()->nm_anak_murid }}</small>
                @endif
                <div class="btn-group user-helper-dropdown">
                    <i class="material-icons" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="true">keyboard_arrow_down</i>
                    <ul class="dropdown-menu pull-right">
                        <li><a class="target-link" href="{{ url(Request::segment(1) . '#profile') }}"><i
                                    class="material-icons">person</i>Profile</a></li>
                        <li><a class="target-link" href="{{ url(Request::segment(1) . '#password') }}"><i
                                    class="material-icons">lock</i>Password</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ url(Request::segment(1) . '/signout') }}"><i
                                    class="material-icons">input</i>Sign
                                Out</a></li>
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
                    <a class="target-link menu-toggle waves-effect waves-block"
                        href="{{ url(Request::segment(1) . '#welcome') }}">
                        <i class="material-icons">home</i>
                        <span>Home</span>
                    </a>
                </li>
                @foreach (get_moduls() as $modul)
                    <li id="modul-item-{{ $modul->route }}" class="modul-item">
                        @if (!empty($modul->page))
                            <a class="target-link" href="{{ url(Request::segment(1) . '#' . $modul->page) }}"
                                class="menu-toggle waves-effect waves-block">
                            @else
                                <a href="javascript:void(0);" class="menu-toggle waves-effect waves-block">
                        @endif
                        <span>{{ $modul->nm_modul }}</span>
                        </a>
                        @if (count($modul->menus))
                            <ul class="ml-menu">
                                @foreach ($modul->menus as $menu)
                                    <li id="menu-item-{{ $modul->route }}-{{ $menu->page }}"
                                        class="menu-item">
                                        @if (!empty($menu->page))
                                            <a class="target-link"
                                                href="{{ url(Request::segment(1) . '#' . $modul->route . '/' . $menu->page) }}"
                                                class="waves-effect waves-block">
                                            @else
                                                <a href="javascript:void(0);" class="waves-effect waves-block">
                                        @endif
                                        {{ $menu->nm_menu }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach

                <ul class="ml-menu">
                    <li class="menu-item" id="menu-item-data-sub-kategori">
                        <a href="{{ url(Request::segment(1) . '#manajemen-file/data-file') }}"
                            class="target-link waves-effect waves-block">

                        </a>
                    </li>
                </ul>

                {{-- MGMP --}}
                @if (count($id_guru_mgmp) && $role_aktif !== 7)

                    <li id="modul-item-manajemen-file" class="modul-item">
                        <a href="javascript:void(0);" class="menu-toggle waves-effect waves-block">
                            <span>MGMP</span>
                        </a>
                        @if ($role_aktif == 2)
                            <ul class="ml-menu">
                                <li class="menu-item" id="menu-item-data-sub-kategori">
                                    <a href="{{ url(Request::segment(1) . '#mgmp/data-file-mapel') }}"
                                        class="target-link waves-effect waves-block">
                                        Data File
                                    </a>
                                </li>
                            </ul>
                            {{-- @else
                            <ul class="ml-menu">
                                <li class="menu-item" id="menu-item-data-kategori">
                                    <a href="{{ url(Request::segment(1) . '#manajemen-file/data-kategori') }}"
                                        class="target-link waves-effect waves-block">
                                        Data Kategori
                                    </a>
                                </li>
                                <li class="menu-item" id="menu-item-data-sub-kategori">
                                    <a href="{{ url(Request::segment(1) . '#manajemen-file/data-sub-kategori') }}"
                                        class="target-link waves-effect waves-block">
                                        Data Sub Kategori
                                    </a>
                                </li>
                                <li class="menu-item" id="menu-item-data-sub-kategori">
                                    <a href="{{ url(Request::segment(1) . '#manajemen-file/data-file') }}"
                                        class="target-link waves-effect waves-block">
                                        Data File
                                    </a>
                                </li>
                            </ul> --}}
                        @endif
                    </li>
                @endif
                {{-- MGMP --}}

                {{-- Manajemen File --}}
                @if ($category_file_role && $role_aktif !== 14)
                    <li id="modul-item-manajemen-file" class="modul-item">
                        <a href="javascript:void(0);" class="menu-toggle waves-effect waves-block">
                            <span>Manajemen File</span>
                        </a>
                        @if ($role_aktif == 2)
                            <ul class="ml-menu">
                                <li class="menu-item" id="menu-item-data-sub-kategori">
                                    <a href="{{ url(Request::segment(1) . '#manajemen-file/data-file') }}"
                                        class="target-link waves-effect waves-block">
                                        Data File
                                    </a>
                                </li>
                            </ul>
                        @else
                            <ul class="ml-menu">
                                <li class="menu-item" id="menu-item-data-kategori">
                                    <a href="{{ url(Request::segment(1) . '#manajemen-file/data-kategori') }}"
                                        class="target-link waves-effect waves-block">
                                        Data Kategori
                                    </a>
                                </li>
                                <li class="menu-item" id="menu-item-data-sub-kategori">
                                    <a href="{{ url(Request::segment(1) . '#manajemen-file/data-sub-kategori') }}"
                                        class="target-link waves-effect waves-block">
                                        Data Sub Kategori
                                    </a>
                                </li>
                                <li class="menu-item" id="menu-item-data-sub-kategori">
                                    <a href="{{ url(Request::segment(1) . '#manajemen-file/data-file') }}"
                                        class="target-link waves-effect waves-block">
                                        Data File
                                    </a>
                                </li>
                            </ul>
                        @endif
                    </li>
                @endif
                {{-- Manajemen File --}}
            </ul>
        </div>

        <!-- #Menu -->
        <!-- Footer -->
        <div class="legal">
            <div class="copyright">
                Copyright &copy;2018
            </div>
            <div class="version">
                Made with <span style="color: #e25555;">&hearts;</span> by <a
                    href="https://dsmartedu.com">@eduschool</a>
            </div>
        </div>
        <!-- #Footer -->
    </aside>
    <!-- #END# Left Sidebar -->
</section>
