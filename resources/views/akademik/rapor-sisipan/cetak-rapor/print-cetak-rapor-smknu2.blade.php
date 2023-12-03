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
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;" style="border-style : hidden">
                <tr>
                    <td colspan="10" style="border-style : hidden">
                        <h2 align="center" style="margin-top: 3px; font-family: Arial;word-spacing: 10px;">
                            LAPORAN PENILAIAN TENGAH SEMESTER
                        </h2>
                        <br>
                    </td>
                </tr>



                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 14%;font-weight: bold;">Nama Siswa
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;font-weight: bold;  text-transform: capitalize;">
                        {{ $siswa->pengguna->nm_pengguna }}
                    </td>
                    <td style="border-style : hidden;width: 24%;font-weight: bold;">Kelas
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;"> :
                    </td>
                    <td style="border-style : hidden;width: 25%;font-weight: bold;">
                        {{ $kelas->nm_kelas }}
                    </td>
                </tr>

                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 14%;font-weight: bold;">NIS
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;font-weight: bold; ">{{ $siswa->nis_siswa }}
                    </td>
                    <td style="border-style : hidden;width: 24%;font-weight: bold;">Semester
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;"> :
                    </td>
                    <td style="border-style : hidden;width: 25%;font-weight: bold;">
                        {{ $semester->nm_semester }}
                    </td>
                </tr>

                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 14%;font-weight: bold;">Nama Sekolah
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;font-weight: bold; ">SMK NAHDLATUL ULAMA LEKOK
                    </td>
                    <td style="border-style : hidden;width: 24%;font-weight: bold;">Tahun Pelajaran
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;"> :
                    </td>
                    <td style="border-style : hidden;width: 25%;font-weight: bold;">
                        {{ $semester->tahun_ajaran }}
                    </td>
                </tr>


                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 14%;font-weight: bold;">Alamat
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;font-weight: bold; ">Jl.Kabupaten No.72 Lekok
                    </td>
                    <td style="border-style : hidden;width: 24%;font-weight: bold;">
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;">
                    </td>
                    <td style="border-style : hidden;width: 25%;font-weight: bold;">

                    </td>
                </tr>


            </table>

            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
                <thead class="head" style="background-color: #C2D69B">
                    <tr>
                        <th rowspan="3">No</th>
                        <th rowspan="3">Mata Pelajaran</th>
                        <th rowspan="3">KKM<br></th>
                        <th colspan="10">Hasil Penilaian Harian (HPH)</th>
                        <th rowspan="3">HP<br>TS</th>
                    </tr>
                    <tr>
                        <th colspan="5">Pengetahuan</th>
                        <th colspan="5">Keterampilan</th>
                    </tr>
                    <tr>

                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>4</th>
                        <th>5</th>
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>4</th>
                        <th>5</th>
                    </tr>
                </thead>
                <br>
                <tbody class="body">

                    @foreach ($data as $kelompok)
                        @if ($kelompok['nama'] == 'Kelompok A ( UMUM )')
                            @php
                                $kelompok['nama'] = 'Muatan Nasional';
                            @endphp
                        @elseif($kelompok['nama'] == 'Kelompok B ( UMUM )')
                            @php
                                $kelompok['nama'] = 'Muatan Lokal';
                            @endphp
                        @elseif ($kelompok['nama'] == 'Kelompok C ( Peminatan )')
                            @php
                                $kelompok['nama'] = 'Kompetensi Keahlian';
                            @endphp
                        @endif
                        <tr>
                            <td colspan="2" style="font-weight: bold;">
                                {{ $kelompok['nama'] }}</td>
                        </tr>
                        @if (isset($kelompok['data']))
                            @foreach ($kelompok['data'] as $key => $data2)
                                @php
                                    $jumlah = count($data2['nm_point']);
                                @endphp
                                <tr>
                                    <td style="text-align: center;">{{ $key }}</td>
                                    <td>{{ $data2['nm_point'][0] }}</td>
                                    <td style="text-align: center;">{{ $data2['kkm'][0] }}</td>
                                    @foreach ($list_komponen as $komponen)
                                        <td style="text-align: center;">
                                            {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_nilai]) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_nilai] : '' }}
                                        </td>
                                    @endforeach
                                </tr>
                                @for ($i = 1; $i < $jumlah; $i++)
                                    <tr>
                                        <td></td>
                                        <td>{{ $data2['nm_point'][$i] }}</td>
                                        <td style="text-align: center;">{{ $data2['kkm'][$i] }}</td>
                                        @foreach ($list_komponen as $komponen)
                                            <td style="text-align: center;">
                                                {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_nilai]) && $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_nilai] != '0' ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_nilai] : '' }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endfor
                                </tr>
                            @endforeach
                        @endif
                    @endforeach


                </tbody>
            </table>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;border:none;">
                <tr>
                    <td style="border: none">HPTS= Hasil Penilaian Tengah Semester (khusus pada aspek Pengetahuan)</td>
                </tr>
            </table>
            <br>
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
            <br>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
                <tr>
                    <td style=" border-style : hidden; width:25%; vertical-align: text-top; padding:0" align="left">
                        Mengetahui,
                        <br>
                        Orang Tua/Wali,
                        <br><br><br><br><br><br><br>
                        <p style="width: 250px;
                                border-bottom: 2px solid   black;">
                        </p>
                        {{-- {{ $auth_data->sekolah_data->nm_kepala_sekolah }} --}}
                    </td>
                    <td style="width:45%; border-style : hidden"></td>

                    <td style="width:30%" align="left">Pasuruan,
                        {{ indonesiaDate(\Carbon\Carbon::now()->format('Y-m-d')) }}

                        <br>
                        Wali Kelas,
                        <br><br><br><br><br><br><br>
                        @if (isset($wali_kelas->guru->pengguna->nm_pengguna))
                            <u><b> {{ $wali_kelas->guru->pengguna->gelar_depan }}
                                    {{ $wali_kelas->guru->pengguna->nm_pengguna }}
                                    {{ $wali_kelas->guru->pengguna->gelar_belakang }}<b></u>
                        @else
                            <p
                                style="width: 250px;
                                        border-bottom: 2px solid   black;">
                            </p>
                        @endif
                    </td>
                </tr>
                <td style=" width:25%; border-style : hidden;"></td>
                <td style="width:45%; border-style : hidden;text-align: center">Mengetahui, <br>
                    Kepala Madrasah
                    <br><br><br><br><br><br><br>
                    <u><b>
                            {{ $auth_data->sekolah_data->nm_kepala_sekolah }}<b></u>
                </td>
                <td style="width:30%; border-style : hidden"> </td>
            </table>
        </div>
    @endforeach
    <br><br>

    </div>

</body>
<script>
    window.print();
</script>

</html>
