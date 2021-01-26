<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Rekapan Pembayaran Siswa</title>

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
    </style>
    <style type="text/css" media="print">
        @page {
            size: A4;
        }
    </style>
</head>

<body>
    <div class="page">
        
        <table cellspacing="0" cellpadding="10" style="width: 100%;">
            <tr>
                <td colspan=1><img src="https://diakad.sgp1.digitaloceanspaces.com/{{$auth_data->sekolah_data->nm_singkat_sekolah}}/global/logo-sekolah" alt="Logo Sekolah" style="height:90px;" /></td>
                <td colspan=6><h1 align="center">REKAPITULASI PEMBAYARAN SISWA<br> {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}</h1></td>
            </tr>
        </table>
        <table>
            <tr>
            @if($start_date != $end_date)
                <td colspan="3"><b>REKAPITULASI PEMBAYARAN <br><br>TANGGAL {{ \Carbon\Carbon::createFromFormat('Y-m-d', $start_date)->format('d M Y') }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d', $end_date)->format('d M Y') }}</b></td>
            @else
                <td colspan="3"><b>REKAPITULASI PEMBAYARAN <br><br>TANGGAL {{ \Carbon\Carbon::createFromFormat('Y-m-d', $start_date)->format('d M Y') }}</b></td>
            @endif
            </tr>
        </table>
        <br>
        <table border="1" cellspacing="0" cellpadding="10" style="width: 100%;">
            <tr>
                <th style="width: 10px;">No.</th>
                <th>NIS Siswa</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Tanggal Pembayaran</th>
                <th>Besar Pembayaran</th>
            </tr>
            @foreach($data_pembayaran as $key => $siswa)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $siswa->nis_siswa }}</td>
                <td>{{ $siswa->nm_pengguna }}</td>
                <td>{{ $siswa->nm_kelas }}</td>
                <td>{{ $siswa->tgl_pembayaran }}</td>
                <td style="text-align: right;">Rp {{ number_format($siswa->total_pembayaran) }}</td>
            </tr>
            @endforeach
            <tr>
                <th colspan="5">TOTAL</th>
                <th>{{ "Rp " . number_format(collect($data_pembayaran)->sum('total_pembayaran')) }}</th>
            </tr>
        </table>
        <div class="ttd avoid-break">
            {{ $auth_data->sekolah_data->alamat_kecamatan !== null ? $auth_data->sekolah_data->alamat_kecamatan . ', ' : null }} {{ \Carbon\Carbon::now()->format('j M Y') }} <br><br><br><br> {{ $auth_data->pengguna->nm_pengguna }}
        </div>
        <div class="clear"></div>
    </div>
</body>
<script>
    window.print();
</script>
</html>