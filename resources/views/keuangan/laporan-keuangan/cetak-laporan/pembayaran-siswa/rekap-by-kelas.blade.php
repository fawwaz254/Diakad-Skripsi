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
            /* margin: 125mm 125mm 125mm 125mm;    */
            size: portrait;
           
        }
    </style>
</head>

<body>
    <div class="page">
        <table cellspacing="0" cellpadding="10" style="width: 90%;   margin: 0 auto;">
            <tr>
                <td colspan=1><img src="https://diakad.sgp1.digitaloceanspaces.com/{{$auth_data->sekolah_data->nm_singkat_sekolah}}/global/logo-sekolah" alt="Logo Sekolah" style="height:90px;" /></td>
                <td colspan=6><h1 align="center">LAPORAN PEMBAYARAN SISWA PER KELAS<br> {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}</h1></td>
            </tr>
        </table>
        <table style="margin-left: 56px"> 
            <tr>
            @if($start_date != $end_date)
                <td colspan="3"><b>TANGGAL   {{ strtoupper(indonesiaDate($start_date)) }} - {{ strtoupper(indonesiaDate($end_date)) }}</b></td>
            @else
                <td colspan="3"><b>TANGGAL   {{ strtoupper(indonesiaDate($start_date)) }}</b></td>
            @endif
            </tr>
        </table>
        <br>
        <table border="1" cellspacing="0" cellpadding="5" style="width: 90%; font-size:small;   margin: 0 auto;" class="mb-2" >
            <tr>
                <th style="width: 10px; background-color: rgb(210, 210, 210)">No.</th>
                <th style="background-color: rgb(210, 210, 210)">Kelas</th>
                @if(isset($data_laporan['jenis_bayar']))
                @foreach($data_laporan['jenis_bayar'] as $jenis => $laporan_jenis_bayar)
                <th style="background-color: rgb(210, 210, 210)">{{$jenis}}</th>
                @endforeach
                @endif
                <th style="background-color: rgb(210, 210, 210)">Total Frekuensi</th>
                <th style="background-color: rgb(210, 210, 210)">Potongan (*Apabila ada)</th>
                <th style="background-color: rgb(210, 210, 210)">Jumlah Pembayaran</th>
            </tr>
        @php 
            $no = 1;
        @endphp
        @if(isset($data_laporan['data']))
            @foreach($data_laporan['data']->groupBy('tagihan_biaya.kelas.nm_kelas') as $kelas => $laporan)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $kelas }}</td>
                @if(isset($data_laporan['jenis_bayar']))
                @foreach($data_laporan['jenis_bayar'] as $jenis => $laporan_jenis_bayar)
                @if($laporan_jenis_bayar->where('tagihan_biaya.kelas.nm_kelas', $kelas)->count('id_pembayaran_siswa') > 0)
                <td style="text-align: right;">{{ $laporan_jenis_bayar->where('tagihan_biaya.kelas.nm_kelas', $kelas)->count('id_pembayaran_siswa') }}x</td>
                @else
                <td style="text-align: right;">-</td>
                @endif
                @endforeach
                @endif
                <td style="text-align: right;">{{ $laporan->count('id_pembayaran_siswa') }}x</td>
                <td style="text-align: right;">{{ 'Rp ' . number_format($laporan->sum('tagihan_biaya.potongan.total_potongan')) }}</td>
                <td style="text-align: right;">{{ 'Rp ' . number_format($laporan->sum('besar_pembayaran')) }}</td>
            </tr>
            @endforeach
            <tr>
                <th colspan="2" style="text-align: right;">TOTAL</th>
                @if(isset($data_laporan['jenis_bayar']))
                @foreach($data_laporan['jenis_bayar'] as $jenis => $laporan_jenis_bayar)
                <th style="text-align: right;">{{ $laporan_jenis_bayar->count('id_pembayaran_siswa') }}x</th>
                @endforeach
                @endif
                <th style="text-align: right;">{{ $data_laporan['data']->count('id_pembayaran_siswa') . ' x' }}</th>
                <th style="text-align: right;">{{ 'Rp ' . number_format($data_laporan['data']->sum('tagihan_biaya.potongan.total_potongan')) }}</th>
                <th style="text-align: right;">{{ 'Rp ' . number_format($data_laporan['data']->sum('besar_pembayaran')) }}</th>
            </tr>
        @endif
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
                        <br><br><br><br><br><br>
                        <b><u>{{ $auth_data->sekolah_data->nm_kepala_sekolah }}</u></b>
                    </td>
                    <td>
                        Keuangan
                        <br><br><br><br> <br><br>
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