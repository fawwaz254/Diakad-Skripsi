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
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/sim/spp/input/save')}}">
                        {{csrf_field()}}
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                @if($realisasi)
                                <input type="hidden" name="id_realisasi" value="{{$realisasi->id_realisasi}}">
                                @endif
                                <label>Tahun Ajaran</label>
                                <select class="form-control show-tick" name="tahun">
                                @foreach($data_semester as $semester)
                                    <option value="{{$semester->thn_akademik_semester}}"
                                        @if($realisasi)
                                            @if($semester->id_semester == $realisasi->id_semester_realisasi)
                                                selected
                                            @endif
                                        @else
                                            @if($semester->thn_akademik_semester == $tahun_akademik_semester)
                                                selected
                                            @endif
                                        @endif>
                                    {{$semester->tahun_ajaran}}</option>
                                @endforeach
                                </select>
                                <br>
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Tanggal Terima</label>
                                        @if($realisasi)
                                        <input type="text" class="datepicker form-control" name="tgl_realisasi" required="" value="{{$realisasi->tgl_realisasi}}">
                                        @else
                                        <input type="text" class="datepicker form-control" name="tgl_realisasi" required="" value="{{\Carbon\Carbon::now()->format('Y-m-d')}}">
                                        @endif
                                    </div>
                                </div>
                                <label>Kategori</label>
                                <select class="form-control show-tick" name="id_subkategori_rapb">
                                    @foreach($data_subkategori as $subkategori)
                                    <option value="{{$subkategori->id_subkategori_rapb}}" {{$realisasi? ($realisasi->rapb->id_ket_subkategori_rapb == $subkategori->id_subkategori_rapb)? 'selected' : '' : ''}}>{{$subkategori->kode_subkategori_rapb}} {{$subkategori->nm_subkategori_rapb}}</option>
                                    @endforeach
                                </select>
                                <br>
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Uraian</label>
                                        <textarea class="form-control" name="nm_realisasi" rows="4" cols="100">@if($realisasi){{$realisasi->nm_realisasi}}@endif</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Nilai</label>
                                        <input type="number" class="form-control" name="dana_realisasi" required="" @if($realisasi) value="{{$realisasi->dana_realisasi}}" @endif>
                                    </div>
                                </div>
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
    <script>
    $(function(){
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: false
        });
    });
    </script>
