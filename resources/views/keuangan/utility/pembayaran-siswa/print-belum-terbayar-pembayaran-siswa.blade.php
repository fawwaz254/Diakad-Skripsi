<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak tagihan siswa yang belum terbayar</title>

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
        
        <table>
            <tr>
                <td colspan=1><img src="https://diakad.sgp1.digitaloceanspaces.com/{{$auth_data->sekolah_data->nm_singkat_sekolah}}/global/logo-sekolah" alt="Logo Sekolah" style="height:90px;" /></td>
                <td colspan=6><h1 align="center">TAGIHAN PEMBAYARAN<br> {{strtoupper($auth_data->sekolah_data->nm_sekolah)}}</h1></td>
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
                <td><b>TAGIHAN PER TANGGAL {{ indonesiaDate(\Carbon\Carbon::now()->format('Y-m-d')) }}</b></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <table border="1" cellspacing="0" cellpadding="10" style="width: 100%;">
                        <tr>
                            <th>No.</th>
                            <th>Nama Biaya</th>
                            <th>Besar Tagihan</th>
                            <th>Frekuensi</th>
                            <th>Subtotal</th>
                        </tr>
                        @php
                            $no = 1;
                        @endphp
                        @foreach($list_data as $data)
                        <tr>
                            <td>{{$no++}}.</td>
                            @if ($data->id_jenis_detail_biaya == 4) 
                            <td>{{$data->nm_biaya." (".$data->nm_bulan.")"}}</td>
                            @else
                            <td>{{$data->nm_biaya." ".$data->keterangan}}</td>
                            @endif
                            <td>{{"Rp".number_format($data->besar_pembayaran)}}</td>
                            <td>1x</td>
                            <td>{{"Rp".number_format($data->besar_pembayaran)}}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="4" align="center"><b>TOTAL</b></td>
                            <td align="center"><b>{{"Rp".number_format($list_data->sum('besar_pembayaran'))}}</b></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div class="ttd avoid-break">
            {{$auth_data->sekolah_data->alamat_kecamatan}}, {{ indonesiaDate(\Carbon\Carbon::now()->format('Y-m-d')) }} <br><br><br><br> {{$auth_data->pengguna->nm_pengguna}}
        </div>
        <div class="clear"></div>
    </div>
</body>
<script>
    window.print();
</script>
</html>