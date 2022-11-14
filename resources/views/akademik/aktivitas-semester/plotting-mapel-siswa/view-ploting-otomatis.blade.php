<div class="container-fluid">

    <div class="row-clearfix">
     
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="block-header">
                <h2>
                    <a class="btn bg-blue waves-effect target-link"
                        href="{{ url(Request::segment(1) .'#aktivitas-semester/plotting-mapel-siswa/view-kelas-plotting/' .$id_semester . '/' .$angkatan ) }}">
                        <i class="material-icons">backspace</i><span>kembali</span>
                    </a>
                </h2>
            </div>
            <div class="card">
                <div class="header">
                    <h2>
                        Plotting Mapel Siswa
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/plotting-mapel-siswa/action-auto-plotting-mapel-siswa')}}">
                            {{csrf_field()}}
                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <h2 class="card-inside-title">
                                    Semester
                                </h2>
                                <select class="form-control show-tick" name="id_semester" disabled="">
                                    @foreach($data_semester as $data)
                                    <option value="{{$data->id_semester}}" @if($id_semester === $data->id_semester) selected @endif>
                                        {{$data->tahun_ajaran}}
                                        {{$data->nm_semester}} 
                                        @if($data->is_aktif_semester == 1)
                                            (Aktif)
                                        @endif
                                    </option>
                                    @endforeach
                                    <input type="hidden" name="id_semester" value="{{$id_semester}}">
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <h2 class="card-inside-title">
                                    Angkatan
                                </h2>
                                <select class="form-control show-tick" name="angkatan" disabled="">
                                    @foreach($thn_masuk_siswa as $data)
                                    <option value="{{$data->thn_masuk_siswa}}"  @if($semester_aktif->thn_akademik_semester == $data->thn_masuk_siswa) selected  @endif>
                                        {{$data->thn_masuk_siswa}}
                                    </option>
                                    @endforeach
                                    <input type="hidden" name="angkatan" value="{{$angkatan}}">
                                    <input type="hidden" name="id_jurusan" value="{{$id_jurusan}}">
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <h2 class="card-inside-title">
                                    Kelas
                                </h2>
                                <select class="form-control show-tick" name="id_kelas">
                                    @foreach($kelas as $data)
                                    <option value="{{$data->id_kelas}}">
                                        {{$data->nm_kelas}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">play_arrow</i><span>Ploting Otomatis</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
