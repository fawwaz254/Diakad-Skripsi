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
                        <h2 align="center" style="margin-top: 3px; font-family: Georgia;word-spacing: 10px;">
                            RAPOR TENGAH SEMESTER
                        </h2>
                        <br>
                    </td>
                </tr>

                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 14%;font-weight: bold;">Nama Sekolah
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;font-weight: bold;">
                        {{ $auth_data->sekolah_data->nm_sekolah }}
                    </td>
                    <td style="border-style : hidden;width: 24%;font-weight: bold;">Kelas/Program
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;"> :
                    </td>
                    <td style="border-style : hidden;width: 25%;font-weight: bold; ">
                        {{ $kelas->nm_kelas }}
                    </td>
                </tr>

                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 14%;font-weight: bold;">Alamat
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;font-weight: bold; font-size: 13px">
                        Jl. Manyar Sambongan No.119 SURABAYA
                    </td>
                    <td style="border-style : hidden;width: 24%;font-weight: bold;">Semester
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;"> :
                    </td>
                    <td style="border-style : hidden;width: 25%;font-weight: bold; ">
                        {{ $semester->nm_semester }}
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
                    <td style="border-style : hidden;width: 24%;font-weight: bold;">Tahun Pelajaran
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;"> :
                    </td>
                    <td style="border-style : hidden;width: 25%;font-weight: bold;">
                        {{ $semester->tahun_ajaran }}
                    </td>
                </tr>

                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 14%;font-weight: bold;">NIS
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;font-weight: bold; ">{{ $siswa->nis_siswa }}
                    </td>
                    <td style="border-style : hidden;width: 24%;font-weight: bold;">NISN
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;"> :
                    </td>
                    <td style="border-style : hidden;width: 25%;font-weight: bold;">
                        {{ $siswa->nisn_siswa }}
                    </td>
                </tr>


            </table>

            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
                <thead class="head" style="background-color: #C2D69B">
                    <tr>
                        <td rowspan="2" style="text-align: center;font-weight: bold;">NO</td>
                        <td rowspan="2" style="text-align: center;font-weight: bold;">KOMPONEN<br></td>
                        <td rowspan="2" style="text-align: center;font-weight: bold;">KKM</td>
                        <td colspan="2" style="text-align: center;font-weight: bold;">KD 3.1</td>
                        <td colspan="2" style="text-align: center;font-weight: bold;">KD 3.2</td>
                        <td colspan="2" style="text-align: center;font-weight: bold;">KD 4.1</td>
                        <td colspan="2" style="text-align: center;font-weight: bold;">KD 4.2</td>
                        <td rowspan="2" style="text-align: center;font-weight: bold;">Nilai <br> PHB</td>
                        <td rowspan="2" style="text-align: center;font-weight: bold;">SIKAP</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;font-weight: bold;">1</td>
                        <td style="text-align: center;font-weight: bold;">2</td>
                        <td style="text-align: center;font-weight: bold;">1</td>
                        <td style="text-align: center;font-weight: bold;">2</td>
                        <td style="text-align: center;font-weight: bold;">1</td>
                        <td style="text-align: center;font-weight: bold;">2</td>
                        <td style="text-align: center;font-weight: bold;">1</td>
                        <td style="text-align: center;font-weight: bold;">2</td>
                    </tr>
                </thead>
                <br>
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
                                        <td style="text-align: center;">
                                            {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_nilai]) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_nilai] : '-' }}
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
                                                {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_nilai]) && $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_nilai] != '0' ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_nilai] : '-' }}
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
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
                <tr>
                    <th align="center">PENGEMBANGAN DIRI</th>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; ">
                <tr>
                    <th align="center" style="width: 5%">1</th>
                    <th align="left">Kepribadian</th>
                    <th>NILAI</th>
                    <th align="center" style="width: 5%">3</th>
                    <th align="left">EKSTRA KURIKULER</th>
                    <th>NILAI</th>
                </tr>

                @foreach ($kelompok_pribadi_sisipan[0]->pribadi_sisipan as $key => $pribadi_sisipan)
                    <tr>
                        @if ($key == '0')
                            <td rowspan="{{ $kelompok_pribadi_sisipan[0]->pribadi_sisipan->count() }}"></td>
                        @endif
                        <td>{{ $pribadi_sisipan->nm_pribadi_sisipan }}</td>
                        <td align="center">
                            {{ isset($nilai_pengembangan_diri[$siswa->id_siswa . $pribadi_sisipan->id_pribadi_sisipan]) && $nilai_pengembangan_diri[$siswa->id_siswa . $pribadi_sisipan->id_pribadi_sisipan] != '0' ? $nilai_pengembangan_diri[$siswa->id_siswa . $pribadi_sisipan->id_pribadi_sisipan] : '' }}
                        </td>
                        @if ($key == '0')
                            <td rowspan="{{ $kelompok_pribadi_sisipan[0]->pribadi_sisipan->count() }}"></td>
                        @endif

                        @if (isset($nilai_ekskul[$siswa->id_siswa . 'nilai_ekskul'][$key]))
                            <td>{{ $nilai_ekskul[$siswa->id_siswa . 'nm_ekskul'][$key] }}</td>
                            <td align="center">{{ $nilai_ekskul[$siswa->id_siswa . 'nilai_ekskul'][$key] }}
                            </td>
                        @else
                            <td></td>
                            <td></td>
                        @endif


                    </tr>
                @endforeach
                <tr>
                    <th align="center">2</th>
                    <th align="left">Ketidak Hadiran</th>
                    <th align="center">Jumlah</th>
                    <th>4</th>
                    <th align="left" colspan="2">Catatan untuk Orang Tuaa</th>
                </tr>
                @foreach ($kelompok_pribadi_sisipan[1]->pribadi_sisipan as $key => $pribadi_sisipan)
                    <tr>
                        @if ($key == '0')
                            <td rowspan="{{ $kelompok_pribadi_sisipan[1]->pribadi_sisipan->count() }}"></td>
                        @endif
                        <td>{{ $pribadi_sisipan->nm_pribadi_sisipan }}</td>
                        <td align="center">
                            {{ isset($nilai_pengembangan_diri[$siswa->id_siswa . $pribadi_sisipan->id_pribadi_sisipan]) ? $nilai_pengembangan_diri[$siswa->id_siswa . $pribadi_sisipan->id_pribadi_sisipan] . ' Hari' : 0 . ' Hari' }}
                        </td>

                    </tr>
                @endforeach

            </table>
            <br>
            <br>




            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
                <tr>
                    <td style=" border-style : hidden; width:25%; vertical-align: text-top; padding:0" align="left">
                        Mengetahui,
                        <br>
                        Orang Tua Siswa / Wali
                        <br><br><br><br><br><br><br>
                        <p style="width: 250px;
                                border-bottom: 1px solid   black;">
                        </p>
                        {{-- {{ $auth_data->sekolah_data->nm_kepala_sekolah }} --}}
                    </td>
                    <td style="width:45%; border-style : hidden"></td>

                    <td style="width:30%" align="left">Surabaya,
                        {{ indonesiaDate(\Carbon\Carbon::now()->format('Y-m-d')) }}
                        <br>
                        Wali Kelas
                        <br><br><br><br><br><br><br>
                        @if (isset($wali_kelas->guru->pengguna->nm_pengguna))
                            {{ $wali_kelas->guru->pengguna->gelar_depan }}
                            {{ $wali_kelas->guru->pengguna->nm_pengguna }}
                            {{ $wali_kelas->guru->pengguna->gelar_belakang }}
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
    <br><br>

    </div>

</body>
<script>
    window.print();
</script>

</html>
