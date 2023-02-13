<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{ url(Request::segment(1)) }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a>
    </div>
    </h2>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card is-gap">
                <div class="body">
                    <center>
                        <h4>Biodata</h4>
                        @if (!empty($pengguna->pengguna->path_foto_pengguna))
                            <img src="{{ Storage::disk('spaces')->url($pengguna->pengguna->path_foto_pengguna) }}"
                                style="height: 270px; width: 180px">
                        @else
                            <img src="{{ asset('media/blank-user.png') }}" style="height: 270px; width: 180px">
                        @endif
                    </center>
                    <br>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 " style="margin-top: 10px">
                @foreach ($role_pengguna as $role)
                    <button style="background-color: white">
                        Role : {{ $role->role->nm_role }}</button>
                @endforeach
            </div>
            @foreach ($role_pengguna as $role)
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 " style="margin-top: 10px">
                    <form action='' method='post'>
                        <button type='submit'
                            style="background-color: transparent; border: none; font-weight: bold; cursor: pointer; margin-top:10px">
                            Role : {{ $role->role->nm_role }}</button>
                    </form>
                    </a>
                    <div class="card">
                        <div class="body " style="text-align: -webkit-center;">
                            @foreach ($role->role->modul as $modul)
                                <h5>
                                    {{ $modul->nm_modul }}
                                </h5>
                                @foreach ($modul->menus as $menu)
                                    <a href="{{ url(Request::segment(0) . Request::segment(1) . '#device/fingerprint') }}"
                                        style=" display: inline-block;">
                                        <div class="card" style="margin-top: 5px">
                                            <div class="body bg-green" style="text-align: -webkit-center;">
                                                <h5>
                                                    {{ $menu->nm_menu }}
                                                </h5>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <br>
    </div>
</div>
<br>
</div>

</div>
@include('scriptjs')
