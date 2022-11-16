<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Keuangan Pembayaran Siswa</title>

    <style>
        .page {
            width: 900px;
        }

        .ttd {
            margin-top: 30px;
            text-align: right;
        }

        .clear {
            clear: both;
        }

        .avoid-break {
            page-break-inside: avoid;
        }

        .mb-0 {
            margin-bottom: 0px;
        }

        .mb-05 {
            margin-bottom: 5px;
        }

        .mb-1 {
            margin-bottom: 10px;
        }

        .mb-2 {
            margin-bottom: 20px;
        }

        .mt-2 {
            margin-top: 20px;
        }

        .mt-4 {
            margin-top: 40px;
        }

        .mb-4 {
            margin-bottom: 40px;
        }
    </style>
</head>

<body>
    <div class="page">

        <table cellspacing="0" cellpadding="10" style="width: 100%;">
            <tr>
                <td colspan=1><img src="https://diakad.sgp1.digitaloceanspaces.com/{{$auth_data->sekolah_data->nm_singkat_sekolah}}/global/logo-sekolah" alt="Logo Sekolah" style="height:90px;" /></td>
                <td colspan=6><h1 align="center">LAPORAN PEMBAYARAN SISWA PER TINGKAT<br> {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}</h1></td>
            </tr>
        </table>
        <table>
            <tr>
            @if($start_date != $end_date)
                <td colspan="3"><b>TANGGAL   {{ strtoupper(indonesiaDate($start_date)) }} - {{ strtoupper(indonesiaDate($end_date)) }}</b></td>
            @else
                <td colspan="3"><b>TANGGAL   {{ strtoupper(indonesiaDate($start_date)) }}</b></td>
            @endif
            </tr>
        </table>
        <br>
        <table border="1" cellspacing="0" cellpadding="5" style="width: 100%; font-size:small" class="mb-2">
            <thead>
                <tr>
                    <th rowspan="2">Tanggal </th>
                    <th colspan="3">Bulan ini</th>
                    <th colspan="3">Tunggakan bulan lalu yang masuk bulan ini</th>
                    <th rowspan="2">Jumlah</th>
                    <th rowspan="2">Tahun Lalu Masuk</th>

                </tr>
                <tr>
                    @foreach($data_laporan['tingkat'] as $tingkat)
                    <th>{{$tingkat}}</th>
                    @endforeach
                    @foreach($data_laporan['tingkat'] as $tingkat)
                    <th>{{$tingkat}}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($data_laporan['dates'] as $date)
                @php
                    $id_bulan = $date->format('n');
                    $periode_bulan_sekolah = collect([7, 8, 9, 10, 11, 12, 1, 2, 3, 4, 5, 6]);

                    if ($id_bulan < 7) {
                        $index_splice = $id_bulan + 5;
                        $index_periode_bulan_ini = $periode_bulan_sekolah->splice($index_splice);
                        $index_periode_bulan_ini->all();

                        $where_bayar_bulan_ini_dan_kedepannya = $index_periode_bulan_ini;
                        $where_bayar_bulan_lalu_dan_belakangnya = $periode_bulan_sekolah;
                    } else if ($id_bulan == 7) {
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
                    <td>{{$date->format('Y-m-d')}}</td>
                    @foreach($data_laporan['tingkat'] as $tingkat)
                    <td>Rp {{ number_format($data_laporan['data']->where('tagihan_biaya.kelas.tingkat', $tingkat)->where('tagihan_biaya.detail_biaya.biaya_sekolah.semester.tahun_ajaran', $data_laporan['semester_aktif']->tahun_ajaran)->whereIn('tagihan_biaya.detail_biaya.id_bulan', $where_bayar_bulan_ini_dan_kedepannya)->filter(function ($item) use ($date) {
                            return false !== stristr($item->tgl_pembayaran, $date->format('Y-m-d'));
                        })->sum('besar_pembayaran')) }}</td>
                    @endforeach
                    @foreach($data_laporan['tingkat'] as $tingkat)
                    <td>Rp {{ number_format($data_laporan['data']->where('tagihan_biaya.kelas.tingkat', $tingkat)->where('tagihan_biaya.detail_biaya.biaya_sekolah.semester.tahun_ajaran', $data_laporan['semester_aktif']->tahun_ajaran)->whereIn('tagihan_biaya.detail_biaya.id_bulan', $where_bayar_bulan_lalu_dan_belakangnya)->filter(function ($item) use ($date) {
                            return false !== stristr($item->tgl_pembayaran, $date->format('Y-m-d'));
                        })->sum('besar_pembayaran')) }}</td>
                    @endforeach
                    <td>Rp {{ number_format($data_laporan['data']->where('tagihan_biaya.detail_biaya.biaya_sekolah.semester.tahun_ajaran', $data_laporan['semester_aktif']->tahun_ajaran)->filter(function ($item) use ($date) {
                            return false !== stristr($item->tgl_pembayaran, $date->format('Y-m-d'));
                        })->sum('besar_pembayaran')) }}</td>
                    <td>Rp {{ number_format(
                            $data_laporan['data_tunggakan']->filter(function ($item) use ($date) {
                                return false !== stristr($item->tgl_pembayaran, $date->format('Y-m-d'));
                            })->sum('besar_pembayaran')
                            + $data_laporan['data']->where('tagihan_biaya.detail_biaya.biaya_sekolah.semester.tahun_ajaran', '!=', $data_laporan['semester_aktif']->tahun_ajaran)->filter(function ($item) use ($date) {
                                return false !== stristr($item->tgl_pembayaran, $date->format('Y-m-d'));
                            })->sum('besar_pembayaran')
                        ) }}</td>
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
                    } else if ($id_bulan == 7) {
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
                    @foreach($data_laporan['tingkat'] as $tingkat)
                    <td>Rp {{ number_format($data_laporan['data']->where('tagihan_biaya.detail_biaya.biaya_sekolah.semester.tahun_ajaran', $data_laporan['semester_aktif']->tahun_ajaran)->where('tagihan_biaya.kelas.tingkat', $tingkat)->whereIn('tagihan_biaya.detail_biaya.id_bulan', $where_bayar_bulan_ini_dan_kedepannya)->sum('besar_pembayaran')) }}</td>
                    @endforeach
                    @foreach($data_laporan['tingkat'] as $tingkat)
                    <td>Rp {{ number_format($data_laporan['data']->where('tagihan_biaya.detail_biaya.biaya_sekolah.semester.tahun_ajaran', $data_laporan['semester_aktif']->tahun_ajaran)->where('tagihan_biaya.kelas.tingkat', $tingkat)->whereIn('tagihan_biaya.detail_biaya.id_bulan', $where_bayar_bulan_lalu_dan_belakangnya)->sum('besar_pembayaran')) }}</td>
                    @endforeach
                    <td>Rp {{ number_format($data_laporan['data']->where('tagihan_biaya.detail_biaya.biaya_sekolah.semester.tahun_ajaran', $data_laporan['semester_aktif']->tahun_ajaran)->sum('besar_pembayaran')) }}</td>
                    <td>Rp {{ number_format(
                        $data_laporan['data_tunggakan']->sum('besar_pembayaran') + $data_laporan['data']->where('tagihan_biaya.detail_biaya.biaya_sekolah.semester.tahun_ajaran', '!=', $data_laporan['semester_aktif']->tahun_ajaran)->sum('besar_pembayaran')
                        ) }}</td>
                </tr>
            </tbody>
        </table>
        <div class="avoid-break mt-4 mb-4">
            <table cellspacing="0" style="width: 80%; margin:auto; text-align:center">
                <tr>
                    <td style="width: 50%;">Mengetahui</td>
                    <td>{{ $auth_data->sekolah_data->alamat_kecamatan !== null ? $auth_data->sekolah_data->alamat_kecamatan . ', ' : null }}
                        {{ indonesiaDate(\Carbon\Carbon::now()->format('Y-m-d'))  }}
                    </td>
                </tr>
                <tr></tr>
                <tr style="vertical-align: top;">
                    <td>
                        Kepala Sekolah
                        <br><br><br><br>
                        <b><u>{{ $auth_data->sekolah_data->nm_kepala_sekolah }}</u></b>
                    </td>
                    <td>
                        Keuangan
                        <br><br><br><br>
                        <b><u>{{ $auth_data->pengguna->nm_pengguna }}</u></b>
                    </td>
                </tr>
            </table>
        </div>
        <div class="clear"></div>
    </div>
</body>
<script>
    window.print();
</script>
</html>
