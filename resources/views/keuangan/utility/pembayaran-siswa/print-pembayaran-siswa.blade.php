<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Pembayaran Siswa</title>

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

        .avoid-page-break {
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
        
        <table>
            <tr>
                <td colspan=1><img src="https://diakad.sgp1.digitaloceanspaces.com/{{$auth_data->sekolah_data->nm_singkat_sekolah}}/global/logo-sekolah" alt="Logo Sekolah" style="height:90px;" /></td>
                <td colspan=6><h1 align="center">KWITANSI PEMBAYARAN<br> {{strtoupper($auth_data->sekolah_data->nm_sekolah)}}</h1></td>
            </tr>
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
                <td>{{$semester_aktif->nm_semester}}</td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                @if($siswa->jenis_kelamin == 1)
                <td>L</td>
                @else
                <td>P</td>
                @endif
                <td></td>
                <td>Tahun Pelajaran</td>
                <td>:</td>
                <td>{{$semester_aktif->tahun_ajaran}}</td>
            </tr>
        </table>
        <br>

        <table border="0" style="width: 100%;">
            <tr>
                <td></td>
                <td><b>PEMBAYARAN TANGGAL {{date_format(date_create($tgl_pembayaran), 'd M Y')}}</b></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <table border="1" cellspacing="0" cellpadding="10" style="width: 100%;">
                        <tr>
                            <th>No.</th>
                            <th>Nama Biaya</th>
                            <th>Besar Pembayaran</th>
                            <!-- <th>Frekuensi</th> -->
                            <th>Subtotal</th>
                        </tr>
                        @php
                            $no = 1;
                        @endphp
                        @foreach($data_pembayaran_siswa as $pembayaran_siswa)
                        <tr>
                            <td>{{$no++}}.</td>
                            @if ($pembayaran_siswa->id_jenis_detail_biaya == 4) 
                            @php
                            $ket = $pembayaran_siswa->nm_bulan.' '.$pembayaran_siswa->thn_akademik_semester;
                            @endphp
                            <td>{{$pembayaran_siswa->nm_biaya." (".$ket.")"}}</td>
                            @else
                            <td>{{$pembayaran_siswa->nm_biaya." ".$pembayaran_siswa->keterangan}}</td>
                            @endif
                            <td>{{"Rp".number_format($pembayaran_siswa->besar_pembayaran)}}</td>
                            <!-- <td>1x</td> -->
                            <td>{{"Rp".number_format($pembayaran_siswa->besar_pembayaran)}}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="3 align="center"><b>TOTAL</b></td>
                            <td align="center"><b>{{"Rp".number_format($data_pembayaran_siswa->sum('besar_pembayaran'))}}</b></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div class="ttd avoid-page-break">
            {{$auth_data->sekolah_data->alamat_kecamatan}}, {{date_format(date_create($tgl_pembayaran), 'd M Y')}} <br><br><br><br> {{$auth_data->pengguna->nm_pengguna}}
        </div>
        <div class="clear"></div>
    </div>
</body>
<script>
    window.print();
</script>
</html>