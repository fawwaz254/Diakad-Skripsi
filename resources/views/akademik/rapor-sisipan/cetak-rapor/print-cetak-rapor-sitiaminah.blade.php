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
                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 14%;font-weight: bold;">Nama Sekolah
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;font-weight: bold;">
                        {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}
                    </td>
                    <td style="border-style : hidden;width: 24%;font-weight: bold;">Kelas
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;"> :
                    </td>
                    <td style="border-style : hidden;width: 25%;font-weight: bold; ">
                        {{ $kelas->nm_kelas }}
                    </td>
                </tr>


                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 14%;font-weight: bold;vertical-align: top;">Alamat
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;vertical-align: top;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;font-weight: bold;vertical-align: top;">
                        Perum Gunung Sari Indah Blok CC, Kedurus Karangpilang Surabaya
                    </td>
                    <td style="border-style : hidden;width: 24%;font-weight: bold;vertical-align: top;">Semester
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;vertical-align: top;"> :
                    </td>
                    <td style="border-style : hidden;width: 25%;font-weight: bold;vertical-align: top; ">
                        {{ $semester->nm_semester }}
                    </td>
                </tr>

                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 14%;font-weight: bold;">Nama Siswa
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;font-weight: bold;">
                        {{ $siswa->pengguna->nm_pengguna }}
                    </td>
                    <td style="border-style : hidden;width: 24%;font-weight: bold;">Tahun Pelajaran
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;"> :
                    </td>
                    <td style="border-style : hidden;width: 25%;font-weight: bold; ">
                        {{ $semester->tahun_ajaran }}
                    </td>
                </tr>

                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 14%;font-weight: bold;">NO Induk
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;font-weight: bold;">
                        {{ $siswa->nis_siswa }}
                    </td>
                </tr>
            </table>

            <br>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
                <tr style="font-weight: bold;border-style : hidden">
                    <td>CAPAIAN HASIL BELAJAR</td>
                </tr>
                <br>
                <tr style="font-weight: bold; border-style : hidden">
                    <td>A. Sikap</td>
                </tr>
                <tr></tr>
                <tr>
                    <td style="border-style: solid">Deskripsi :
                        <br>
                        Selalu Bersyukur, selalu berdoa sebelum melakukan kegiatan, toleran pada agama yang berbeda dan
                        perlu meningkatkan ketaatan beribadah serta selalu bersikap sikap santun, peduli, percaya diri,
                        dan perlu meningkatkan sikap jujur, disiplin dan tanggung jawab.
                    </td>
                </tr>
                <tr></tr>
            </table>
            <br>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;border-style : hidden">
                <tr style="font-weight: bold;">
                    <td>B. Pengetahuan dan Ketrampilan</td>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
                <thead class="head" style="background-color: #ffffcc">
                    <tr>
                        <td rowspan="2" style="text-align: center;font-weight: bold;">NO</td>
                        <td rowspan="2" style="text-align: center;font-weight: bold;">KOMPONEN<br></td>
                        <td rowspan="2" style="text-align: center;font-weight: bold;">KKM</td>
                        @foreach ($list_komponen as $komponen)
                            <td colspan="2" style="text-align: center;font-weight: bold;">{{ $komponen->nm_nilai }}
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td style="text-align: center;font-weight: bold;">Angka</td>
                        <td style="text-align: center;font-weight: bold;">Predikat</td>
                        <td style="text-align: center;font-weight: bold;">Angka</td>
                        <td style="text-align: center;font-weight: bold;">Predikat</td>
                    </tr>
                </thead>
                {{-- <br> --}}
                <tbody class="body">

                    @foreach ($data as $kelompok)
                        <tr>
                            <td colspan="2" style="font-weight: bold;">
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
                                    <td style="text-align: center;">{{ $data2['kkm'][0] }}</td>
                                    @foreach ($list_komponen as $komponen)
                                        <td style="text-align: center;font-weight: bold;">
                                            <span @if (isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_nilai]) &&
                                                    isset($data2['kkm'][0]) &&
                                                    $data2['kkm'][0] != '0' &&
                                                    $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_nilai] <
                                                        $data2['kkm'][0]
                                            ) style="color:red" @endif>
                                                {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_nilai]) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_nilai] : '' }}</span>
                                        </td>
                                        <td style="text-align: center;font-weight: bold;">
                                            <span @if (isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_nilai]) &&
                                                    isset($data2['kkm'][0]) &&
                                                    $data2['kkm'][0] != '0' &&
                                                    $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_nilai] <
                                                        $data2['kkm'][0]
                                            ) style="color:red" @endif>
                                                {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_nilai . 'predikat']) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_nilai . 'predikat'] : '' }}
                                            </span>
                                        </td>
                                    @endforeach
                                </tr>
                                @for ($i = 1; $i < $jumlah; $i++)
                                    <tr>
                                        <td></td>
                                        <td>{{ $data2['nm_point'][$i] }}</td>
                                        <td style="text-align: center;">{{ $data2['kkm'][$i] }}</td>
                                        @foreach ($list_komponen as $komponen)
                                            <td style="text-align: center;font-weight: bold;">
                                                <span @if (isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_nilai]) &&
                                                        isset($data2['kkm'][$i]) &&
                                                        $data2['kkm'][$i] != '0' &&
                                                        $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_nilai] <
                                                            $data2['kkm'][$i]
                                                ) style="color:red" @endif>
                                                    {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_nilai . 'predikat']) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_nilai . 'predikat'] : '' }}
                                                </span>
                                            </td>
                                            <td style="text-align: center;font-weight: bold;">
                                                <span @if (isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_nilai]) &&
                                                        isset($data2['kkm'][$i]) &&
                                                        $data2['kkm'][$i] != '0' &&
                                                        $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_nilai] <
                                                            $data2['kkm'][$i]
                                                ) style="color:red" @endif>
                                                    {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_nilai . 'predikat']) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_nilai . 'predikat'] : '' }}
                                                </span>
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
    @endforeach
    {{-- <br><br>
        <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
            <tr>
                <td style=" border-style : hidden; width:25%; vertical-align: text-top; padding:0" align="center">
                    Mengetahui,
                    <br>
                    Kepala Sekolah
                    <br><br><br><br><br><br><br>
                    {{ $auth_data->sekolah_data->nm_kepala_sekolah }}
                </td>
                <td style="width:50%; border-style : hidden"></td>

                <td style="width:25%" align="center">Sidoarjo,
                    {{ indonesiaDate(\Carbon\Carbon::now()->format('Y-m-d')) }}
                    <br>
                    Wali Kelas
                    <br><br><br><br><br><br><br>
                    @if (isset($wali_kelas->guru->pengguna->nm_pengguna))
                        {{ $wali_kelas->guru->pengguna->gelar_depan }}
                        {{ $wali_kelas->guru->pengguna->nm_pengguna }}
                        {{ $wali_kelas->guru->pengguna->gelar_belakang }}
                    @else
                        <p style="width: 250px;
								border-bottom: 1px solid   black;"></p>
                    @endif
                </td>
            </tr>
        </table>
    </div> --}}

</body>
<script>
    window.print();
</script>

</html>
