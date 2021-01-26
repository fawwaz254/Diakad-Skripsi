<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak tagihan siswa yang belum terbayar untuk kelas {{ $kelas_data->nm_kelas }}</title>

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
                <td colspan=6><h1 align="center">TAGIHAN PEMBAYARAN KELAS {{ $kelas_data->nm_kelas }}<br> {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}</h1></td>
            </tr>
        </table>
        @foreach($all_data as $siswa)
        <br><br>
        <table cellspacing="0" cellpadding="10" style="width: 100%; text-align: left;">
            <tr>
                <td colspan="3"><b>TAGIHAN PER TANGGAL {{ \Carbon\Carbon::now()->format('j M Y') }}</b></td>
            </tr>
            <tr>
                <th style="width: 200px;">Siswa</th>
                <th style="width: 5px;">:</th>
                <th>{{ $siswa->pengguna->nm_pengguna }}</th>
            </tr>
            <tr>
                <th>Kelas</th>
                <th>:</th>
                <th>{{ $siswa->kelas->nm_kelas }}</th>
            </tr>
            <tr>
                <th>Nomor Induk</th>
                <th>:</th>
                <th>{{ $siswa->nis_siswa }}</th>
            </tr>
        </table>
        <table border="1" cellspacing="0" cellpadding="10" style="width: 100%;">
            <tr>
                <th style="width: 10px;">No.</th>
                <th>Nama Biaya</th>
                <th>Besar Tagihan</th>
            </tr>
            @php
                $no = 1;
            @endphp
            @foreach($siswa->tagihan as $tagihan)
            <tr>
                <td>{{$no++}}.</td>
                <td>{{ $tagihan['judul'] }}</td>
                <td>{{ $tagihan['biaya'] }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="2" align="center"><b>TOTAL</b></td>
                <td align="center"><b>{{"Rp " . number_format($siswa->tagihan_biaya->sum('besar_biaya'))}}</b></td>
            </tr>
        </table>
        @endforeach
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