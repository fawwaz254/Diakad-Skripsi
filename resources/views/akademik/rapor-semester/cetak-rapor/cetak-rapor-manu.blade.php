<!DOCTYPE html>
<html lang="eng">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Nilai Rapor Tengah Semester</title>


    <style>
        * {
            font-family: 'Tahoma';
            letter-spacing: 1.5px;
        }

        table,
        td,
        th {
            border: 1px solid;
            padding: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            /* border: 5px double; */
        }

        .head {
            border: 5px double;
        }

        td {
            /* font-size: 10px; */
            padding: 2px;
        }

        .page {
            width: 1200px;
        }

        .body {

            border: 5px double;
            border-top-style: none;
        }

        .under-below {
            text-decoration: underline;
            -webkit-text-underline-position: under;
            -ms-text-underline-position: below;
            text-underline-position: under;
        }
    </style>

    <style type="text/css" media="print">
        @page {
            /* margin: 125mm 125mm 125mm 125mm;    */
            size: portrait;
            size: auto;
            margin: 0mm;

        }

        @media print {
            .page {
                page-break-after: always;
            }
        }
    </style>
</head>

<body>

    @foreach ($list_siswa as $siswa)
        <div class="page">
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
                <tr>
                    <th>
                        <h2>RAPOR DAN PROFIL PESERTA DIDIK</h2>
                    </th>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
                <tr style="border-style : hidden ;">
                    <td style="border-style : hidden;width: 14%;">Nama Siswa
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;">
                        {{ $siswa->pengguna->nm_pengguna }}

                    </td>
                    <td style="border-style : hidden;width: 24%;">Kelas
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 25%; ">
                        {{ $kelas->nm_kelas }}
                    </td>
                </tr>
                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 14%;">NISN/NIS
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;">
                        {{ $siswa->nisn_siswa . '/' . $siswa->nis_siswa }}
                    </td>
                    <td style="border-style : hidden;width: 24%;">Fase
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 25%; ">
                        D
                    </td>
                </tr>
                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 14%;vertical-align: top;">Nama Sekolah
                    </td>
                    <td style="border-style : hidden;width: 1%;vertical-align: top;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;vertical-align: top;">
                        {{ $auth_data->sekolah_data->nm_sekolah }}
                    </td>
                    <td style="border-style : hidden;width: 24%;vertical-align: top;">Semester
                    </td>
                    <td style="border-style : hidden;width: 1%;vertical-align: top;"> :
                    </td>
                    <td style="border-style : hidden;width: 25%;vertical-align: top; ">
                        {{ $semester->nm_semester }}
                    </td>
                </tr>
                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 14%;vertical-align: top;">Alamat Sekolah
                    </td>
                    <td style="border-style : hidden;width: 1%;vertical-align: top;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;vertical-align: top;">
                        JL. KABUPATEN NO.72, Jatirejo
                    </td>
                    <td style="border-style : hidden;width: 24%;vertical-align: top;">Tahun Pelajaran
                    </td>
                    <td style="border-style : hidden;width: 1%;vertical-align: top;"> :
                    </td>
                    <td style="border-style : hidden;width: 25%;vertical-align: top; ">
                        {{ $semester->tahun_ajaran }}
                    </td>
                </tr>
            </table>

            <br>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;border-style : hidden">
                <tr style="font-weight: bold;">
                    <td>
                        I. Nilai Akademik
                    </td>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
                <thead class="head" style="background-color: #d4d4d4">
                    <tr>
                        <th>No</th>
                        <th>Mata Pelajaran</th>
                        <th>Nilai Akhir</th>
                        <th>Capaian Kompetensi</th>
                    </tr>
                </thead>
                <tbody class="body">
                    @foreach ($data as $kelompok)
                        @if (isset($kelompok['data']))
                            <tr>
                                <td colspan="2" style="font-weight: bold;">
                                    {{ isset($kelompok['nama']) ? $kelompok['nama'] : '' }}</td>
                            </tr>
                            @foreach ($kelompok['data'] as $key => $data2)
                                @php
                                    $jumlah = count($data2['nm_point']);
                                @endphp
                                <tr>
                                    <td style="text-align: center;" rowspan="2">{{ $key }}</td>
                                    <td rowspan="2">{{ $data2['nm_point'][0] }}</td>

                                    @php
                                        $komponen = $list_komponen->first();
                                    @endphp
                                    <td style="text-align: center;font-weight: bold;" rowspan="2">
                                        {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_jenis_rapor . 'nilai']) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_jenis_rapor . 'nilai'] : '' }}
                                    </td>
                                    <td>
                                        {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_jenis_rapor . 'keterangan']) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_jenis_rapor . 'keterangan'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_jenis_rapor . 'keterangan2']) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_jenis_rapor . 'keterangan2'] : '' }}
                                    </td>
                                </tr>
                                @for ($i = 1; $i < $jumlah; $i++)
                                    <tr>
                                        <td rowspan="2"></td>
                                        <td rowspan="2">{{ $data2['nm_point'][$i] }}</td>
                                        <td style="text-align: center;font-weight: bold;" rowspan="2">
                                            {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_jenis_rapor . 'nilai']) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_jenis_rapor . 'nilai'] : '' }}
                                        </td>
                                        <td>
                                            {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_jenis_rapor . 'keterangan']) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_jenis_rapor . 'keterangan'] : '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_jenis_rapor . 'keterangan2']) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_jenis_rapor . 'keterangan2'] : '' }}
                                        </td>

                                        </td>
                                    </tr>
                                @endfor
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>
            <br>

            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
                <tr style="background-color: #d4d4d4">
                    <th>No</th>
                    <th>Ekstrakurikuler</th>
                    <th>Keterangan</th>
                </tr>

                @php
                    $no_ekskul = 0;
                @endphp
                @foreach ($ekskul_tambahan_rapor as $ekskul)
                    @if (isset($tambahan['ekskul'][$siswa->id_siswa][$ekskul->id_tambahan_rapor]))
                        <tr>
                            <td style="text-align: center">{{ ++$no_ekskul }}</td>
                            <td style="text-align: center">{{ $ekskul->nm_tambahan_rapor }}</td>
                            <td style="text-align: center">
                                {{ $tambahan['ekskul'][$siswa->id_siswa][$ekskul->id_tambahan_rapor] }}</td>
                        </tr>
                    @endif
                @endforeach

                @if (!isset($tambahan['ekskul'][$siswa->id_siswa]))
                    <tr>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                @endif

            </table>
            <br>




            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
                <tr>
                    <td style="background-color: #d4d4d4"><b>Catatan Guru</b></td>
                </tr>
                <tr>
                    <td style="padding: 10px">
                        {{ isset($tambahan['catatan_wali_kelas'][$siswa->id_siswa]) ? $tambahan['catatan_wali_kelas'][$siswa->id_siswa] : '-' }}

                    </td>
                </tr>
            </table>
            <br>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; ">
                <tr>
                    <td style="background-color: #d4d4d4" colspan="2"><b>Ketidak Hadiran</b></td>
                    <td style="width: 50%;border-right: hidden; 
					border-bottom: hidden; 
					border-top: hidden;">
                    </td>
                </tr>
                @foreach ($kehadiran_tambahan_rapor as $k)
                    <tr>
                        <td>
                            {{ $k->nm_tambahan_rapor }}
                        </td>
                        <td style="text-align: center">
                            {{ isset($tambahan['ketidakhadiran'][$siswa->id_siswa][$k->id_tambahan_rapor]) ? $tambahan['ketidakhadiran'][$siswa->id_siswa][$k->id_tambahan_rapor] . ' Hari' : ' - Hari' }}
                        </td>
                        <td
                            style="width: 50%;border-right: hidden; 
					border-bottom: hidden; 
					border-top: hidden;">
                        </td>
                    </tr>
                @endforeach
            </table>
            <br>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
                <tr>
                    <td style="border-style : hidden; width:35%;" align="left">
                        Mengetahui, <br>
                        Orang Tua/Wali
                        <br><br><br><br><br><br><br>
                        <p style="width: 250px;
								border-bottom: 1px solid   black;">
                        </p>
                    </td>
                    <td style="width:35%; border-style : hidden;" align="left">

                    </td>
                    <td style="width: 30%;">
                        Pasuruan, 23 Desember 2023
                        <br>
                        Wali Kelas
                        <br><br><br><br><br><br><br>
                        @if (isset($wali_kelas->guru->pengguna->nm_pengguna))
                            <b><u> {{ $wali_kelas->guru->pengguna->gelar_depan }}
                                    {{ $wali_kelas->guru->pengguna->nm_pengguna }}
                                    {{ $wali_kelas->guru->pengguna->gelar_belakang }}</u></b>
                        @else
                            <p style="width: 250px;
										border-bottom: 1px solid   black;">
                            </p>
                        @endif

                    </td>
                </tr>
            </table>

            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
                <tr>
                    <td style="border-style : hidden; width:35%;" align="left">
                    </td>
                    <td style="width:35%; border-style : hidden;" align="left">
                        Mengetahui, <br>
                        Kepala Madrasah
                        <br><br><br><br><br><br><br>
                        <b><u>
                                {{ $auth_data->sekolah_data->nm_kepala_sekolah }}</u></b>
                    </td>
                    <td style="width: 30%;">
                    </td>
                </tr>
            </table>
        </div>
    @endforeach
</body>
<script>
    window.print();
</script>

</html>
