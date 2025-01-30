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
                        <br>
                        @if (auth_data()->pengguna->status_join_table == 1 || auth_data()->pengguna->status_join_table == 2)
                            <form action="{{ route('update-foto-profil') }}" method="POST" enctype="multipart/form-data"
                                style="margin-top: 10px">
                                @method('PUT') 
                                @csrf
                                <input type="file" name="foto" accept="image/*" required>
                                <button type="submit" class="btn btn-primary" style="margin-top: 10px">Upload</button>
                            </form>

                        @endif

                        @if (session('success'))
                            <div class="alert alert-success mt-3">
                                {{ session('success') }}
                            </div>
                        @endif
                    </center>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 " style="margin-top: 10px">
                <div class="card">
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive">
                                    <table
                                        class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                                        <tr>
                                            @foreach ($role_pengguna as $role)
                                                <th style="text-align:center"> {{ $role->role->nm_role }}</th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach ($role_pengguna as $role)
                                                <td>
                                                    @foreach ($role->role->modul->sortBy('urutan') as $modul)
                                                        <b> {{ $modul->nm_modul }} </b><br>
                                                        @foreach ($modul->menus->sortBy('urutan') as $menu)
                                                            {{ ' -' . $menu->nm_menu }} <br>
                                                        @endforeach
                                                    @endforeach
                                                </td>
                                            @endforeach
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
    </div>
</div>
<br>
</div>

</div>
@include('scriptjs')
