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
                        <h3 align="center" style="margin-top: 3px">
                            REKAPITULASI NILAI ASLI<br>
                            @if ($kelas->tingkat == 2)
                                PENILAIAN TENGAH SEMESTER (PTS)
                            @else
                                SUMATIF TENGAH SEMESTER (STS)
                            @endif
                            <br>
                        </h3>
                        <br>
                    </td>
                </tr>

                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 14%;">NAMA SISWA
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;">
                        {{ $siswa->pengguna->nm_pengguna }}
                    </td>
                    <td style="border-style : hidden;width: 14%;">KELAS
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;">{{ $kelas->nm_kelas }}
                    </td>
                </tr>

                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 14%;">NO. INDUK
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;">{{ $siswa->nis_siswa }}
                    </td>
                    <td style="border-style : hidden;width: 24%;">Tahun Pelajaran
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 25%;">
                        {{ $semester->tahun_ajaran . ' ' . $semester->nm_semester }}
                    </td>
                </tr>
            </table>

            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
                <thead class="head" style="background-color: #99cccc">
                    <tr>
                        <th rowspan="2"style="text-align: center;font-weight: bold;">No</th>
                        <th rowspan="2" style="text-align: center;font-weight: bold;">Mata Pelajaran</th>
                        <th rowspan="2" style="text-align: center;font-weight: bold;">KKM</th>
                        <th colspan="{{ $jumlah_komponen }}" style="text-align: center;font-weight: bold;">Nilai
                            Hasil Belajar</th>
                        <th rowspan="2" style="text-align: center;font-weight: bold;">RATA RATA</th>
                        <th rowspan="2" style="text-align: center;font-weight: bold;">KETERANGAN</th>
                    </tr>
                    <tr>
                        @foreach ($list_komponen as $komponen)
                            <th style="text-align: center;font-weight: bold;">{{ $komponen->nm_komponen_jenis_rapor }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <br>
                <tbody class="body">

                    @foreach ($data as $kelompok)
                        <tr>
                            <td colspan="8" style="font-weight: bold; background-color: #ccffff">
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
                                    <td style="text-align: center;">
                                        {{ isset($data2['kkm'][0]) ? $data2['kkm'][0] : '' }}
                                    </td>
                                    @foreach ($list_komponen as $komponen)
                                        <td style="text-align: center;">
                                            {{ isset($nilai_siswa[$siswa->id_siswa][$data2['id_mata_pelajaran'][0]][$komponen->id_komponen_jenis_rapor]) ? $nilai_siswa[$siswa->id_siswa][$data2['id_mata_pelajaran'][0]][$komponen->id_komponen_jenis_rapor] : '' }}
                                        </td>
                                    @endforeach
                                    <td style="text-align: center;">
                                        {{ isset($rata_rata_nilai[$siswa->id_siswa][$data2['id_mata_pelajaran'][0]]) ? $rata_rata_nilai[$siswa->id_siswa][$data2['id_mata_pelajaran'][0]] : '' }}
                                    </td>
                                    <td style="text-align: center;">
                                        @php
                                            $keterangan = '';
                                            if (
                                                isset(
                                                    $rata_rata_nilai[$siswa->id_siswa][$data2['id_mata_pelajaran'][0]],
                                                ) &&
                                                isset($data2['kkm'][0])
                                            ) {
                                                if (
                                                    $rata_rata_nilai[$siswa->id_siswa][
                                                        $data2['id_mata_pelajaran'][0]
                                                    ] >= $data2['kkm'][0]
                                                ) {
                                                    $keterangan = 'Tuntas';
                                                } elseif (
                                                    $rata_rata_nilai[$siswa->id_siswa][$data2['id_mata_pelajaran'][0]] <
                                                    $data2['kkm'][0]
                                                ) {
                                                    $keterangan = 'Tidak Tuntas';
                                                }
                                            }
                                        @endphp
                                        {{ $keterangan }}
                                    </td>
                                </tr>
                                @for ($i = 1; $i < $jumlah; $i++)
                                    <tr>
                                        <td></td>
                                        <td>{{ $data2['nm_point'][$i] }}</td>
                                        <td style="text-align: center;">
                                            {{ isset($data2['kkm'][$i]) ? $data2['kkm'][$i] : '' }}
                                        </td>
                                        @foreach ($list_komponen as $komponen)
                                            <td style="text-align: center;">
                                                {{ isset($nilai_siswa[$siswa->id_siswa][$data2['id_mata_pelajaran'][$i]][$komponen->id_komponen_jenis_rapor]) ? $nilai_siswa[$siswa->id_siswa][$data2['id_mata_pelajaran'][$i]][$komponen->id_komponen_jenis_rapor] : '' }}
                                            </td>
                                        @endforeach
                                        <td style="text-align: center;">
                                            {{ isset($rata_rata_nilai[$siswa->id_siswa][$data2['id_mata_pelajaran'][$i]]) ? $rata_rata_nilai[$siswa->id_siswa][$data2['id_mata_pelajaran'][$i]] : '' }}
                                        </td>
                                        <td style="text-align: center;">
                                            @php
                                                $keterangan = '';
                                                if (
                                                    isset(
                                                        $rata_rata_nilai[$siswa->id_siswa][
                                                            $data2['id_mata_pelajaran'][$i]
                                                        ],
                                                    ) &&
                                                    isset($data2['kkm'][$i])
                                                ) {
                                                    if (
                                                        $rata_rata_nilai[$siswa->id_siswa][
                                                            $data2['id_mata_pelajaran'][$i]
                                                        ] >= $data2['kkm'][$i]
                                                    ) {
                                                        $keterangan = 'Tuntas';
                                                    } elseif (
                                                        $rata_rata_nilai[$siswa->id_siswa][
                                                            $data2['id_mata_pelajaran'][$i]
                                                        ] < $data2['kkm'][$i]
                                                    ) {
                                                        $keterangan = 'Tidak Tuntas';
                                                    }
                                                }
                                            @endphp
                                            {{ $keterangan }}
                                        </td>
                                    </tr>
                                @endfor
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                    <tr>
                        <td style="text-align: center;" colspan="6">
                            <h3>JUMLAH</h3>
                        </td>
                        <td style="text-align: center; font-weight: bold;" colspan="2">
                            {{ isset($total_nilai[$siswa->id_siswa]) ? $total_nilai[$siswa->id_siswa] : '' }}
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center;background-color: #99cccc;font-weight: bold;" colspan="2">
                            PERMINATAN KHUSUS</td>
                        <td style="text-align: center;background-color: #99cccc;font-weight: bold;" colspan="2">NILAI
                        </td>
                        <td style="text-align: center;font-weight: bold;" colspan="4" rowspan="2">Peringkat ke :
                            {{ array_search($siswa->id_siswa, array_keys($total_nilai)) + 1 }}
                            dari Siswa {{ $list_siswa->count() }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;" colspan="2">----</td>
                        <td style="text-align: center;" colspan="2">----</td>
                    </tr>
                </tbody>
            </table>
            <br>

            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;" style="border-style : hidden">
                <td style="vertical-align: top;border-style : hidden">
                    <table>
                        <tr style="background-color: #9999cc">
                            <th colspan="2">Ekstrakurikuler</th>
                            <th>Predikat</th>
                            <th>Keterangan</th>

                        </tr>

                        @if (isset($nilai_ekskul[$siswa->id_siswa . 'ekskul']))
                            @foreach ($nilai_ekskul[$siswa->id_siswa . 'ekskul'] as $key => $ekskul)
                                <tr>
                                    <td style="text-align: center;">{{ $key + 1 }}</td>
                                    <td> {{ $ekskul }}</td>
                                    <td style="text-align: center;">
                                        {{ $nilai_ekskul[$siswa->id_siswa . 'nilai_ekskul'][$key] }}</td>
                                    <td>
                                        {{ $nilai_ekskul[$siswa->id_siswa . 'keterangan_ekskul'][$key] }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td style="text-align: center;">-</td>
                                <td style="text-align: center;">-</td>
                                <td style="text-align: center;">-</td>
                                <td style="text-align: center;">-</td>
                            </tr>
                        @endif


                    </table>
                </td>

                <td style="vertical-align: top;border-style : hidden">
                    <table>
                        <tr style="background-color: #9999cc">
                            <th colspan="2">Absensi</th>
                        </tr>
                        @php
                            $jumlah = 0;
                        @endphp
                        @foreach ($pribadi_sisipan_kehadiran as $key => $k)
                            <tr>
                                <td>{{ $k->nm_pribadi_sisipan }}</td>
                                @if (isset($nilai_pengembangan_diri[$siswa->id_siswa . $k->id_pribadi_sisipan]))
                                    <td style="text-align: center;">
                                        @php
                                            $jumlah +=
                                                $nilai_pengembangan_diri[$siswa->id_siswa . $k->id_pribadi_sisipan];
                                        @endphp
                                        {{ $nilai_pengembangan_diri[$siswa->id_siswa . $k->id_pribadi_sisipan] }}</td>
                                @else
                                    <td style="text-align: center;">0 </td>
                                @endif
                            </tr>
                        @endforeach

                        <tr>
                            <td style="text-align: center; font-weight: bold;">Jumlah</td>
                            <td style="text-align: center;">{{ $jumlah }}</td>
                        </tr>
                    </table>
                </td>
                <table></table>
            </table>





            {{--
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;" style="border-style : hidden">
                <tr style="background-color: #9999cc">
                    <th colspan="2">Ekstrakurikuler</th>
                    <th>Predikat</th>
                    <th>Keterangan</th>
                    <th colspan="2">Absensi</th>
                </tr>
                <tr>
                    <td>1</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>1. Sakit</td>
                    <td style="text-align: center;">0</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>2. izin</td>
                    <td style="text-align: center;">0</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>3. Alpha</td>
                    <td style="text-align: center;">0</td>
                </tr>
                <tr>
                    <td colspan="4"> </td>
                    <td style="text-align: center; font-weight: bold;">Jumlah</td>
                    <td style="text-align: center;">0</td>
                </tr>
            </table> --}}
            <br>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
                <tr>
                    <td style="width: 70%; border-style : hidden;"></td>
                    <td style="border-style : hidden;">Diberikan di : Pasuruan</td>
                </tr>
                <tr>
                    <td style="width: 70%; border-style : hidden;"></td>
                    <td>Tanggal : 23 Desember 2023</td>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
                <tr>
                    <td style="border-style : hidden; width:35%;font-weight: bold;" align="left">
                        Orang Tua Siswa / Wali
                        <br><br><br><br><br><br><br>
                        <p style="width: 250px;
                                border-bottom: 1px solid   black;">
                        </p>
                    </td>
                    <td style="width:35%; border-style : hidden;font-weight: bold;" align="left">
                        Wali Kelas {{ $kelas->nm_kelas }}
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
                    <td style="width: 30%;font-weight: bold;">
                        Kepala Madrasah
                        <br><br><br><br><br><br><br>

                        Hasanul Bisri, M.Pd

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
