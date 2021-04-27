<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Keuangan Pembayaran Siswa</title>

    <style>
        .page {
            width: 1200px;
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
    <style type="text/css" media="print">
        @page {
            size: landscape;
        }
    </style>
</head>

<body>
    <div class="page">
        
        <table cellspacing="0" cellpadding="10" style="width: 100%;">
            <tr>
                <td colspan=1><img src="https://diakad.sgp1.digitaloceanspaces.com/{{$auth_data->sekolah_data->nm_singkat_sekolah}}/global/logo-sekolah" alt="Logo Sekolah" style="height:90px;" /></td>
                <td colspan=6><h1 align="center">LAPORAN PEMBAYARAN SISWA PER BULAN<br> {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}</h1></td>
            </tr>
        </table>
        <table>
            <tr>
            @if($start_year != $end_year)
                <td><b>TAHUN   {{ $start_year }} - {{ $end_year }}</b></td>
            @else
                <td><b>TAHUN   {{ $start_year }}</b></td>
            @endif
            </tr>
        </table>
        <br>
        <table border="1" cellspacing="0" cellpadding="5" style="width: 100%; font-size:small" class="mb-2">
            <tr>
                <th style="width: 10px;">No.</th>
                @if($start_year != $end_year)
                    <th>TAHUN</th>
                @endif
                <th>BULAN</th>
                @if(isset($data_laporan['kategori_biaya']))
                    @foreach($data_laporan['kategori_biaya'] as $biaya)
                        <th style="width: 10%;">{{ strtoupper($biaya) }}</th>
                    @endforeach
                @endif
                <th>JUMLAH</th>
            </tr>
            @php 
                $no = 1;
                $year_count = 1;
            @endphp
            @if(isset($data_laporan['data']))
                @foreach($data_laporan['data'] as $data)
                <tr>
                    <td>{{ $no++ }}</td>
                    @if($start_year != $end_year)
                        @if(($no-2) % 12 == 0)
                        <td rowspan="12" style="text-align: center;">{{ $data['tahun'] }}</td>
                        @endif
                    @endif
                    <td>{{ $data['bulan'] }}</td>
                    @if(isset($data_laporan['kategori_biaya']))
                        @foreach($data_laporan['kategori_biaya'] as $idBiaya => $biaya)
                            <td style="text-align: right;">{{ 
                                collect($data['details'])->where('id_biaya', $idBiaya)->first() ? 'Rp ' . number_format(collect($data['details'])->where('id_biaya', $idBiaya)->first()['total_pembayaran']) : '-' 
                            }}</td>
                        @endforeach
                    @endif
                    <td style="text-align: right;"><b>{{ 'Rp ' . number_format($data['total_pembayaran']) }}</b></td>
                </tr>
                @endforeach
            @endif
            <tr>
                @if($start_year != $end_year)
                <th colspan="{{ count($data_laporan['kategori_biaya']) + 3 }}">Total</th>
                @else
                <th colspan="{{ count($data_laporan['kategori_biaya']) + 2 }}">Total</th>
                @endif
                <th style="text-align: right;">{{ 'Rp ' . number_format(collect($data_laporan['data'])->sum('total_pembayaran')) }}</th>
            </tr>
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