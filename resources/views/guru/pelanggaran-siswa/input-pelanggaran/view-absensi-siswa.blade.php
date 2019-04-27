<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-orange">
                    <!-- <h2>
                        PILIH ABSENSI SISWA
                    </h2> -->
                    <h2>
                        UNDER MAINTENANCE
                    </h2>
                </div>
                <!-- <div class="body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#kbm_with_icon_title" data-toggle="tab" aria-expanded="true">
                                <i class="material-icons">class</i> KELAS KBM
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#uts_with_icon_title" data-toggle="tab">
                                <i class="material-icons">assignment</i> UJIAN UTS
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#uas_with_icon_title" data-toggle="tab">
                                <i class="material-icons">send</i> UJIAN UAS
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="kbm_with_icon_title">
                            <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-kbm-absensi-siswa')}}">
                                    {{csrf_field()}}
                                <h2 class="card-inside-title">
                                    Kelas KBM
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <select class="form-control show-tick" name="id_kelas_mp">
                                            @foreach($data_kbm as $data)
                                                <option value="{{$data->id_kelas_mp}}">{{$data->nm_jadwal_hari}} - {{$data->nm_mata_pelajaran}} - {{$data->nm_kelas}} - {{$data->nm_ruangan}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <h2 class="card-inside-title">
                                    Pertemuan Ke
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <select class="form-control show-tick" name="pertemuan_ke">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                            <option value="13">13</option>
                                            <option value="14">14</option>
                                            <option value="15">15</option>
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
                        <div role="tabpanel" class="tab-pane fade" id="uts_with_icon_title">
                            <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-uts-absensi-siswa')}}">
                                    {{csrf_field()}}
                                <h2 class="card-inside-title">
                                    Ujian UTS
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <select class="form-control show-tick" name="id_ujian_mp">
                                            @foreach($data_uts as $data)
                                                <option value="{{$data->id_ujian_mp}}">{{$data->nm_mata_pelajaran}} - {{$data->nm_kelas}} - {{$data->nm_ruangan}}</option>
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
                        <div role="tabpanel" class="tab-pane fade" id="uas_with_icon_title">
                            <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-uts-absensi-siswa')}}">
                                    {{csrf_field()}}
                                <h2 class="card-inside-title">
                                    Ujian UAS
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <select class="form-control show-tick" name="id_ujian_mp">
                                            @foreach($data_uas as $data)
                                                <option value="{{$data->id_ujian_mp}}">{{$data->nm_mata_pelajaran}} - {{$data->nm_kelas}} - {{$data->nm_ruangan}}</option>
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
                </div> -->
            </div>
        </div>
    </div>
</div>
@include('scriptjs')