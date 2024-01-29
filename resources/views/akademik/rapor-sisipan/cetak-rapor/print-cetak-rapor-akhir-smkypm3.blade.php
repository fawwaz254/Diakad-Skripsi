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

                        <h2 align="center" style="margin-top: 3px">
                            LAPORAN PENILAIAN HASIL BELAJAR<br>
                            {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}<br>
                            TENGAH SEMESTER GASAL<br>
                            {{-- @php
                                $nama = $list_nilai->first();
                                // dd($nama->rapor_sisipan->semester->tahun_ajaran);
                                echo isset($nama->rapor_sisipan->semester->tahun_ajaran) ? 'TAHUN AJARAN ' . $nama->rapor_sisipan->semester->tahun_ajaran : '';
                            @endphp --}}

                        </h2>
                        <hr>
                        <br>
                    </td>
                <tr>

                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 14%;font-weight: bold;">NAMA SISWA
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;font-weight: bold;">
                        {{ $siswa->pengguna->nm_pengguna }}
                    </td>
                    <td style="border-style : hidden;width: 24%;font-weight: bold;">BIDANG KEAHLIAN
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;"> :
                    </td>
                    <td style="border-style : hidden;width: 25%;font-weight: bold; ">
                        {{ $kelas->jurusan->bidang_keahlian }}
                    </td>
                </tr>
                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 14%;font-weight: bold;">NO. INDUK
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;font-weight: bold;">{{ $siswa->nis_siswa }}
                    </td>
                    <td style="border-style : hidden;width: 24%;font-weight: bold;">PROGRAM KEAHLIAN
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;"> :
                    </td>`
                    <td style="border-style : hidden;width: 25%;font-weight: bold;">
                        {{ $kelas->jurusan->program_keahlian }}
                    </td>
                </tr>
                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 14%;font-weight: bold;">KELAS
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;font-weight: bold;">{{ $kelas->nm_kelas }}
                    </td>
                    <td style="border-style : hidden;width: 24%;font-weight: bold;">
                        @if ($kelas->tingkat == '1')
                            KONSENTRASI KEAHLIAN
                        @else
                            KOMPETENSI KEAHLIAN
                        @endif
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;"> :
                    </td>
                    <td style="border-style : hidden;width: 25%;font-weight: bold;">
                        {{ $kelas->jurusan->kompetensi_keahlian }}
                    </td>
                </tr>
            </table>

            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
                <thead class="head" style="background-color: #C2D69B">
                    <tr>
                        <td style="text-align: center;font-weight: bold;">NO</td>
                        <td style="text-align: center;font-weight: bold;">MATA PELAJARAN<br></td>
                        <td style="text-align: center;font-weight: bold;">KKM</td>
                        <td style="text-align: center;font-weight: bold;">NILAI UTS</td>
                        <td style="text-align: center;font-weight: bold;">NILAI UAS</td>
                    </tr>

                </thead>
                <br>
                <tbody class="body">

                    @foreach ($data as $kelompok)
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
                                            {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_jenis_rapor]) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_jenis_rapor] : '' }}
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
                                                {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_jenis_rapor]) && $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_jenis_rapor] != '0' ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_jenis_rapor] : '' }}
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
            {{-- <table style="width: 30%; margin-left:10%; margin-top:20px">
             <tr>
                <td colspan="4">
                    <p align="center" style="display: inline">
                        RAPOR =
                    </p>
                    <p align="center" style="display: inline" class="under-below">
                        {(2 x RT2 SMT)+(STS)}
                    </p>
                </td>
            </tr> 
        </table> --}}
            <br><br>
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
                        {{-- {{ $rapor_sisipan->pengguna->gelar_depan }} {{ $rapor_sisipan->pengguna->nm_pengguna }} {{ $rapor_sisipan->pengguna->gelar_belakang }} --}}
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
