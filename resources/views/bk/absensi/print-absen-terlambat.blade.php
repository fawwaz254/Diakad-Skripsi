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
            width: <?= $lebar ?>mm;
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
            size: <?= $lebar ?>mm;
        }
    </style>
</head>

<body>
    <div class="page">
        
        <table>
            <tr>
                <td colspan=1><img src="https://diakad.sgp1.digitaloceanspaces.com/{{$auth_data->sekolah_data->nm_singkat_sekolah}}/global/logo-sekolah" alt="Logo Sekolah" style="height:50px;" /></td>
                <td colspan=6><h1 style="font-size: 10pt;" align="center">SURAT IZIN MASUK<br> {{strtoupper($auth_data->sekolah_data->nm_sekolah)}}</h1></td>
            </tr>
        </table>
        <table style="width: 100%; font-size:12px;font-weight: 700;">
            <tr>
                <td>Siswa</td>
                <td style="width: 5px;">:</td>
                <td>{{$siswa->nm_pengguna}}</td>
            </tr>
            <tr>
                <td>Nomor Induk</td>
                <td>:</td>
                <td>{{$siswa->nis_siswa}}</td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>:</td>
                <td>{{$siswa->nm_kelas}}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td>{{ Carbon\Carbon::parse($presences->date)->format('d-m-Y') }}</td>
            </tr>
        </table>
        <hr>
        <table border="0" cellspacing="0" cellpadding="5" style="width: 100%; font-size:12px;font-weight: 700;">
            <tr>
                <th>Jam Check In</th>
                <th>Notes</th>
            </tr>
            <tr>
                <td style="text-align: center;">
                    @if ($presences->check_in)
                        {{$presences->check_in}}
                    @else
                        -
                    @endif

                </td>
                <td style="text-align: center;">
                    @if ($presences->notes)
                        {{$presences->notes}}
                    @else
                        -
                    @endif

                </td>
            </tr>
        </table>
        <hr>
        <div class="ttd" style="font-weight:700;font-size: 12px;">
            {{ $auth_data->sekolah_data->alamat_kecamatan !== null ? $auth_data->sekolah_data->alamat_kecamatan . ', ' : null }} {{ Carbon\Carbon::parse($presences->date)->format('d-m-Y') }} <br><br><br><br> {{$auth_data->pengguna->nm_pengguna}}
        </div>
        <div class="clear"></div>
    </div>
</body>
<script>
    window.print();
</script>
</html>