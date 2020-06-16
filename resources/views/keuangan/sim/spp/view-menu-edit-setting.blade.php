<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                    INPUT PENERIMAAN
                    </h2>
                </div>
                @include('keuangan/sim/spp/partials/header-card-menu')
            </div>
            <div class="card">
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/sim/spp/setting/save')}}">
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
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Kelas</label>
                                        <input type="text" class="form-control" disabled="" value="{{$kelas->nm_kelas}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Biaya SPP JULI</label>
                                        <input type="number" class="form-control" name="nominal_spp_juli" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Biaya SPP NON-JULI</label>
                                        <input type="number" class="form-control" name="nominal_spp_non_juli" required="">
                                    </div>
                                </div>
                                <label>Kelompok Biaya</label>
                                <select class="form-control show-tick" name="id_kelompok_biaya">
                                    <option value="">Pilih Kelompok Biaya</option>
                                    @foreach($data_kelompok_biaya as $kelompok_biaya)
                                    <option value="{{$kelompok_biaya->id_kelompok_biaya}}">{{$kelompok_biaya->nm_kelompok_biaya}}</option>
                                    @endforeach
                                </select>
                                <br>
                                <label>Kelompok Biaya Internal</label>
                                <select class="form-control show-tick" name="id_kelompok_biaya_internal">
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