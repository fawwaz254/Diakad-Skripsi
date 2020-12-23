<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Rapor</title>
    <!-- source https://gist.github.com/alfredoem/c7a6fbcba33c57948132 -->

    <style type="text/css">
        * {
            font-family: Verdana, Arial, sans-serif;
        }

        table {
            font-size: x-small;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }


        .presensi td{
            padding-bottom: 9px;
            border-top: 1px solid #000000;
            border-left: 1px solid #000000;
        }

        .presensi tr td:last-child, .presensi tr th:last-child {
            border-right: 1px solid #000000;
        }

        .presensi tr:last-child td{
            border-bottom: 1px solid #000000;
        }

        .presensi th{
            padding-bottom: 9px;
            border-top: 1px solid #000000;
            border-left: 1px solid #000000;
        }

        .gray {
            background-color: lightgray
        }

        #logo {
            -webkit-filter: grayscale(100%);
            /* Safari 6.0 - 9.0 */
            filter: grayscale(100%);
        }
    </style>

</head>

<body>

    <table width="100%" style="margin-bottom: 30px;">
        <tr>
            <td width="60">
                <img id="logo"
                    src="https://diakad.sgp1.digitaloceanspaces.com/{{$auth_data->sekolah_data->nm_singkat_sekolah}}/global/logo-sekolah"
                    height="100">
            </td>
            <td width="400">
                <h3>RAPOR<br>
                    {{strtoupper($auth_data->sekolah_data->nm_sekolah)}}
                </h3>
                <hr>
            </td>
        </tr>
    </table>
    
    <div class="row">
        <div class="col-6">
            <table width="100%" style="margin-bottom: 30px;">
                <tr>
                    <td>No. NIS</td>
                    <td>:</td>
                    <td>{{ $data_siswa['nis_siswa'] }}</td>
                </tr>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>{{ $data_siswa->pengguna['nm_pengguna'] }}</td>
                </tr>
                <tr>
                    <td>Kelas</td>
                    <td>:</td>
                    <td></td>
                </tr>
            </table>
        </div>
        <div class="col-6"></div>
    </div>

    <!-- <table width="100%">
        <tr>
            <th>A. SIKAP</th>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <th>1. Pada Teman</th>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <th>2. Pada Guru</th>
            <td></td>
            <td></td>
        </tr>
    </table> -->
    <table width="100%" style="border-width: 1px; border-color:#000000; text-align:left;">
        <tr>
            <th colspan="3">B. MUATAN LOKAL</th>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <th>No.</th>
            <th>Mata Pelajaran</th>
            <th>Nilai KKM</th>
            <th>Nilai Angka</th>
            <th>Nilai Huruf</th>
        </tr>
        @foreach($data_detail_rapor as $key => $detail)
        <tr>
            <td>{{ $key }}</td>
            <td>{{ $detail['nm_mata_pelajaran'] }}</td>
            <td>{{ $detail['nilai_kkm'] }}</td>
            <td>{{ $detail['nilai_angka'] }}</td>
            <td>{{ $detail['nilai_huruf'] }}</td>
        </tr>
        @endforeach
    </table>
</body>

</html>