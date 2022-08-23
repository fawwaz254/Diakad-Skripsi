<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Cetak Laporan Keuangan</title>

</head>

<body>

    <div class="container">

        <table cellspacing="0" cellpadding="10" style="width: 100%;">
            <tr>
                <td colspan=1><img
                        src="https://diakad.sgp1.digitaloceanspaces.com/{{ $auth_data->sekolah_data->nm_singkat_sekolah }}/global/logo-sekolah"
                        alt="Logo Sekolah" style="height:90px;" /></td>
                <td colspan=6>
                    <h1 align="center">{{ $judul }}<br> {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}
                    </h1>
                </td>
            </tr>
        </table>

        <table>
            <tr>

                @if ($start_date != $end_date)
                    <td colspan="3"><b>TANGGAL
                            {{ indonesiaDate(\Carbon\Carbon::parse($start_date)->format('Y-m-d')) }} -
                            {{ indonesiaDate(\Carbon\Carbon::parse($end_date)->format('Y-m-d')) }}</b></td>
                @else
                    <td colspan="3"><b>TANGGAL
                            {{ indonesiaDate(\Carbon\Carbon::parse($start_date)->format('Y-m-d')) }}</b></td>
                @endif
            </tr>
        </table>
        <br>
        <table border="1" cellspacing="0" cellpadding="5" style="width: 100%; font-size:15px;">
            <tr>
                <th style="width: 10px;">No.</th>
                @if ($start_date != $end_date)
                    <th>Tanggal</th>
                @endif
                <th>Keterangan</th>
                <th>Debit</th>
                <th>Kredit</th>
            </tr>
            @foreach ($data_laporan['laporan'] as $key => $value)
                @php
                    $date = new DateTime($value['tanggal']);
                @endphp
                <tr>
                    <td>{{ $key + 1 }}</td>
                    @if ($start_date != $end_date)
                        <td>{{ indonesiaDate($value['tanggal']) }}</td>
                    @endif
                    <td>
                        {{ $value['keterangan'] }}
                        @if ($value['potongan'])
                            <br>
                            <small>Potongan : Rp {{ number_format($value['potongan'], 2) }}</small>
                        @endif
                    </td>
                    @if ($value['tipe'] == 1)
                        <td style="text-align: right;">Rp {{ number_format($value['nominal'], 2) }}</td>
                        <td></td>
                    @else
                        <td></td>
                        <td style="text-align: right;">Rp {{ number_format($value['nominal'], 2) }}</td>
                    @endif
                </tr>
            @endforeach
            <tr>
                @if ($start_date != $end_date)
                    <th colspan="3"></th>
                @else
                    <th colspan="2"></th>
                @endif
                <th>TOTAL DEBIT: <br>{{ 'Rp ' . number_format($data_laporan['total_debit'], 2) }}</th>
                <th>TOTAL KREDIT: <br>{{ 'Rp ' . number_format($data_laporan['total_kredit'], 2) }}</th>
            </tr>
        </table>

        @if ($sekolah == 'SMK PEMUDA KRIAN')
            <div style="margin-top:50px;">
                <table style="width:100%">
                    <tr>
                        <td style="width:30%"></td>
                        <td style="width:20%">Saldo Bulan Lalu</td>
                        <td>{{ 'Rp ' . number_format($saldo_before->kas_akhir_bulan, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="width:30%"></td>
                        <td style="width:20%">Penerimaan Bulan Ini</td>
                        <td style="text-decoration:underline;">
                            {{ 'Rp ' . number_format($data_laporan['total_debit'], 2) }}</td>
                    </tr>
                    <tr>
                        <td style="width:30%"></td>
                        <td style="width:20%"></td>
                        <td style="font-weight: 700;">
                            {{ 'Rp ' . number_format($saldo_before->kas_akhir_bulan + $data_laporan['total_debit'], 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td style="width:30%"></td>
                        <td style="width:20%">Pengeluaran Bulan Ini</td>
                        <td style="text-decoration:underline;">
                            {{ 'Rp ' . number_format($data_laporan['total_kredit'], 2) }}</td>
                    </tr>
                    <tr>
                        <td style="width:30%"></td>
                        <td style="width:20%">Saldo Akhir Bulan</td>
                        <td style="font-weight: 700;">
                            {{ 'Rp ' . number_format($saldo_before->kas_akhir_bulan + $data_laporan['total_debit'] - $data_laporan['total_kredit'], 2) }}
                        </td>
                    </tr>
                </table>
            </div>
        @endif

        <div class="row" style="margin-top:40px;">
            <div class="col-md-4">
                <p>Mengetahui<br>Kepala Sekolah<br><br><br><br>
                    <b><u>{{ $auth_data->sekolah_data->nm_kepala_sekolah }}</u></b>
                </p>
            </div>
            @if ($sekolah == 'SMK PEMUDA KRIAN')
                <div class="col-md-4">
                    <p>Bendahara PCM<br><br><br><br><br>
                        <b><u>Drs.ec.H.Nanang Abdul Hakim,ST</u></b>
                    </p>
                </div>
            @endif
            <div class="col-md-4 {{ $sekolah == 'SMK PEMUDA KRIAN' ? '' : 'offset-md-4' }}">
                <p>{{ $auth_data->sekolah_data->alamat_kecamatan !== null ? $auth_data->sekolah_data->alamat_kecamatan . ', ' : null }}
                    {{ indonesiaDate(\Carbon\Carbon::parse($end_date)->format('Y-m-d')) }}
                    {{-- {{ indonesiaDate(\Carbon\Carbon::now()->format('Y-m-d'))  }} --}}
                    <br>Keuangan<br><br><br><br>
                    <b><u>{{ $auth_data->pengguna->nm_pengguna }}, SE  </u></b>
                </p>
            </div>
        </div>

        <div class="clear"></div>
    </div>

</body>
<script>
    window.print();
</script>

</html>
