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
            border-collapse: collapse;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }

        .presensi td{
            padding: 9px;
            border: 1px solid #000000;
            /* border-left: 1px solid #000000; */
        }

        .small {
            font-size: x-small;
        }

        /* .presensi tr td:last-child, .presensi tr th:last-child {
            border-right: 1px solid #000000;
        }

        .presensi tr:last-child td{
            border-bottom: 1px solid #000000;
        } */

        .presensi th{
            padding: 9px;
            border: 1px solid #000000;
            /* border-left: 1px solid #000000; */
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
    <h5>Data Siswa</h5>
    <table width="50%" style="margin-bottom: 30px;" class="">
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
            <td>{{ $data_rapor->kelas['nm_kelas'] }}</td>
        </tr>
    </table>

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
    <table width="100%" class="presensi">
        @if(!empty($data_detail_rapor))
        <!-- loop for jenis_mata_pelajaran -->
        @foreach($data_detail_rapor as $jenis_mata_pelajaran => $detail)
        <tr class="gray">
            <th colspan="6" class="text-left">{{ strtoupper($jenis_mata_pelajaran) }}</th>
        </tr>
        <tr>
            <th>No.</th>
            <th>Mata Pelajaran</th>
            <th>Nilai KKM</th>
            <th>Nilai Angka</th>
            <th>Nilai Huruf</th>
            <th>Deskripsi</th>
        </tr>
            <!-- loop for each detail_rapor based on jenis_mata_pelajaran -->
            @foreach($detail as $key_detail => $detail_rapor)
            <tr class="presensi">
                <td style="width: 30px;">{{ $key_detail + 1 }}</td>
                <td>{{ $detail_rapor['nm_mata_pelajaran'] }}</td>
                <td class="text-right">{{ $detail_rapor['nilai_kkm'] }}</td>
                <td class="text-right">{{ $detail_rapor['nilai_angka'] }}</td>
                <td class="text-center">{{ $detail_rapor['nilai_huruf'] }}</td>
                <td class="text-center">-- deskipsi --</td>
            </tr>
            @endforeach
        @endforeach
        @endif
    </table>
    <div>
        <h5>Catatan Wali Kelas</h5>
        <table style="border: 1px solid #000;" width="100%">
            <tr>
                <td style="padding: 10px;">
                    <p class="small">{{ $data_rapor->deskripsi_catatan_wali_kelas }}</p>
                </td>
            </tr>
        </table>
    </div>
    <div>
        <h5>Absensi</h5>
        <table>
            <tr>
                <td>Izin</td>
                <td>:</td>
                <td>{{ $data_rapor->jumlah_izin }}</td>
            </tr>
            <tr>
                <td>Sakit</td>
                <td>:</td>
                <td>{{ $data_rapor->jumlah_sakit }}</td>
            </tr>
            <tr>
                <td>Tanpa Keterangan</td>
                <td>:</td>
                <td>{{ $data_rapor->jumlah_tanpa_keterangan }}</td>
            </tr>
        </table>
    </div>
</body>

</html>