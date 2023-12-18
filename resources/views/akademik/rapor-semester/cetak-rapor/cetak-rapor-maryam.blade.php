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
            font-size: 17px;
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
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
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
                    <td style="border-style : hidden;width: 14%;">NO Induk/NISN
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;">
                        {{ $siswa->nis_siswa . '/' . $siswa->nisn_siswa }}
                    </td>
                </tr>
                <tr>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
                <tr>
                    <td>
                        <h2 style="text-align: center">CAPAIAN HASIL BELAJAR</h2>
                    </td>
                </tr>
            </table>

            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;border-style : hidden">
                <tr style="font-weight: bold;border-style : hidden">
                    <td>
                        A. Sikap
                    </td>
                </tr>
            </table>
            <br>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;border-style : hidden">
                {{-- <br> --}}
                <tr style="border-style : hidden">
                    <td style="font-weight: bold;">1. Sikap Spiritual</td>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
                <thead class="head" style="background-color: #dcdcff">
                    <tr>
                        <th>Predikat</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody class="body">
                    <tr>
                        <td style="text-align: center;vertical-align: top;padding:10px">
                            {{ isset($tambahan['predikat_sikap_spiritual'][$siswa->id_siswa]) ? $tambahan['predikat_sikap_spiritual'][$siswa->id_siswa] : '-' }}
                        </td>
                        <td style="height: 300px;vertical-align: top;padding:10px">Memiliki sikap spiritual
                            {{ isset($tambahan['predikat_sikap_spiritual'][$siswa->id_siswa]) ? $tambahan['predikat_sikap_spiritual'][$siswa->id_siswa] : '-' }},
                            antara lain<br>
                            {{ isset($tambahan['deskripsi_sikap_spiritual'][$siswa->id_siswa]) ? $tambahan['deskripsi_sikap_spiritual'][$siswa->id_siswa] : '-' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <br>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;border-style : hidden">

                <tr style="border-style : hidden">
                    <td style="font-weight: bold;">2. Sikap Sosial</td>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
                <thead class="head" style="background-color: #dcdcff">
                    <tr>
                        <th>Predikat</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody class="body">
                    <tr>
                        <td style="text-align: center;vertical-align: top;padding:10px">
                            {{ isset($tambahan['predikat_sikap_sosial'][$siswa->id_siswa]) ? $tambahan['predikat_sikap_sosial'][$siswa->id_siswa] : '-' }}
                        </td>
                        <td style="height: 300px;vertical-align: top;padding:10px">Memiliki sikap sosial
                            {{ isset($tambahan['predikat_sikap_sosial'][$siswa->id_siswa]) ? $tambahan['predikat_sikap_sosial'][$siswa->id_siswa] : '-' }},
                            antara
                            lain<br>
                            {{ isset($tambahan['deskripsi_sikap_sosial'][$siswa->id_siswa]) ? $tambahan['deskripsi_sikap_sosial'][$siswa->id_siswa] : '-' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <br>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
                <tr>
                    <td style="border-style : hidden; width:35%;" align="left">
                    </td>
                    <td style="width:30%; border-style : hidden;" align="left">
                    </td>
                    <td style="width: 35%;">
                        {{ 'Surabaya, 21 Desember' }}
                        <br>
                        Wali Kelas
                        <br><br><br><br><br><br><br>
                        @if (isset($wali_kelas->guru->pengguna->nm_pengguna))
                            <b><u> {{ $wali_kelas->guru->pengguna->gelar_depan }}
                                    {{ $wali_kelas->guru->pengguna->nm_pengguna }}
                                    {{ $wali_kelas->guru->pengguna->gelar_belakang }}</u></b>
                        @else
                            <p
                                style="width: 250px;
                                    border-bottom: 1px solid   black;">
                            </p>
                        @endif

                    </td>
                </tr>
            </table>
        </div>

        @foreach ($list_komponen as $noKomponen => $komponen)
            <div class="page">
                <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;"
                    style="border-style : hidden">
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
                        <td style="border-style : hidden;width: 14%;">NO Induk/NISN
                        </td>
                        <td style="border-style : hidden;width: 1%;"> :
                        </td>
                        <td style="border-style : hidden;width: 35%;">
                            {{ $siswa->nis_siswa . '/' . $siswa->nisn_siswa }}
                        </td>
                    </tr>
                    <tr>
                    </tr>

                </table>


                <br>
                <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;border-style : hidden">
                    <tr style="font-weight: bold;">
                        <td>
                            @if ($noKomponen == '0')
                                B.
                            @else
                                C.
                            @endif {{ $komponen->nm_komponen_jenis_rapor }}<br>
                            Kriteria Ketuntasan Minimal = 78
                        </td>
                    </tr>
                </table>
                <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
                    <thead class="head" style="background-color: #dcdcff">
                        <tr>
                            <td rowspan="2" style="text-align: center;font-weight: bold;">NO</td>
                            <td rowspan="2" style="text-align: center;font-weight: bold;">MATA PELAJARAN<br></td>
                            <td colspan="3" style="text-align: center;font-weight: bold;">
                                {{ $komponen->nm_komponen_jenis_rapor }}<br></td>

                        </tr>
                        <tr>
                            <td style="text-align: center;font-weight: bold;">Nilai</td>
                            <td style="text-align: center;font-weight: bold;">Predikat</td>
                            <td style="text-align: center;font-weight: bold;">Deskripsi</td>
                        </tr>
                    </thead>
                    <tbody class="body">
                        @foreach ($data as $kelompok)
                            <tr>
                                <td colspan="5" style="font-weight: bold;">
                                    {{ isset($kelompok['nama']) ? $kelompok['nama'] : '' }}</td>
                            </tr>
                            @if (isset($kelompok['data']))
                                @foreach ($kelompok['data'] as $key => $data2)
                                    @php
                                        $jumlah = count($data2['nm_point']);
                                    @endphp
                                    <tr>
                                        <td style="text-align: center;">{{ $key }}</td>
                                        <td>{{ $data2['nm_point'][0] }}</td>
                                        <td style="text-align: center;font-weight: bold;">
                                            {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_jenis_rapor . 'nilai']) ? round($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_jenis_rapor . 'nilai']) : '' }}
                                        </td>
                                        <td style="text-align: center;font-weight: bold;">
                                            {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_jenis_rapor . 'predikat']) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_jenis_rapor . 'predikat'] : '' }}
                                        </td>
                                        <td>
                                            {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_jenis_rapor . 'keterangan']) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_jenis_rapor . 'keterangan'] : '' }}
                                        </td>
                                    </tr>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <br>
                @if ($noKomponen != '0')
                    <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;border-style : hidden">

                        <tr>
                            <td><b>Tabel Interval predikat berdasarkan KKM</b></td>
                        </tr>
                    </table>
                    <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">

                        <tr>
                            <th style="font-weight: bold;" rowspan="2">KKM</th>
                            <th style="font-weight: bold;" colspan="4">Predikat</th>
                        </tr>
                        <tr>
                            <th>Kurang (D)</th>
                            <th>Cukup (C)</th>
                            <th>Baik (B)</th>
                            <th>Sangat Baik (A)</th>
                        </tr>
                        <tr>
                            <td style="text-align: center;">70</td>
                            <td style="text-align: center;">
                                < 70</td>
                            <td style="text-align: center;">70 - 80</td>
                            <td style="text-align: center;">81 - 90</td>
                            <td style="text-align: center;">91 - 100</td>
                        </tr>
                    </table>
                @endif
                <br>
                <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
                    <tr>
                        <td style="border-style : hidden; width:35%;" align="left">
                        </td>
                        <td style="width:30%; border-style : hidden;" align="left">
                        </td>
                        <td style="width: 35%;">
                            {{ 'Surabaya, 21 Desember 2023' }}
                            <br>
                            Wali Kelas
                            <br><br><br><br><br><br><br>
                            @if (isset($wali_kelas->guru->pengguna->nm_pengguna))
                                <b><u> {{ $wali_kelas->guru->pengguna->gelar_depan }}
                                        {{ $wali_kelas->guru->pengguna->nm_pengguna }}
                                        {{ $wali_kelas->guru->pengguna->gelar_belakang }}</u></b>
                            @else
                                <p
                                    style="width: 250px;
                                        border-bottom: 1px solid   black;">
                                </p>
                            @endif

                        </td>
                    </tr>
                </table>
            </div>
        @endforeach
        <div class="page">
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
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
                    <td style="border-style : hidden;width: 14%;">NO Induk/NISN
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;">
                        {{ $siswa->nis_siswa . '/' . $siswa->nisn_siswa }}
                    </td>
                </tr>
                <tr>
                </tr>
            </table>
            <br>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;border-style : hidden">
                <tr>
                    <td><b>D. Ekstrakurikuler</b></td>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
                <tr>
                    <th>No</th>
                    <th>Kegiatan Ekstrakurikuler</th>
                    <th>Keterangan</th>
                </tr>

                @php
                    $no_ekskul = 0;
                @endphp
                @foreach ($ekskul_tambahan_rapor as $ekskul)
                    @if (isset($tambahan['ekskul'][$siswa->id_siswa][$ekskul->id_tambahan_rapor]))
                        <tr>
                            <td style="text-align: center;padding: 10px;width:10%">{{ ++$no_ekskul }}</td>
                            <td style="padding: 10px; width:30%">{{ $ekskul->nm_tambahan_rapor }}</td>
                            <td style="padding: 10px; width:50%">
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
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;border-style : hidden">
                <tr>
                    <td><b>E. Prestasi</b></td>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; ">
                <tr>
                    <th style="text-align: center;padding: 10px;width:10%">No</th>
                    <th style="padding: 10px; width:30%">Jenis Prestasi</th>
                    <th style="padding: 10px; width:50%">Keterangan</th>
                </tr>
                <tr>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
            </table>
            <br>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
                <tr>
                    <td><b>F. Ketidakhadiran</b></td>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; ">
                @foreach ($kehadiran_tambahan_rapor as $k)
                    <tr>
                        <td style="padding: 10px">
                            {{ $k->nm_tambahan_rapor }}
                        </td>

                        <td style="padding: 10px">
                            {{ isset($tambahan['ketidakhadiran'][$siswa->id_siswa][$k->id_tambahan_rapor]) ? $tambahan['ketidakhadiran'][$siswa->id_siswa][$k->id_tambahan_rapor] . ' Hari' : '0 Hari' }}
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
                    <td><b>G. Catatan Walikelas</b></td>
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
                    <td><b>H. Tanggapan Orang Tua</b></td>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
                <tr>
                    <td style="padding: 10px;height:50px">


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
                    <td style="width:30%; border-style : hidden;" align="left">

                    </td>
                    <td style="width: 35%;">
                        {{ 'Surabaya, 21 Desember 2023' }}
                        <br>
                        Wali Kelas
                        <br><br><br><br><br><br><br>
                        @if (isset($wali_kelas->guru->pengguna->nm_pengguna))
                            <b><u> {{ $wali_kelas->guru->pengguna->gelar_depan }}
                                    {{ $wali_kelas->guru->pengguna->nm_pengguna }}
                                    {{ $wali_kelas->guru->pengguna->gelar_belakang }}</u></b>
                        @else
                            <p
                                style="width: 250px;
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
