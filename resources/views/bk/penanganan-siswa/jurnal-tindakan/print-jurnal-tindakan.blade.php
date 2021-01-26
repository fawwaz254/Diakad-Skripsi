<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Jurnal Tindakan</title>

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
    </style>
    <style type="text/css" media="print">
        @page {
            size: A4;
        }
    </style>
</head>

<body>
    <div class="page">
        <h1 align="center">LAPORAN PRIBADI SISWA <br> {{strtoupper($sekolah_data->nm_sekolah)}}</h1>
        <table>
            <tr>
                <td>Siswa</td>
                <td>:</td>
                <td>{{$siswa->nm_pengguna}}</td>
                <td width="300px"></td>
                <td>Kelas</td>
                <td>:</td>
                <td>{{$siswa->nm_kelas}}</td>
            </tr>
            <tr>
                <td>Nomor Induk</td>
                <td>:</td>
                <td>{{$siswa->nis_siswa}}</td>
                <td></td>
                <td>Semester</td>
                <td>:</td>
                <td>{{$semester->nm_semester}}</td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>{{$siswa->jenis_kelamin}}</td>
                <td></td>
                <td>Tahun Pelajaran</td>
                <td>:</td>
                <td>{{$semester->tahun_ajaran}}</td>
            </tr>
        </table>
        <br>

        <table border="0">
            <tr>
                <td><b>A.</b></td>
                <td><b>CATATAN SISWA</b></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <table border="1" cellspacing="0" cellpadding="10">
                        <tr>
                            <th>No.</th>
                            <th>Jenis Pelanggaran</th>
                            <th>Pelanggaran Tingkat</th>
                            <th>Catatan Pelanggaran</th>
                            <th>Poin</th>
                        </tr>
                        @php
                            $no = 1;
                        @endphp
                        @foreach($list_data as $data)
                        <tr>
                            <td>{{$no++}}.</td>
                            <td>{{$data->keterangan_subkategori_pelanggaran}}</td>
                            <td>{{$data->nm_kategori_pelanggaran}}</td>
                            <td>{{$data->catatan_pelanggaran}}</td>
                            <td>{{$data->poin_subkategori_pelanggaran}}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="4" align="center"><b>TOTAL</b></td>
                            <td align="center"><b>{{$list_data->sum('poin_subkategori_pelanggaran')}}</b></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td><b>B.</b></td>
                <td><b>DESKRIPSI PERILAKU SISWA</b></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <fieldset style="height: 90px;">
                        <p></p>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <td><b>C.</b></td>
                <td><b>CATATAN SEKOLAH</b></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <fieldset style="height: 90px;">
                        <p></p>
                    </fieldset>
                </td>
            </tr>
        </table>
        <div class="ttd">
            Sidoarjo, {{now('Asia/Jakarta')->format('d M Y')}}  <br> Wali Kelas <br><br><br> ....
        </div>
        <div class="clear"></div>
    </div>
</body>

</html>