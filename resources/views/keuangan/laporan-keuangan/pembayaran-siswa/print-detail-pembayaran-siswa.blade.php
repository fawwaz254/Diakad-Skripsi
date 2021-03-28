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
                <td colspan=6><h1 align="center">REKAPITULASI RINCIAN PEMBAYARAN SISWA<br> {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}</h1></td>
            </tr>
        </table>
        <table>
            <tr>
            @if($start_date != $end_date)
                <td colspan="3"><b>REKAPITULASI RINCIAN PEMBAYARAN <br><br>TANGGAL {{ \Carbon\Carbon::createFromFormat('Y-m-d', $start_date)->format('d M Y') }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d', $end_date)->format('d M Y') }}</b></td>
            @else
                <td colspan="3"><b>REKAPITULASI RINCIAN PEMBAYARAN <br><br>TANGGAL {{ \Carbon\Carbon::createFromFormat('Y-m-d', $start_date)->format('d M Y') }}</b></td>
            @endif
            </tr>
        </table>
        <br>
        <table border="1" cellspacing="0" cellpadding="10" style="width: 100%;">
            @foreach($data_pembayaran as $siswa)
            <tr>
                <th colspan="6" style="text-align: left; background:lightyellow">
                    {{ $siswa->first()->nis_siswa . ' - ' . $siswa->first()->nm_pengguna . ' (' . $siswa->first()->nm_kelas . ')'  }}
                </th>
            </tr>
            <tr>
                <th style="width: 10px;">No.</th>
                <th>Keterangan Bayar</th>
                <th>Tanggal Pembayaran</th>
                <th>Besar Pembayaran</th>
            </tr>
                @foreach($siswa as $key => $value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    @if($value->id_jenis_detail_biaya == 4)
                        <td>{{ $value->nm_biaya . ' ' . \Carbon\Carbon::createFromFormat('m', $value->id_bulan)->format('F') }}</td>
                    @else
                        <td>{{ $value->nm_biaya . ' ' . $value->keterangan }}</td>
                    @endif
                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value->tgl_pembayaran)->format('d M Y') }}</td>
                    <td style="text-align: right;">Rp {{ number_format($value->besar_pembayaran) }}</td>
                </tr>
                @endforeach
                <tr>
                    <th colspan="3">TOTAL</th>
                    <th>{{ "Rp " . number_format(collect($siswa)->sum('besar_pembayaran')) }}</th>
                </tr>
                <tr>
                <td colspan="4"></td>
                </tr>
            @endforeach
            <tr>
                <th colspan="3">GRAND TOTAL</th>
                <th>{{ "Rp " . number_format($total_pembayaran) }}</th>
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