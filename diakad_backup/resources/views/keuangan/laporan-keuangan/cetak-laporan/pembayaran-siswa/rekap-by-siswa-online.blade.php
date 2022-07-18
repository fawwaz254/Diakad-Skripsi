<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Keuangan Pembayaran Siswa Online</title>

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
                <td colspan=6><h1 align="center">LAPORAN PEMBAYARAN SISWA (ONLINE) PER SISWA<br> {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}</h1></td>
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
            <tr>
                <th style="width: 10px;">No.</th>
                <th>Nama</th>
                <th>Kelas</th>
                @if(isset($data_laporan['kategori_biaya']))
                @foreach($data_laporan['kategori_biaya'] as $biaya)
                    <th style="width: 10%;">{{ strtoupper($biaya) }}</th>
                @endforeach
                @endif
                <th>Potongan</th>
                <th>Jumlah</th>
            </tr>

            @if(isset($data_laporan['data']))
            @foreach($data_laporan['data'] as $siswa)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $siswa['nis_siswa'] . ' - ' . $siswa['nm_siswa'] }}</td>
                <td>{{ $siswa['kelas_siswa'] }}</td>
                @if(isset($data_laporan['kategori_biaya']))
                @foreach($data_laporan['kategori_biaya'] as $idBiaya => $biaya)
                    @php
                        $payment = collect($siswa['summary'])->where('id_biaya', $idBiaya)->first();
                        $discount = collect($siswa['summary'])->where('id_biaya', $idBiaya)->first(); 
                    @endphp
                    <td>
                        {{ $payment ? 'Rp ' . number_format($payment['total_nominal_pembayaran']) : '-' }}
                        @if($discount)
                        @if($discount['total_potongan_biaya'])
                        <br>
                        <small>Potongan : Rp {{ number_format($discount['total_potongan_biaya']) }}</small>
                        @endif
                        @endif
                    </td>
                @endforeach
                @endif
                <td>{{ 'Rp ' . number_format($siswa['potongan_biaya']) }}</td>
                <td><b>{{ 'Rp ' . number_format(collect($siswa['summary'])->sum('total_nominal_pembayaran')) }}</b></td>
            </tr>
            @endforeach
            @endif
        </table>

        @if(isset($data_laporan['summary']))
        <table border="1" cellspacing="0" cellpadding="5" style="width: 100%; font-size:small" class="mb-2">
            <tr>
                <th colspan="3">Ringkasan</th>
            </tr>
            @foreach($data_laporan['summary'] as $summ)
            <tr>
                <th style="text-align: left;">{{ strtoupper($summ['nm_biaya']) }}</th>
                <td>{{ $summ['frekuensi'] . ' x' }}</td>
                <th style="text-align: right;">{{ 'Rp ' . number_format($summ['total_pembayaran']) }}</th>
            </tr>
            @endforeach
            <tr>
                <th colspan="2">TOTAL POTONGAN</th>
                <th style="text-align: right;">{{ 'Rp '. number_format(collect($data_laporan['summary'])->sum('total_potongan_biaya')) }}</th>
            </tr>
            <tr>
                <th colspan="2">TOTAL PEMBAYARAN</th>
                <th style="text-align: right;">{{ 'Rp ' . number_format(collect($data_laporan['summary'])->sum('total_pembayaran')) }}</th>
            </tr>
        </table>
        @endif
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