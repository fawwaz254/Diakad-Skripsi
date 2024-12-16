<!DOCTYPE html>
<html lang="eng">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Nilai Rapor Semester</title>


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
            font-size: 15px;
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
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; " style="border-style : hidden">
                <tr style="border-style : hidden ;">
                    <td style="border-style : hidden;width: 14%;">Nama Sekolah
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;">
                        {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}
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
                    <td style="border-style : hidden;width: 14%;vertical-align: top;">Alamat
                    </td>
                    <td style="border-style : hidden;width: 1%;vertical-align: top;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;vertical-align: top;">
                        JL. MANYAR SAMBONGAN 119
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
                    <td style="border-style : hidden;width: 14%;">Nama Siswa
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;font-weight: bold;">
                        {{ $siswa->pengguna->nm_pengguna }}
                    </td>
                    <td style="border-style : hidden;width: 24%;">Tahun Pelajaran
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 25%; ">
                        {{ $semester->tahun_ajaran }}
                    </td>
                </tr>

                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 14%;">NO Induk
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;">
                        {{ $siswa->nis_siswa }}
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
                <thead class="head" style="background-color: #ccffff">
                    <tr>
                        <th style="width: 5%">No</th>
                        <th>Mata Pelajaran</th>
                        <th>Nilai</th>
                        <th>Capaian Kompetensi</th>
                    </tr>

                </thead>
                <tbody class="body">
                    @foreach ($data as $kelompok)
                        @if (isset($kelompok['data']))
                            @php
                                $no = 0;
                            @endphp
                            @foreach ($kelompok['data'] as $key => $data2)
                                @php
                                    $jumlah = count($data2['nm_point']);
                                @endphp
                                @if (
                                    !isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . 'rata-rata']) ||
                                        ($kelas->tingkat == '2' && $kelas->tingkat == '3'))
                                    @continue
                                @endif
                                <tr>
                                    <td style="text-align: center;width: 5%" rowspan="2">{{ ++$no }}</td>
                                    <td rowspan="2">{{ $data2['nm_point'][0] }}</td>

                                    @php
                                        $komponen = $list_komponen->first();
                                    @endphp
                                    <td style="text-align: center;font-weight: bold;" rowspan="2">
                                        @if (isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . 'rata-rata']) &&
                                                isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . 'uas']))
                                            {{ round(($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . 'rata-rata'] + $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . 'uas'] * 2) / 3) }}
                                        @endif
                                    </td>
                                    <td style="padding: 3px">
                                        {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . 'keterangan']) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . 'keterangan'] : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 3px">
                                        {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . 'keterangan2']) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . 'keterangan2'] : '' }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>
            <br>
        </div>
        <div class="page">
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;border-style : hidden">
                <tr>
                    <td><b>II. Ekstra Kurikuler</b></td>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
                <tr>
                    <th style="width: 5%">No</th>
                    <th style="width:30%">Nama Ekstrakurikuler</th>
                    <th style="65%">Keterangan</th>
                </tr>

                @php
                    $no_ekskul = 0;
                @endphp
                @foreach ($ekskul_tambahan_rapor as $ekskul)
                    @if (isset($tambahan['ekskul'][$siswa->id_siswa][$ekskul->id_tambahan_rapor]))
                        <tr>
                            <td style="text-align: center">{{ ++$no_ekskul }}</td>
                            <td style="padding-left: 10px">{{ $ekskul->nm_tambahan_rapor }}</td>
                            <td style="text-align: center">
                                {{ $tambahan['ekskul'][$siswa->id_siswa][$ekskul->id_tambahan_rapor] }}</td>
                        </tr>
                    @endif
                @endforeach

                @if (!isset($tambahan['ekskul'][$siswa->id_siswa]))
                    <tr>
                        <td style="text-align: center">-</td>
                        <td style="padding-left: 10px">-</td>
                        <td style="text-align: center">-</td>
                    </tr>
                @endif

            </table>
            <br>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;border-style : hidden">
                <tr>
                    <td><b>III. Prestasi</b></td>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; ">
                <tr>
                    <th style="width: 5%">No</th>
                    <th style="width:30%">Jenis Prestasi</th>
                    <th style="width: 65%">Keterangan</th>
                </tr>
                <tr>
                    <td style="text-align: center">-</td>
                    <td style="padding-left: 10px">-</td>
                    <td style="text-align: center">-</td>
                </tr>
            </table>
            <br>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
                <tr>
                    <td><b>IV. Ketidak Hadiran</b></td>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; ">
                <tr>
                    <th>Alasan Ketidak Hadiran</th>
                    <th>Jumlah Ketidak Hadiran</th>
                    <th
                        style="width: 50%;border-right: hidden; 
        border-bottom: hidden; 
        border-top: hidden;">
                    </th>
                </tr>

                @foreach ($kehadiran_tambahan_rapor as $k)
                    <tr>
                        <td style="padding-left: 10px">
                            {{ $k->nm_tambahan_rapor }}
                        </td>
                        <td style="padding-left: 10px">
                            {{ isset($tambahan['ketidakhadiran'][$siswa->id_siswa][$k->id_tambahan_rapor]) ? $tambahan['ketidakhadiran'][$siswa->id_siswa][$k->id_tambahan_rapor] . ' Hari' : '-' }}
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
                    <td><b>V. Catatan Walikelas</b></td>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
                <tr>
                    <td style="padding: 10px">
                        {{ isset($tambahan['catatan_wali_kelas'][$siswa->id_siswa]) ? $tambahan['catatan_wali_kelas'][$siswa->id_siswa] : '-' }}

                    </td>
                </tr>
            </table>
            <br>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
                <tr>
                    <td style="border-style : hidden; width:35%;" align="left">
                        <br>
                        <br>
                        Orang Tua/Wali
                        <br><br><br><br><br><br><br>
                        <p style="width: 250px;
								border-bottom: 1px solid   black;">
                        </p>
                    </td>
                    <td style="width:35%; border-style : hidden;" align="left">

                    </td>
                    <td style="width: 30%;">
                        Surabaya, {{ $tanggal_cetak }}
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
                        Kepala Sekolah
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
