<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-light-green">
                    <h2>
                        PLOTTING MAPEL SISWA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-plotting-mapel-siswa')}}">
                            {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Semester
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_semester">
                                    @foreach($data_semester as $data)
                                    <option value="{{$data->id_semester}}" @if($data->is_aktif_semester == 1) selected @endif>
                                        {{$data->tahun_ajaran}}
                                        {{$data->nm_semester}} 
                                        @if($data->is_aktif_semester == 1)
                                            (Aktif)
                                        @endif
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                         <h2 class="card-inside-title">
                            Tahun Masuk
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="angkatan">
                                    @foreach($thn_masuk_siswa as $data)
                                    <option value="{{$data->thn_masuk_siswa}}"  @if($semester_aktif->thn_akademik_semester == $data->thn_masuk_siswa) selected  @endif>
                                        {{$data->thn_masuk_siswa}}
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
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
