<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        PEMASUKAN SPP
                    </h2>
                </div>
                @include('keuangan/sim/spp/partials/header-card-menu')
            </div>
            <div class="card">
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-6 col-sm-12 col-xs-12">
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
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Bulan
                            </h2>
                            <select class="form-control show-tick" name="bulan">
                            @foreach($data_bulan as $bulan)
                                <option value="{{$bulan->id_bulan}}" 
                                    @if($bulan->id_bulan == $id_bulan)
                                        selected
                                    @endif
                                >{{$bulan->nm_bulan}}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-btn-submit waves-effect" onclick="filterAction()"><i class="material-icons">save</i><span>Ubah Tahun Ajaran/Bulan</span></button>
                        </div>
                    </div>
                    <style>
                        table tr th{
                            text-align: center;
                        }
                    </style>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display nowrap" id="primary_table">
                            <thead>
                                <tr>
                                    <th rowspan="2">Tanggal </th>
                                    <th colspan="3">Bulan ini</th>
                                    <th colspan="3">Tunggakan bulan lalu yang masuk bulan ini</th>
                                    <th rowspan="2">Jumlah</th>
                                    
                                </tr>
                                <tr>
                                    <th>I</th>
                                    <th>II</th>
                                    <th>III</th>
                                    <th>I</th>
                                    <th>II</th>
                                    <th>III</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dates as $date)
                                <tr>
                                    <td>{{$date->format('Y-m-d')}}</td>
                                    <td>Rp {{
                                        number_format($data_pemasukan_bulan_ini->whereIn('tagihan_biaya.kelas.tingkat', [1, 7, 10])->where('tagihan_biaya.detail_biaya.id_bulan', '>=', $id_bulan)->filter(function ($item) use ($date) {
                                            return false !== stristr($item->tgl_pembayaran, $date->format('Y-m-d'));
                                        })->sum('besar_pembayaran'))
                                        }}</td>
                                    <td>Rp {{
                                        number_format($data_pemasukan_bulan_ini->whereIn('tagihan_biaya.kelas.tingkat', [2, 8, 11])->where('tagihan_biaya.detail_biaya.id_bulan', '>=', $id_bulan)->filter(function ($item) use ($date) {
                                            return false !== stristr($item->tgl_pembayaran, $date->format('Y-m-d'));
                                        })->sum('besar_pembayaran'))
                                        }}</td>
                                    <td>Rp {{
                                        number_format($data_pemasukan_bulan_ini->whereIn('tagihan_biaya.kelas.tingkat', [3, 9, 12])->where('tagihan_biaya.detail_biaya.id_bulan', '>=', $id_bulan)->filter(function ($item) use ($date) {
                                            return false !== stristr($item->tgl_pembayaran, $date->format('Y-m-d'));
                                        })->sum('besar_pembayaran'))
                                        }}</td>
                                    <td>Rp {{
                                        number_format($data_pemasukan_bulan_ini->whereIn('tagihan_biaya.kelas.tingkat', [1, 7, 10])->where('tagihan_biaya.detail_biaya.id_bulan', '<', $id_bulan)->filter(function ($item) use ($date) {
                                            return false !== stristr($item->tgl_pembayaran, $date->format('Y-m-d'));
                                        })->sum('besar_pembayaran'))
                                        }}</td>
                                    <td>Rp {{
                                        number_format($data_pemasukan_bulan_ini->whereIn('tagihan_biaya.kelas.tingkat', [2, 8, 11])->where('tagihan_biaya.detail_biaya.id_bulan', '<', $id_bulan)->filter(function ($item) use ($date) {
                                            return false !== stristr($item->tgl_pembayaran, $date->format('Y-m-d'));
                                        })->sum('besar_pembayaran'))
                                        }}</td>
                                    <td>Rp {{
                                        number_format($data_pemasukan_bulan_ini->whereIn('tagihan_biaya.kelas.tingkat', [3, 9, 12])->where('tagihan_biaya.detail_biaya.id_bulan', '<', $id_bulan)->filter(function ($item) use ($date) {
                                            return false !== stristr($item->tgl_pembayaran, $date->format('Y-m-d'));
                                        })->sum('besar_pembayaran'))
                                        }}</td>
                                    <td>Rp {{
                                        number_format($data_pemasukan_bulan_ini->filter(function ($item) use ($date) {
                                            return false !== stristr($item->tgl_pembayaran, $date->format('Y-m-d'));
                                        })->sum('besar_pembayaran'))
                                        }}</td>
                                </tr>
                                <tr>
                                @endforeach
                                <td>TOTAL</td>
                                <td>Rp {{
                                    number_format($data_pemasukan_bulan_ini->whereIn('tagihan_biaya.kelas.tingkat', [1, 7, 10])->where('tagihan_biaya.detail_biaya.id_bulan', '>=', $id_bulan)->sum('besar_pembayaran'))
                                    }}</td>
                                <td>Rp {{
                                    number_format($data_pemasukan_bulan_ini->whereIn('tagihan_biaya.kelas.tingkat', [2, 8, 11])->where('tagihan_biaya.detail_biaya.id_bulan', '>=', $id_bulan)->sum('besar_pembayaran'))
                                    }}</td>
                                <td>Rp {{
                                    number_format($data_pemasukan_bulan_ini->whereIn('tagihan_biaya.kelas.tingkat', [3, 9, 12])->where('tagihan_biaya.detail_biaya.id_bulan', '>=', $id_bulan)->sum('besar_pembayaran'))
                                    }}</td>
                                <td>Rp {{
                                    number_format($data_pemasukan_bulan_ini->whereIn('tagihan_biaya.kelas.tingkat', [1, 7, 10])->where('tagihan_biaya.detail_biaya.id_bulan', '<', $id_bulan)->sum('besar_pembayaran'))
                                    }}</td>
                                <td>Rp {{
                                    number_format($data_pemasukan_bulan_ini->whereIn('tagihan_biaya.kelas.tingkat', [2, 8, 11])->where('tagihan_biaya.detail_biaya.id_bulan', '<', $id_bulan)->sum('besar_pembayaran'))
                                    }}</td>
                                <td>Rp {{
                                    number_format($data_pemasukan_bulan_ini->whereIn('tagihan_biaya.kelas.tingkat', [3, 9, 12])->where('tagihan_biaya.detail_biaya.id_bulan', '<', $id_bulan)->sum('besar_pembayaran'))
                                    }}</td>
                                <td>Rp {{
                                    number_format($data_pemasukan_bulan_ini->sum('besar_pembayaran'))
                                    }}</td>
                                    
                                {{-- @foreach($data_laporan['tingkat'] as $tingkat)
                                <td>Rp {{ number_format($data_laporan['data']->where('tagihan_biaya.kelas.tingkat', $tingkat)->where('tagihan_biaya.detail_biaya.id_bulan', '>=', $date->format('n'))->sum('besar_pembayaran')) }}</td>
                                @endforeach
                                @foreach($data_laporan['tingkat'] as $tingkat)
                                <td>Rp {{ number_format($data_laporan['data']->where('tagihan_biaya.kelas.tingkat', $tingkat)->where('tagihan_biaya.detail_biaya.id_bulan', '<', $date->format('n'))->sum('besar_pembayaran')) }}</td>
                                @endforeach
                                <td>Rp {{ number_format($data_laporan['data']->sum('besar_pembayaran')) }}</td>
                                <td>Rp {{ number_format($data_laporan['data_tunggakan']->sum('besar_pembayaran')) }}</td> --}}
                            </tr>
                            </tbody>
                        </table>
                        <!-- <div class="row clearfix">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <a class="btn btn-block bg-btn-submit waves-effect" onclick="refreshAction(this)">Refresh</span></a>
                                <a class="btn btn-block bg-btn-submit waves-effect" target="_blank" href="{{url('keuangan/sim/spp/pemasukan/'.$tahun_akademik_semester.'/'.$id_bulan.'/report')}}">Download Laporan Bulanan</span></a>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function filterAction(){
        var bulan = $('select[name=bulan]').val();
        var tahun_akademik_semester = $('select[name=tahun_akademik_semester]').val();
        loadURI('sim/spp/pemasukan/'+tahun_akademik_semester+'/'+bulan);
    }

    function refreshAction(element){
        var item = $(element);
        $('button').attr('disabled', 'disabled');
        $.ajax({
            type: "GET",
            url: "{{url('keuangan/sim/spp/pemasukan/'.$tahun_akademik_semester.'/'.$id_bulan.'/refresh')}}",
            success: function (response) {
                vex.dialog.alert(response.message);
            },
            complete: function() {
                $('button').removeAttr('disabled', 'disabled');
            }
        });
    }
</script>