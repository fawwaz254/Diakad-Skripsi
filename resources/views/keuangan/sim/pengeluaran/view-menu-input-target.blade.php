<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                    INPUT TARGET
                    </h2>
                </div>
                @include('keuangan/sim/pengeluaran/partials/header-card-menu')
            </div>
            <div class="card">
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/sim/pengeluaran/target/save')}}">
                        {{csrf_field()}}
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <input type="hidden" class="form-control" name="rapb" value="{{(!empty($item)? $item->id_rapb : '')}}">
                                <input type="hidden" class="form-control" name="subkategori" required="" value="{{$subkategori_rapb->id_subkategori_rapb}}">
                                <input type="hidden" class="form-control" name="tahun" required="" value="{{$tahun_akademik_semester}}">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Tahun Akademik</label>
                                        <input type="text" class="form-control" disabled="" value="{{$semester_mulai->tahun_ajaran}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Kode (Nama)</label>
                                        <input type="text" class="form-control" disabled="" value="{{$subkategori_rapb->kode_subkategori_rapb}} {{$subkategori_rapb->nm_subkategori_rapb}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Nilai</label>
                                        <input type="number" class="form-control" name="dana_perkiraan_rapb" value="{{(!empty($item)? $item->dana_perkiraan_rapb : 0)}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-btn-submit waves-effect" type="submit"><i class="material-icons">save</i><span>Simpan</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')