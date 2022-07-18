<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Cetak Presensi</title>
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
                <h3>PRESENSI UAS KELAS <br>
                    {{strtoupper($auth_data->sekolah_data->nm_sekolah)}} <br>
                    TAHUN AJARAN {{$semester_aktif->tahun_ajaran}}
                </h3>
                <hr>
            </td>
        </tr>
    </table>

    <table width="100%" style="margin-bottom: 30px;">
        <tr>
            <td width="50%">
                KELAS: {{$data_kelas->nm_kelas}} <br>
                MAPEL: {{$data_kelas->nm_mata_pelajaran}} <br>
            </td>
            <td width="50%">
                TANGGAL: {{date_format(date_create($data_kelas->tgl_ujian_mp), "d M Y")}} <br>
                JAM: {{$data_kelas->jam_mulai}} - {{$data_kelas->jam_selesai}} <br>
            </td>
        </tr>
    </table>

    <table class="presensi">
        <thead>
            <tr>
                <th style="text-align:center">No. </th>
                <th style="text-align:center" width="100">NIS</th>
                <th style="text-align:center" width="275">Nama</th>
                <th style="text-align:center" width="100">Presensi</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @foreach($data_siswa as $siswa)
            <tr>
                <td height="25">{{$no++}}</td>
                <td>{{$siswa->nis_siswa}}</td>
                <td>{{$siswa->nm_pengguna}}</td>
                <td></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>