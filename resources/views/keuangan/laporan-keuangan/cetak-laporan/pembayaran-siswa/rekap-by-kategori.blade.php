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
                <td colspan=1><img
                        src="https://diakad.sgp1.digitaloceanspaces.com/{{ $auth_data->sekolah_data->nm_singkat_sekolah }}/global/logo-sekolah"
                        alt="Logo Sekolah" style="height:90px;" /></td>
                <td colspan=6>
                    <h1 align="center">LAPORAN PEMBAYARAN SISWA PER KATEGORI<br>
                        {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}</h1>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                @if ($start_date != $end_date)
                    <td colspan="3"><b>TANGGAL {{ strtoupper(indonesiaDate($start_date)) }} -
                            {{ strtoupper(indonesiaDate($end_date)) }}</b></td>
                @else
                    <td colspan="3"><b>TANGGAL {{ strtoupper(indonesiaDate($start_date)) }}</b></td>
                @endif
            </tr>
        </table>
        <br>
        <table border="1" cellspacing="0" cellpadding="5" style="width: 100%; font-size:small" class="mb-2">
            <tr>
                <th style="width: 10px;">No.</th>
                <th>Nama Kategori</th>
                <th>Jumlah</th>
            </tr>
            @foreach ($data_laporan as $r)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $r['nama'] }}</td>
                    <td><b>{{ 'Rp ' . number_format($r['total']) }}</b></td>
                </tr>
            @endforeach
        </table>

        <div class="avoid-break mt-4 mb-4">
            <table cellspacing="0" style="width: 80%; margin:auto; text-align:center">
                <tr>
                    <td style="width: 50%;">Mengetahui</td>
                    <td>{{ $auth_data->sekolah_data->alamat_kecamatan !== null ? $auth_data->sekolah_data->alamat_kecamatan . ', ' : null }}
                        {{ indonesiaDate(\Carbon\Carbon::now()->format('Y-m-d')) }}
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
    var start_date = '{{ strtoupper(indonesiaDate($start_date)) }}';
    var end_date = '{{ strtoupper(indonesiaDate($end_date)) }}';

    document.title = 'Rekap per Kategori' + ' - ' + start_date + ' - ' + end_date;
    window.print();
</script>

</html>
