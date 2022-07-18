<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        TUNGGAKAN TAHUN LALU
                    </h2>
                </div>
                @include('keuangan/sim/spp/partials/header-card-menu')
            </div>
            <div class="card">
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Tahun Ajaran
                            </h2>
                            <select class="form-control show-tick" name="tahun_akademik_semester">
                            @foreach($data_semester as $semester)
                                <option value="{{$semester->thn_akademik_semester}}" 
                                    @if($semester->thn_akademik_semester == $tahun_akademik_semester)
                                        selected
                                    @endif>
                                {{$semester->tahun_ajaran}}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-btn-submit waves-effect" onclick="filterAction()"><i class="material-icons">save</i><span>Ubah Tahun Ajaran</span></button>
                        </div>
                    </div>
                    <style>
                        table tr th{
                            text-align: center;
                        }
                    </style>
                    @if($tutup_buku_tahunan)
                    <div class="table-responsive">
                        <h4>Total tunggakan tahun lalu: <b>Rp{{number_format($tutup_buku_tahunan->jml_tunggakan_biaya)}}</b></h4>
                        <h4>Sisa tunggakan tahun lalu: <b>Rp{{number_format($sisa_tunggakan)}}</b> (Otomatis akan berkurang saat ada pemasukan)</h4>
                        <table class="table table-bordered table-striped table-hover dataTable display nowrap" id="primary_table">
                            <thead>
                                <tr>
                                    <th>SISA TUNGGAKAN </th>
                                    <th>Jumlah Pemasukan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data_bulan as $bulan)
                                <tr>
                                    <td>Bulan {{$bulan->nm_bulan}}</td>
                                    <td>Rp {{
                                        number_format($data_pembayaran_tunggakan->filter(function ($item) use ($bulan) {
                                            return false !== stristr(date_format(date_create($item->tgl_pembayaran),"m"), $bulan->kode_bulan);
                                        })->sum('besar_pembayaran'))
                                        }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p>Mohon maaf data pada tahun ajaran ini tidak ditemukan</p>
                    @endif
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/sim/spp/tunggakan/save')}}">
                        {{csrf_field()}}
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <h4>Input tunggakan</h4>
                                <input type="hidden" name="tahun_akademik_semester" required="" value="{{$tahun_akademik_semester}}">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Tanggal Input</label>
                                        <input type="text" class="datepicker form-control" name="tgl_pembayaran" required="" value="{{\Carbon\Carbon::now()->format('Y-m-d')}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Keterangan</label>
                                        <textarea class="form-control" name="keterangan" rows="4" cols="100"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Nilai</label>
                                        <input type="number" class="form-control" name="besar_pembayaran" required="">
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

<script>
    function filterAction(){
        var tahun_akademik_semester = $('select[name=tahun_akademik_semester]').val();
        loadURI('sim/spp/tunggakan/'+tahun_akademik_semester);
}
</script>

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