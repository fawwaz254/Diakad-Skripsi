<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                    EDIT SETTING SPP
                    </h2>
                </div>
                @include('keuangan/sim/spp/partials/header-card-menu')
            </div>
            <div class="card">
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/sim/spp/setting-non-spp/save')}}">
                        {{csrf_field()}}
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label>Tahun Ajaran</label>
                                <input type="hidden" name="tahun_akademik_semester" required="" value="{{$tahun_akademik_semester}}">
                                <input type="hidden" name="id_kelas" required="" value="{{$kelas->id_kelas}}">
                                @foreach($data_semester as $semester)
                                    @if($semester->thn_akademik_semester == $tahun_akademik_semester)
                                    <input type="text" class="form-control" disabled="" value="{{$semester->tahun_ajaran}}">
                                    @endif
                                @endforeach
                                <br>
                                <label>Ganjil/Genap</label>
                                <select class="form-control show-tick" name="semester" required="">
                                    <option value="1">Ganjil</option>
                                    <option value="2">Genap</option>
                                </select>
                                <br>
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Kelas</label>
                                        <input type="text" class="form-control" disabled="" value="{{$kelas->nm_kelas}}">
                                    </div>
                                </div>
                                <label>Setting Untuk</label>
                                <select class="form-control show-tick" name="id_biaya" required="">
                                    @foreach($data_biaya as $biaya)
                                    <option value="{{$biaya->id_biaya}}">{{$biaya->nm_biaya}}</option>
                                    @endforeach
                                </select>
                                <br>
                                <label>Jenis Biaya</label>
                                <select class="form-control show-tick" name="id_jenis_detail_biaya" required="">
                                    @foreach($data_jenis_detail_biaya as $data)
                                    <option value="{{$data->id_jenis_detail_biaya}}">{{$data->nm_jenis_detail_biaya}} </option>
                                    @endforeach
                                </select>
                                <br>
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Besar Biaya</label>
                                        <input type="number" class="form-control" name="besar_biaya" required="">
                                    </div>
                                </div>
                                <label>Kelompok Biaya</label>
                                <select class="form-control show-tick" name="id_kelompok_biaya" required="">
                                    <option value="">Pilih Kelompok Biaya</option>
                                    @foreach($data_kelompok_biaya as $kelompok_biaya)
                                    <option value="{{$kelompok_biaya->id_kelompok_biaya}}">{{$kelompok_biaya->nm_kelompok_biaya}}</option>
                                    @endforeach
                                </select>
                                <br>
                                <label>Kelompok Biaya Internal</label>
                                <select class="form-control show-tick" name="id_kelompok_biaya_internal" required="">
                                    <option value="">Pilih Kelompok Biaya Internal</option>
                                    @foreach($data_kelompok_biaya_internal as $kelompok_biaya_internal)
                                    <option value="{{$kelompok_biaya_internal->id_kelompok_biaya_internal}}">{{$kelompok_biaya_internal->nm_kelompok_biaya_internal}}</option>
                                    @endforeach
                                </select>
                                <br>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-btn-submit waves-effect"><i class="material-icons">save</i><span>Simpan</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')