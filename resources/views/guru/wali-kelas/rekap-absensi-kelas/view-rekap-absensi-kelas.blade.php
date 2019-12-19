<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        PILIH MATA PELAJARAN {{$wali_kelas->nm_kelas}}
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-rekap-absensi-kelas')}}">
                            {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Kelas KBM
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_jadwal_kelas_mp">
                                    <option value="" disabled selected >-- Pilih Kelas KBM --</option>
                                    @foreach($grup_kbm_perhari as $hari => $data_kbm)
                                    <optgroup label="{{$hari}}">
                                        @foreach($data_kbm as $data)
                                            <option value="{{$data->id_jadwal_kelas_mp}}">{{$data->kelas_mp->mata_pelajaran->nm_mata_pelajaran}} - {{$data->ruangan->nm_ruangan}}</option>
                                        @endforeach
                                    </optgroup>
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