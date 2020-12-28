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

        .vertical {
            text-align:center;
            white-space:nowrap;
            transform: rotate(90deg);
        }

        .mb-2 {
            margin-bottom: 20px;
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
    <table width="100%" style="margin-bottom: 30px;" class="">
        <tr>
            <td>Nama Sekolah</td>
            <td>:</td>
            <td>{{ $auth_data->sekolah_data->nm_sekolah }}</td>
            <td>Kelas</td>
            <td>:</td>
            <td>{{ collect($data_detail_rapor)->first()->kelas['nm_kelas'] }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td>{{ $auth_data->sekolah_data->alamat_jalan }}</td>
            <td>Semester</td>
            <td>:</td>
            <td>{{ collect($data_detail_rapor)->first()->nm_semester }}</td>
        </tr>
        <tr>
            <td>Nama Siswa</td>
            <td>:</td>
            <td>{{ $data_siswa->pengguna['nm_pengguna'] }}</td>
            <td>Tahun Pelajaran</td>
            <td>:</td>
            <td>{{ collect($data_detail_rapor)->first()->tahun_ajaran }}</td>
        </tr>
        <tr>
            <td>No. NIS</td>
            <td>:</td>
            <td>{{ $data_siswa['nis_siswa'] }}</td>
        </tr>
    </table>
    <hr>
    <h4>CAPAIAN HASIL BELAJAR</h4>
    @foreach($format_rapor_kategori as $kategori)
    <div class="mb-2">
        <h5>{{ $kategori->nm_rapor_kategori }}</h5>
        @if($kategori->nm_rapor_kategori == 'Sikap')
        <table class="presensi" width="100%">
            <tr>
                <td>Deskripsi: <br><br><br></td>
            </tr>
        </table>
        @elseif($kategori->nm_rapor_kategori == 'Pengetahuan dan Keterampilan')
        <table width="100%" class="presensi">
            <!-- start loop for format_rapor_kelompok -->
            @foreach($format_rapor_kelompok as $kel)
            <tr class="gray">
                <th colspan="10" class="text-left">{{ $kel->nm_rapor_kelompok }}</th>
            </tr>
            <tr>
                <th rowspan="2" width="10px">No.</th>
                <th rowspan="2">Mata Pelajaran</th>
                <th colspan="4" width="30%">Pengetahuan</th>
                <th colspan="4" width="30%">Keterampilan</th>
            </tr>
            <tr>
                <th><p class="vertical">KKM</p></th>
                <th><p class="vertical">Angka</p></th>
                <th><p class="vertical">Predikat</p></th>
                <th>Deskripsi</th>
                <th><p class="vertical">KKM</p></th>
                <th><p class="vertical">Angka</p></th>
                <th><p class="vertical">Predikat</p></th>
                <th>Deskripsi</th>
            </tr>
                <!-- loop for each detail_rapor based on kelompok -->
                @foreach(collect($data_detail_rapor)->where('id_rapor_kelompok', $kel->id_rapor_kelompok) as $key => $detail)
                <tr class="presensi">
                    <td style="width: 30px;">{{ $key + 1 }}</td>
                    <td>{{ $detail['nm_mata_pelajaran'] }}</td>
                    <td class="text-right">{{ $detail['nilai_kkm'] }}</td>
                    <td class="text-right">{{ $detail['nilai_angka'] }}</td>
                    <td class="text-center">{{ $detail['nilai_huruf'] }}</td>
                    <td class="text-center">-- deskripsi --</td>
                    <td class="text-right">{{ $detail['nilai_kkm'] }}</td>
                    <td class="text-right">{{ $detail['nilai_angka'] }}</td>
                    <td class="text-center">{{ $detail['nilai_huruf'] }}</td>
                    <td class="text-center">-- deskripsi --</td>
                </tr>
                @endforeach
            @endforeach
            <!-- end loop for format_rapor_kelompok -->
        </table>
        @elseif($kategori->nm_rapor_kategori == 'Praktek Kerja Lapangan')
        <table width="100%" class="presensi">
            <tr>
                <th style="width: 30px;">No.</th>
                <th>Mitra DU/DI</th>
                <th>Lokasi</th>
                <th>Lamanya (bulan)</th>
                <th>Keterangan</th>
            </tr>
        </table>
        @elseif($kategori->nm_rapor_kategori == 'Ekstrakurikuler')
        <table width="100%" class="presensi">
            <tr>
                <th style="width: 30px;">No.</th>
                <th>Kegiatan Ekstrakurikuler</th>
                <th>Keterangan</th>
            </tr>
        </table>
        @elseif($kategori->nm_rapor_kategori == 'Prestasi')
        <table width="100%" class="presensi">
            <tr>
                <th style="width: 30px;">No.</th>
                <th>Jenis Prestasi</th>
                <th>Keterangan</th>
            </tr>
        </table>
        @elseif($kategori->nm_rapor_kategori == 'Ketidakhadiran')
        <table class="presensi">
            <tr>
                <td>Izin</td>
                <td>: {{ $presensi['izin'] }} Hari</td>
            </tr>
            <tr>
                <td>Sakit</td>
                <td>: {{ $presensi['sakit'] }} Hari</td>
            </tr>
            <tr>
                <td>Tanpa Keterangan</td>
                <td>: {{ $presensi['tanpa_keterangan'] }} Hari</td>
            </tr>
        </table>
        @elseif($kategori->nm_rapor_kategori == 'Catatan Wali Kelas')
        <table style="border: 1px solid #000;" width="100%">
            <tr>
                <td style="padding: 10px;">
                    <p class="small">{{ collect($data_detail_rapor)->first()->deskripsi_catatan_wali_kelas }}</p>
                </td>
            </tr>
        </table>
        @elseif($kategori->nm_rapor_kategori == 'Tanggapan Orang Tua / Wali')
        <table style="border: 1px solid #000;" width="100%">
            <tr>
                <td><br><br><br></td>
            </tr>
        </table>
        @endif
    </div>
    @endforeach
    @if(collect($data_detail_rapor)->first()->nm_semester == 'Genap')
    <div class="mb-2">
        <h5>Keputusan</h5>
        <p class="small">Berdasarkan hasil yang dicapai pada semester 1 dan 2, maka peserta didik ini ditetapkan :</p>
        <table>
            <tr>
                <td>Naik ke kelas</td>
                <td>:</td>
            </tr>
            <tr>
                <td>Tinggal di kelas</td>
                <td>:</td>
            </tr>
        </table>
    </div>
    @endif
    <div>
        <table width="100%" class="text-center">
            <tr>
                <td>Mengetahui :</td>
                <td>....., .............</td>
            </tr>
            <tr>
                <td>Orang Tua/Wali</td>
                <td>Wali Kelas<td>
            </tr>
            <tr>
                <td><br><br></td>
            </tr>
            <tr>
                <td>__________________</td>
                <td>__________________</td>
            </tr>
            <tr>
                <td colspan="2" class="text-center">Mengetahui:</td>
            </tr>
            <tr>
                <td colspan="2" class="text-center">Kepala Sekolah</td>
            </tr>
            <tr>
                <td colspan="2"><br><br></td>
            </tr>
            <tr>
                <td colspan="2" class="text-center">-- Kepala Sekolah --</td>
            </tr>
        </table>
    </div>
    
</body>

</html>