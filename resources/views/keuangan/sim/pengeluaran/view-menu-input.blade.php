<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        INPUT PENGELUARAN
                    </h2>
                </div>
                @include('keuangan/sim/pengeluaran/partials/header-card-menu')
            </div>
            <div class="card">
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/sim/pengeluaran/input/save')}}">
                        {{csrf_field()}}
                        <div class="row clearfix">
                            <input type="hidden" name="id_realisasi" value="{{ $pengeluaran->id_realisasi ?? null }}">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Tahun Ajaran</label>
                                        <select class="form-control show-tick" name="tahun">
                                            @foreach($data_semester as $semester)
                                            <option value="{{$semester->thn_akademik_semester}}" @if($semester->thn_akademik_semester == $tahun_akademik_semester)
                                                selected
                                                @endif>
                                                {{$semester->tahun_ajaran}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <p></p>
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Tanggal</label>
                                        <input type="text" class="datepicker form-control" name="tgl_realisasi" required="" value="{{ isset($pengeluaran) ? $pengeluaran->tgl_realisasi : \Carbon\Carbon::now()->format('Y-m-d') }}">
                                    </div>
                                </div>
                                <p></p>
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Kategori</label>
                                        <select class="form-control show-tick" data-live-search="true" name="id_subkategori_rapb">
                                            @foreach($data_subkategori as $subkategori)
                                            <option value="{{$subkategori->id_subkategori_rapb}}" @if(isset($rapb) && $subkategori->id_subkategori_rapb == $rapb->id_subkategori_rapb)
                                                selected
                                                @endif>
                                                {{$subkategori->kode_subkategori_rapb}} {{$subkategori->nm_subkategori_rapb}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <p></p>
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Uraian</label>
                                        <textarea class="form-control" name="nm_realisasi" rows="4" cols="100">{{ isset($pengeluaran) ? $pengeluaran->nm_realisasi : null }}</textarea>
                                    </div>
                                </div>
                                <p></p>
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Nominal</label>
                                        <input type="number" class="form-control" name="dana_realisasi" required="" value="{{ $pengeluaran->dana_realisasi ?? null }}">
                                    </div>
                                </div>
                                <p></p>
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Dana berasal dari</label>
                                        <div class="demo-radio-button">
                                            <input name="sumber_dana" type="radio" id="radio_1"
                                                value="SPP" checked="" class="with-gap" />
                                            <label for="radio_1">SPP</label>
                                            <input name="sumber_dana" type="radio" id="radio_2"
                                                value="BANTUAN" class="with-gap" />
                                            <label for="radio_2">Dana Bantuan</label>
                                        </div>
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
    $(function() {
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: false,
            minDate: '{{ $minDate }}',
            maxDate: '{{ $maxDate }}',
        });

        $('select:not(.ms)').selectpicker();

    });
</script>