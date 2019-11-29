<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#setting-kelas/sekretaris-kelas/view-kelas/'.$data_kelas->id_kelas)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        EDIT SEKRETARIS KELAS {{$data_kelas->nm_kelas}}
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-sekretaris-kelas/edit/'.$data_sekretaris_kelas->id_sekretaris_kelas)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Nama Kelas
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_kelas">
                                    <option value="{{$data_kelas->id_kelas}}">{{$data_kelas->nm_kelas}}</option>
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Semester
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_semester">
                                    <option value="{{$data_semester->id_semester}}">{{$data_semester->tahun_ajaran}}
                                        {{$data_semester->nm_semester}}</option>
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Sekretaris
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_siswa">
                                    @foreach($data_siswa as $data)
                                    @if($data->id_siswa == $data_sekretaris_kelas->id_siswa)
                                    <option value="{{$data->id_siswa}}" selected>{{$data->nm_pengguna}}</option>
                                    @else
                                    <option value="{{$data->id_siswa}}">{{$data->nm_pengguna}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Status Aktif
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="is_aktif">
                                    @if($data_sekretaris_kelas->is_aktif == 0)
                                    <option value="0" selected>Tidak</option>
                                    <option value="1">Ya</option>
                                    @else
                                    <option value="0">Tidak</option>
                                    <option value="1" selected>Ya</option>
                                    @endif
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