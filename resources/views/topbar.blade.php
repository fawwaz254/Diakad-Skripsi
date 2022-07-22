@php
$theme_name = Request::segment(1);
// dd(Auth::user()->sekolah_data);
@endphp
@switch($theme_name)
    @case('pendidikan')
        {!! '<body class="theme-green">' !!}
    @break

    @case('guru')
        {!! '<body class="theme-orange">' !!}
    @break

    @case('siswa')
        {!! '<body class="theme-blue">' !!}
    @break

    @case('wali-murid')
        {!! '<body class="theme-brown">' !!}
    @break

    @case('bimbingan-konseling')
        {!! '<body class="theme-deep-purple">' !!}
    @break

    @case('kesiswaan')
        {!! '<body class="theme-indigo">' !!}
    @break

    @case('akademik')
        {!! '<body class="theme-light-green">' !!}
    @break

    @case('sumber-daya')
        {!! '<body class="theme-pink">' !!}
    @break

    @case('keuangan')
        {!! '<body class="theme-amber">' !!}
    @break

    @case('sarana-prasarana')
        {!! '<body class="theme-purple">' !!}
    @break

    @case('ppdb')
        {!! '<body class="theme-lime">' !!}
    @break

    @case('alumni')
        {!! '<body class="theme-blue-grey">' !!}
    @break

    @case('pelatih-ekskul')
        {!! '<body class="theme-deep-orange">' !!}
    @break

    @case('sekretariat')
        {!! '<body class="theme-black">' !!}
    @break

    @case('tendik')
        {!! '<body class="theme-grey">' !!}
    @break

    @case('administrator')
        {!! '<body class="theme-cyan">' !!}
    @break

    @case('humas')
        {!! '<body class="theme-teal">' !!}
    @break

    @case('rapor-buku-induk')
        {!! '<body class="theme-red">' !!}
    @break

    @default
@endswitch
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="preloader">
            <div class="spinner-layer pl-green">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>
        <p>Please wait...</p>
    </div>
</div>
<!-- #END# Page Loader -->
<div class="overlay"></div>
<!-- Search Bar -->
<div class="search-bar">
    <div class="search-icon">
        <i class="material-icons">search</i>
    </div>
    <form id="form-search">
        <input type="text" name="q" placeholder="Ketikkan nama menu di sini...">
    </form>
    <div class="close-search">
        <i class="material-icons">close</i>
    </div>
</div>
<!-- #END# Search Bar -->
@php
$sekolah = \App\Models\Sekolah::orderBy('id_sekolah')->first();
@endphp
<nav class="navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse"
                data-target="#navbar-collapse" aria-expanded="false"></a>
            <a href="javascript:void(0);" class="bars" style="display: none;"></a>
            <a class="navbar-brand"><?= str_replace('-', ' ', strtoupper($theme_name)) ?>
                <?= strtoupper(auth_data()->sekolah_data->nm_sekolah) ?> -
                @if ($sekolah->nm_singkat_sekolah == 'smawidyadarma')
                    {{ strtoupper(env('APP_NAME', 'EDUSCHOOL')) }}
                @else
                    DIAKAD
                @endif
            </a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <!-- Call Search -->
                <li><a href="javascript:void(0);" class="js-search" data-close="true"><i
                            class="material-icons">search</i></a></li>
                <!-- #END# Call Search -->
                <!-- Notifications -->
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                        <i class="material-icons">notifications</i>
                        <span class="label-count">0</span>
                    </a>
                    @php
                        $count_notification = 0;
                    @endphp
                    <ul class="dropdown-menu">
                        <li class="header">NOTIFICATIONS</li>
                        <li class="body">
                            <ul class="menu">
                                @if ($count_notification <= 0)
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-light-green">
                                                <i class="material-icons">mood</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4>Tidak ada notifikasi baru</h4>
                                            </div>
                                        </a>
                                    </li>
                                @else
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-light-green">
                                                <i class="material-icons">person_add</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4>12 new members joined</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 14 mins ago
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-cyan">
                                                <i class="material-icons">add_shopping_cart</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4>4 sales made</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 22 mins ago
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-red">
                                                <i class="material-icons">delete_forever</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4><b>Nancy Doe</b> deleted account</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 3 hours ago
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-orange">
                                                <i class="material-icons">mode_edit</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4><b>Nancy</b> changed name</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 2 hours ago
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-blue-grey">
                                                <i class="material-icons">comment</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4><b>John</b> commented your post</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 4 hours ago
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-light-green">
                                                <i class="material-icons">cached</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4><b>John</b> updated status</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 3 hours ago
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-purple">
                                                <i class="material-icons">settings</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4>Settings updated</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> Yesterday
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                        @if ($count_notification > 0)
                            <li class="footer">
                                <a href="javascript:void(0);">View All Notifications</a>
                            </li>
                        @endif
                    </ul>
                </li>
                <!-- #END# Notifications -->
                @if ($semester_aktif = \App\Libraries\Pendidikan\LibDataAkademik::fetchDataSemesterAktif(auth_data()))
                    <li><a class="navbar-brand" style="display: block; left: 8px;">TH AJARAN
                            {{ strtoupper($semester_aktif->tahun_ajaran . ' (' . $semester_aktif->nm_semester . ')') }}</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
