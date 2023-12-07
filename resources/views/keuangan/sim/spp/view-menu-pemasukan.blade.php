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
                                Tahun
                            </h2>
                            <select class="form-control show-tick" name="tahun_akademik_semester">
                                @foreach ($data_semester as $semester)
                                    <option value="{{ $semester->thn_akademik_semester }}"
                                        @if ($semester->thn_akademik_semester == $tahun_akademik_semester) selected @endif>
                                        {{ $semester->thn_akademik_semester }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Bulan
                            </h2>
                            <select class="form-control show-tick" name="bulan">
                                @foreach ($data_bulan as $bulan)
                                    <option value="{{ $bulan->id_bulan }}"
                                        @if ($bulan->id_bulan == $id_bulan) selected @endif>{{ $bulan->nm_bulan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-btn-submit waves-effect" onclick="filterAction()"><i
                                    class="material-icons">save</i><span>Ubah Tahun Ajaran/Bulan</span></button>
                        </div>
                    </div>
                    <style>
                        table tr th {
                            text-align: center;
                        }
                    </style>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th rowspan="2">Tanggal </th>
                                    <th colspan="3">Bulan ini</th>
                                    <th colspan="3">Tunggakan bulan lalu yang masuk bulan ini</th>
                                    <th rowspan="2">Jumlah</th>
                                    <th rowspan="2">Tahun Lalu Masuk</th>

                                </tr>
                                <tr>
                                    @foreach ($data_laporan['tingkat'] as $tingkat)
                                        <th>{{ $tingkat }}</th>
                                    @endforeach
                                    @foreach ($data_laporan['tingkat'] as $tingkat)
                                        <th>{{ $tingkat }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_laporan['dates'] as $date)
                                    @php
                                        $id_bulan = $date->format('n');
                                        $periode_bulan_sekolah = collect([7, 8, 9, 10, 11, 12, 1, 2, 3, 4, 5, 6]);

                                        if ($id_bulan < 7) {
                                            $index_splice = $id_bulan + 5;
                                            $index_periode_bulan_ini = $periode_bulan_sekolah->splice($index_splice);
                                            $index_periode_bulan_ini->all();

                                            $where_bayar_bulan_ini_dan_kedepannya = $index_periode_bulan_ini;
                                            $where_bayar_bulan_lalu_dan_belakangnya = $periode_bulan_sekolah;
                                        } elseif ($id_bulan == 7) {
                                            $where_bayar_bulan_ini_dan_kedepannya = [7];
                                            $where_bayar_bulan_lalu_dan_belakangnya = [];
                                        } else {
                                            $index_splice = $id_bulan - 7;
                                            $index_periode_bulan_ini = $periode_bulan_sekolah->splice($index_splice);
                                            $index_periode_bulan_ini->all();

                                            $where_bayar_bulan_ini_dan_kedepannya = $index_periode_bulan_ini;
                                            $where_bayar_bulan_lalu_dan_belakangnya = $periode_bulan_sekolah;
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $date->format('Y-m-d') }}</td>
                                        @foreach ($data_laporan['tingkat'] as $tingkat)
                                            <td>
                                                <div id="{{ $tingkat . '-' . '1' . '-' . $date->format('Y-m-d') }}">
                                                    Loading
                                                </div>
                                                {{-- Rp
                                                {{ number_format(
                                                    $data_laporan['data']->where('tagihan_biaya.kelas.tingkat', $tingkat)->where(
                                                            'tagihan_biaya.detail_biaya.biaya_sekolah.semester.tahun_ajaran',
                                                            $data_laporan['semester_aktif']->tahun_ajaran,
                                                        )->whereIn('tagihan_biaya.detail_biaya.id_bulan', $where_bayar_bulan_ini_dan_kedepannya)->filter(function ($item) use ($date) {
                                                            return false !== stristr($item->tgl_pembayaran, $date->format('Y-m-d'));
                                                        })->sum('besar_pembayaran'),
                                                ) }} --}}
                                            </td>
                                        @endforeach
                                        @foreach ($data_laporan['tingkat'] as $tingkat)
                                            <td>
                                                <div id="{{ $tingkat . '-' . '2' . '-' . $date->format('Y-m-d') }}">
                                                    Loading
                                                </div>

                                                {{-- Rp
                                                {{ number_format(
                                                    $data_laporan['data']->where('tagihan_biaya.kelas.tingkat', $tingkat)->where(
                                                            'tagihan_biaya.detail_biaya.biaya_sekolah.semester.tahun_ajaran',
                                                            $data_laporan['semester_aktif']->tahun_ajaran,
                                                        )->whereIn('tagihan_biaya.detail_biaya.id_bulan', $where_bayar_bulan_lalu_dan_belakangnya)->filter(function ($item) use ($date) {
                                                            return false !== stristr($item->tgl_pembayaran, $date->format('Y-m-d'));
                                                        })->sum('besar_pembayaran'),
                                                ) }} --}}
                                            </td>
                                        @endforeach
                                        <td>
                                            <div id="{{ 'jumlah-' . $date->format('Y-m-d') }}">
                                                Loading
                                            </div>
                                            {{-- Rp
                                            {{ number_format(
                                                $data_laporan['data']->where(
                                                        'tagihan_biaya.detail_biaya.biaya_sekolah.semester.tahun_ajaran',
                                                        $data_laporan['semester_aktif']->tahun_ajaran,
                                                    )->filter(function ($item) use ($date) {
                                                        return false !== stristr($item->tgl_pembayaran, $date->format('Y-m-d'));
                                                    })->sum('besar_pembayaran'),
                                            ) }} --}}
                                        </td>
                                        <td>
                                            <div id="{{ 'tahun-lalu-masuk-' . $date->format('Y-m-d') }}">
                                                Loading
                                            </div>

                                            {{-- Rp
                                            {{ number_format(
                                                $data_laporan['data_tunggakan']->filter(function ($item) use ($date) {
                                                        return false !== stristr($item->tgl_pembayaran, $date->format('Y-m-d'));
                                                    })->sum('besar_pembayaran') +
                                                    $data_laporan['data']->where(
                                                            'tagihan_biaya.detail_biaya.biaya_sekolah.semester.tahun_ajaran',
                                                            '!=',
                                                            $data_laporan['semester_aktif']->tahun_ajaran,
                                                        )->filter(function ($item) use ($date) {
                                                            return false !== stristr($item->tgl_pembayaran, $date->format('Y-m-d'));
                                                        })->sum('besar_pembayaran'),
                                            ) }} --}}
                                        </td>
                                    </tr>
                                @endforeach
                                @php
                                    $id_bulan = $date->format('n');
                                    $periode_bulan_sekolah = collect([7, 8, 9, 10, 11, 12, 1, 2, 3, 4, 5, 6]);

                                    if ($id_bulan < 7) {
                                        $index_splice = $id_bulan + 5;
                                        $index_periode_bulan_ini = $periode_bulan_sekolah->splice($index_splice);
                                        $index_periode_bulan_ini->all();

                                        $where_bayar_bulan_ini_dan_kedepannya = $index_periode_bulan_ini;
                                        $where_bayar_bulan_lalu_dan_belakangnya = $periode_bulan_sekolah;
                                    } elseif ($id_bulan == 7) {
                                        $where_bayar_bulan_ini_dan_kedepannya = [7];
                                        $where_bayar_bulan_lalu_dan_belakangnya = [];
                                    } else {
                                        $index_splice = $id_bulan - 7;
                                        $index_periode_bulan_ini = $periode_bulan_sekolah->splice($index_splice);
                                        $index_periode_bulan_ini->all();

                                        $where_bayar_bulan_ini_dan_kedepannya = $index_periode_bulan_ini;
                                        $where_bayar_bulan_lalu_dan_belakangnya = $periode_bulan_sekolah;
                                    }
                                @endphp
                                <tr>
                                    <td>TOTAL</td>
                                    @foreach ($data_laporan['tingkat'] as $tingkat)
                                        <td>
                                            <div id="{{ 'total-' . $tingkat . '-' . '1' }}">
                                                Loading
                                            </div>
                                            {{-- Rp
                                            {{ number_format($data_laporan['data']->where('tagihan_biaya.detail_biaya.biaya_sekolah.semester.tahun_ajaran', $data_laporan['semester_aktif']->tahun_ajaran)->where('tagihan_biaya.kelas.tingkat', $tingkat)->whereIn('tagihan_biaya.detail_biaya.id_bulan', $where_bayar_bulan_ini_dan_kedepannya)->sum('besar_pembayaran')) }} --}}
                                        </td>
                                    @endforeach
                                    @foreach ($data_laporan['tingkat'] as $tingkat)
                                        <td>
                                            <div id="{{ 'total-' . $tingkat . '-' . '2' }}">
                                                Loading
                                            </div>
                                            {{-- Rp
                                            {{ number_format($data_laporan['data']->where('tagihan_biaya.detail_biaya.biaya_sekolah.semester.tahun_ajaran', $data_laporan['semester_aktif']->tahun_ajaran)->where('tagihan_biaya.kelas.tingkat', $tingkat)->whereIn('tagihan_biaya.detail_biaya.id_bulan', $where_bayar_bulan_lalu_dan_belakangnya)->sum('besar_pembayaran')) }} --}}
                                        </td>
                                    @endforeach
                                    <td>
                                        <b>
                                            <div id="{{ 'total-jumlah' }}">
                                                Loading
                                            </div>
                                        </b>
                                        {{-- Rp
                                        {{ number_format($data_laporan['data']->where('tagihan_biaya.detail_biaya.biaya_sekolah.semester.tahun_ajaran', $data_laporan['semester_aktif']->tahun_ajaran)->sum('besar_pembayaran')) }} --}}
                                    </td>
                                    <td>
                                        <div id="{{ 'total-tahun-lalu-masuk' }}">
                                            Loading
                                        </div>
                                        {{-- Rp
                                        {{ number_format(
                                            $data_laporan['data_tunggakan']->sum('besar_pembayaran') +
                                                $data_laporan['data']->where(
                                                        'tagihan_biaya.detail_biaya.biaya_sekolah.semester.tahun_ajaran',
                                                        '!=',
                                                        $data_laporan['semester_aktif']->tahun_ajaran,
                                                    )->sum('besar_pembayaran'),
                                        ) }} --}}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function filterAction() {
        var bulan = $('select[name=bulan]').val();
        var tahun_akademik_semester = $('select[name=tahun_akademik_semester]').val();
        loadURI('sim/spp/pemasukan/' + tahun_akademik_semester + '/' + bulan);
    }

    function refreshAction(element) {
        var item = $(element);
        $('button').attr('disabled', 'disabled');
        $.ajax({
            type: "GET",
            url: "{{ url('keuangan/sim/spp/pemasukan/' . $tahun_akademik_semester . '/' . $id_bulan . '/refresh') }}",
            success: function(response) {
                vex.dialog.alert(response.message);
            },
            complete: function() {
                $('button').removeAttr('disabled', 'disabled');
            }
        });
    }

    $(document).ready(function() {
        getBulanIni();
    });


    function getBulanIni() {
        $.ajax({
            type: "POST",
            url: `{{ Request::segment(1) }}/{{ Request::segment(2) }}/{{ Request::segment(3) }}/get-pemasukan`,
            data: {
                start_date: '{{ $start_date }}',
                end_date: '{{ $end_date }}',
                status: '1'
            },
            success: function(response) {
                $.each(response, function(key, item) {
                    $('#' + key).html(item);
                });
                getBulanLalu();
            }
        });
    }


    function getBulanLalu() {
        $.ajax({
            type: "POST",
            url: `{{ Request::segment(1) }}/{{ Request::segment(2) }}/{{ Request::segment(3) }}/get-pemasukan`,
            data: {
                start_date: '{{ $start_date }}',
                end_date: '{{ $end_date }}',
                status: '2'
            },
            success: function(response) {
                $.each(response, function(key, item) {
                    $('#' + key).html(item);
                });
            }
        });
    }
</script>
