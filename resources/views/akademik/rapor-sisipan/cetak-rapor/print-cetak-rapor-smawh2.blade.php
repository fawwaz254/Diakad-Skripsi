<!DOCTYPE html>
<html lang="eng">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapor Sisipan STS ({{ $kelas->nm_kelas }})</title>


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

        .capitalize {
            text-transform: capitalize;
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
                <tr width="90%" style="background-color: black;color:white">
                    <img src="{{ asset('media/ttd/kop_surat_smawh2.png') }}" alt="kop_surat" style="width: 100%">
                </tr>
            </table>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;" style="border-style : hidden">
                <tr>
                    <td colspan="10" style="border-style : hidden">
                        <br>
                        <h2
                            style="margin-top: 3px; font-family:'Times New Roman', Times, serif; font-size:30px; text-align:center">
                            LAPORAN HASIL BELAJAR SISWA MURNI
                            <br>
                            <u>
                                SEMESTER
                                @if ($semester->nm_semester == 'Ganjil')
                                    GANJIL
                                @else
                                    GENAP
                                @endif
                            </u>
                        </h2>
                    </td>
                <tr>

                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 15%;">Nama Sekolah
                    </td>
                    <td style="border-style : hidden;width: 35%;"> :
                        {{ $auth_data->sekolah_data->nm_sekolah }}

                    </td>
                    <td style="border-style : hidden;width: 25%;">Kelas
                    </td>
                    <td style="border-style : hidden;width: 25%; "> :
                        {{ $kelas->nm_kelas }}
                    </td>
                </tr>
                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 15%;">Nama
                    </td>
                    <td class="capitalize" style="border-style : hidden;width: 35%;"> :
                        {{ $siswa->pengguna->nm_pengguna }}
                    </td>
                    <td style="border-style : hidden;width: 25%;">Semester
                    </td>
                    <td style="border-style : hidden;width: 25%;"> :
                        @if ($semester->nm_semester == 'Ganjil')
                            I
                        @else
                            II
                        @endif
                    </td>
                </tr>
                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 15%;">No. Induk
                    </td>
                    <td style="border-style : hidden;width: 35%;"> : {{ $siswa->nis_siswa }}
                    </td>
                    <td style="border-style : hidden;width: 25%;">Tahun Ajaran
                    </td>
                    <td style="border-style : hidden;width: 25%;"> : {{ $semester->tahun_ajaran }}
                    </td>
                </tr>
            </table>

            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
                <thead class="head" style="background-color: #C2D69B">
                    <tr>
                        <td colspan="2" rowspan="2" style="text-align: center;font-weight: bold;">MATA
                            PELAJARAN<br></td>
                        <td colspan="6" style="text-align: center;font-weight: bold;">NILAI FORMATIF</td>
                        <td rowspan="2" style="text-align: center;font-weight: bold;">SUMATIF<br>TENGAH<br>SEMESTER
                            <br> (STS)
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center;font-weight: bold;">UH/TP 1</td>
                        <td style="text-align: center;font-weight: bold;">UH/TP 2</td>
                        <td style="text-align: center;font-weight: bold;">UH/TP 3</td>
                        <td style="text-align: center;font-weight: bold;">UH/TP 1</td>
                        <td style="text-align: center;font-weight: bold;">UH/TP 2</td>
                        <td style="text-align: center;font-weight: bold;">UH/TP 3</td>
                    </tr>
                </thead>
                <br>
                <tbody class="body">
                    @php
                        $jumlah_nilai = [];
                        $count_nilai = [];
                    @endphp
                    @foreach ($data as $kelompok)
                        <tr>
                            <td colspan="{{ 2 + count($list_komponen) }}" style="font-weight: bold;">
                                {{ $kelompok['nama'] ?? '' }}
                            </td>
                        </tr>
                        @if (!empty($kelompok['data']))
                            @foreach ($kelompok['data'] as $key => $data2)
                                @php
                                    $jumlah = count($data2['nm_point']);
                                @endphp
                                <tr>
                                    <td style="text-align: center;">{{ $key }}</td>
                                    <td>{{ $data2['nm_point'][0] }}</td>
                                    @foreach ($list_komponen as $komponen)
                                        <td style="text-align: center; font-weight: bold;">
                                            @php
                                                $nilai_satuan = isset(
                                                    $nilai_siswa[
                                                        $siswa->id_siswa .
                                                            $data2['id_mata_pelajaran'][0] .
                                                            $komponen->id_komponen_jenis_rapor
                                                    ],
                                                )
                                                    ? (int) $nilai_siswa[
                                                        $siswa->id_siswa .
                                                            $data2['id_mata_pelajaran'][0] .
                                                            $komponen->id_komponen_jenis_rapor
                                                    ]
                                                    : 0;
                                                if (
                                                    array_key_exists($komponen->id_komponen_jenis_rapor, $jumlah_nilai)
                                                ) {
                                                    $jumlah_nilai[$komponen->id_komponen_jenis_rapor] += $nilai_satuan;
                                                } else {
                                                    $jumlah_nilai[$komponen->id_komponen_jenis_rapor] = $nilai_satuan;
                                                }

                                                if (
                                                    array_key_exists($komponen->id_komponen_jenis_rapor, $count_nilai)
                                                ) {
                                                    $count_nilai[$komponen->id_komponen_jenis_rapor] +=
                                                        $nilai_satuan > 0 ? 1 : 0;
                                                } else {
                                                    $count_nilai[$komponen->id_komponen_jenis_rapor] =
                                                        $nilai_satuan > 0 ? 1 : 0;
                                                }
                                            @endphp
                                            {{ $nilai_satuan > 0 ? $nilai_satuan : '' }}
                                        </td>
                                    @endforeach
                                </tr>
                                @for ($i = 1; $i < $jumlah; $i++)
                                    <tr>
                                        <td></td>
                                        <td>{{ $data2['nm_point'][$i] }}</td>
                                        @foreach ($list_komponen as $komponen)
                                            <td style="text-align: center; font-weight: bold;">
                                                {{ $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_jenis_rapor] ?? '' }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endfor
                            @endforeach
                        @endif
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan=2 style="font-weight: bold; text-align:center;">
                            JUMLAH
                        </td>
                        @foreach ($list_komponen as $komponen)
                            <td style="text-align: center; font-weight: bold;">
                                {{ isset($jumlah_nilai[$komponen->id_komponen_jenis_rapor]) ? $jumlah_nilai[$komponen->id_komponen_jenis_rapor] : '0' }}
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td colspan=2 style="font-weight: bold; text-align:center;">
                            RATA-RATA
                        </td>
                        @foreach ($list_komponen as $komponen)
                            <td style="text-align: center; font-weight: bold;">
                                @if ($count_nilai[$komponen->id_komponen_jenis_rapor] > 0)
                                    {{ isset($jumlah_nilai[$komponen->id_komponen_jenis_rapor]) ? round($jumlah_nilai[$komponen->id_komponen_jenis_rapor] / $count_nilai[$komponen->id_komponen_jenis_rapor]) : '0' }}
                                @else
                                    0
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td colspan=2 style="font-weight: bold; text-align:center;">
                            RANGKING
                        </td>
                        @foreach ($list_komponen as $komponen)
                            <td style="text-align: center; font-weight: bold;">
                            </td>
                        @endforeach
                    </tr>
                </tfoot>
            </table>

            <table style="width: 90%; margin-left:10%; margin-top:20px">
                <tr style="font-weight:bold;border-style : hidden;">
                    <td width="20%" style="border-style : hidden;">
                        KETIDAKHADIRAN
                    </td>
                    <td width="15%" style="border-style : hidden;"></td>
                    <td style="border-style : hidden;">CATATAN
                    </td>
                </tr>
                <tr style="border-style : hidden;">
                    <td style="border-style : hidden;">
                        Sakit
                        <br>
                        Izin
                        <br>
                        Tanpa Keterangan
                    </td>
                    <td> :
                        {{ isset($nilai_pengembangan_diri[$siswa->id_siswa . '1']) ? $nilai_pengembangan_diri[$siswa->id_siswa . '1'] : '0' }}
                        hari<br>
                        :
                        {{ isset($nilai_pengembangan_diri[$siswa->id_siswa . '2']) ? $nilai_pengembangan_diri[$siswa->id_siswa . '2'] : '0' }}
                        hari<br>
                        :
                        {{ isset($nilai_pengembangan_diri[$siswa->id_siswa . '3']) ? $nilai_pengembangan_diri[$siswa->id_siswa . '3'] : '0' }}
                        hari</td>
                    <td style="border-style : hidden;">
                        @foreach ($pribadi_sisipan_catatan_orang_tua as $key => $k)
                            <p>
                                @if (isset($nilai_pengembangan_diri[$siswa->id_siswa . $k->id_pribadi_sisipan]))
                                    {{ $nilai_pengembangan_diri[$siswa->id_siswa . $k->id_pribadi_sisipan] }}
                                @else
                                    -
                                @endif
                            </p>
                        @endforeach
                    </td>
                </tr>
            </table>
            <br><br>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin-left:10%; border-style : hidden">
                <tr>
                    <td style=" border-style : hidden; width:15%; vertical-align: text-top; padding:0">
                        <br>
                        Orang Tua/Wali,
                        <br><br><br><br><br><br><br>
                        <p style="width: 250px;
                    border-bottom: 1px solid   black;"></p>
                    </td>
                    <td style="width:40%; border-style : hidden"></td>

                    <td style="width:25%; position: relative;" align="center">Sidoarjo,
                        {{ $tanggal_cetak }}
                        <br>
                        Wali Kelas
                        <img style="position: absolute; top: 20%; left:27%"
                            src="{{ Storage::disk('spaces')->url($guru->path_foto_ttd) }}" alt="TTD"
                            width="120px" height="120px">
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
                <tr>
                    <td></td>
                    <td align="center" style="border-style : hidden; position: relative;">Mengetahui<br>Kepala
                        Sekolah,
                        <img style="position: absolute; margin-left:-113px; margin-top: 20px "
                            src="{{ asset('media/ttd/qr_kepsek_smawh2.png') }}" alt="TTD" width="100px"
                            height="100px" class="ttd">
                        <br><br><br><br><br>
                        <br>
                        <br>
                        <u><b>
                                {{ $auth_data->sekolah_data->nm_kepala_sekolah }}</b></u>
                    </td>
                    <td></td>
                </tr>

            </table>

        </div>
    @endforeach
</body>
{{-- <script>
    window.print();
</script> --}}

</html>
