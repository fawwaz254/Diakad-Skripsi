<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Pembayaran Siswa</title>

    <style>
        body {
            font-family: sans-serif;
        }

        .page {
            width: <?=$lebar ?>mm;
        }

        .ttd {
            margin-top: 30px;
            text-align: right;
            font-size: x-small;
        }

        .clear {
            clear: both;
        }
    </style>
    <style type="text/css" media="print">
        @page {
            size: <?=$lebar ?>mm;
        }
    </style>
</head>

<body>
    <div class="page">

        <table>
            <tr>
                <td colspan=1><img
                        src="https://diakad.sgp1.digitaloceanspaces.com/{{ $auth_data->sekolah_data->nm_singkat_sekolah }}/global/logo-sekolah"
                        alt="Logo Sekolah" style="height:50px;" /></td>
                <td colspan=6>
                    <h1 style="font-size: 10pt;" align="center">KWITANSI PEMBAYARAN<br>
                        {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}</h1>
                </td>
            </tr>
        </table>
        <table style="width: 100%; font-size:12px;font-weight: 700;">
            <tr>
                <td>Siswa</td>
                <td style="width: 5px;">:</td>
                <td>{{ $siswa->nm_pengguna }}</td>
            </tr>
            <tr>
                <td>Nomor Induk</td>
                <td>:</td>
                <td>{{ $siswa->nis_siswa }}</td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>:</td>
                <td>{{ $siswa->nm_kelas }}</td>
            </tr>
            <tr>
                <td>Waktu Pembayaran</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data_pembayaran_siswa->first()->tgl_pembayaran)->format('d M Y') }}
                    - {{ \Carbon\carbon::parse($data_pembayaran_siswa->first()->created_at)->format('H:i') }}</td>
            </tr>
        </table>
        <hr>
        <table border="0" cellspacing="0" cellpadding="5" style="width: 100%; font-size:12px;font-weight: 700;">
            <tr>
                <th>Nama Biaya</th>
                <th>Besar Pembayaran</th>
            </tr>
            @foreach ($data_pembayaran_siswa as $pembayaran_siswa)
                <tr>
                    @if ($pembayaran_siswa->id_jenis_detail_biaya == 4)
                        @if ($pembayaran_siswa->id_bulan <= 6)
                            @php $tahun = $pembayaran_siswa->thn_akademik_semester + 1; @endphp
                        @else
                            @php $tahun = $pembayaran_siswa->thn_akademik_semester; @endphp
                        @endif
                        <td>{{ $pembayaran_siswa->nm_biaya }} ({{ $pembayaran_siswa->nm_bulan }} {{ $tahun }})
                        </td>
                    @else
                        <td>{{ $pembayaran_siswa->nm_biaya . ' ' . $pembayaran_siswa->keterangan }}</td>
                    @endif
                    <td style="text-align: right;">{{ 'Rp ' . number_format($pembayaran_siswa->besar_pembayaran) }}
                        <br>
                        @if ($pembayaran_siswa->total_potongan)
                            <br>
                            <span style="color:red">-
                                {{ 'Rp ' . number_format($pembayaran_siswa->total_potongan) }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
        <hr>
        <table style="width: 100%; font-size:12px">
            <tr>
                <td align="center"><b>TOTAL</b></td>
                <td align="right"><b>{{ 'Rp ' . number_format($data_pembayaran_siswa->sum('besar_pembayaran')) }}</b>
                </td>
            </tr>
        </table>
        <div class="ttd" style="font-weight:700;font-size: 12px;">
            {{ $auth_data->sekolah_data->alamat_kecamatan !== null ? $auth_data->sekolah_data->alamat_kecamatan . ', ' : null }}
            {{ date_format(date_create($tgl_pembayaran), 'd M Y') }} <br><br><br><br>

            @if ($auth_data->sekolah_data->nm_singkat_sekolah != 'smamaryamsby')
                {{ $auth_data->pengguna->nm_pengguna }}
            @else
                Bendahara SMA Maryam
            @endif
        </div>
        <div class="clear"></div>
    </div>
</body>
<script>
    window.print();
</script>

</html>
