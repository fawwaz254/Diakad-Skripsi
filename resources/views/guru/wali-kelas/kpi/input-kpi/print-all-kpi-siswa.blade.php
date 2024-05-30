<!DOCTYPE html>
<html lang="eng">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Nilai Rapor STS</title>


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
            .break {
                page-break-after: always;

            }
        }

        '
    </style>
</head>

<body>

    @foreach ($list_siswa as $siswa)
        <div class="page">
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 20px  auto;" style="border-style : hidden">
                <tr>
                    <td colspan="10" style="border-style : hidden">
                        <h2 align="center" style="margin-top: 3px">
                            LAPORAN KECAKAPAN PENERAPAN IBADAH (KPI)<br>
                            <br>
                        </h2>
                    </td>
                <tr>
                    <br>

                <tr style="border-style : hidden">
                    <td style="border-style : hidden; width: 10%;">Nama Siswa
                    </td>
                    <td style="border-style : hidden;  width: 60%;">: <b>{{ $siswa->pengguna->nm_pengguna }}</b></td>

                    <td style="border-style : hidden; width: 10%;">Semester
                    </td>
                    <td style="border-style : hidden;  width: 20%;">: <b>
                            {{ $semester->tahun_ajaran . ' ' . $semester->nm_semester }}</b>
                    </td>
                </tr>
                <tr style="border-style : hidden;">
                    <td style="border-style : hidden; width: 10%;">NIS
                    </td>
                    <td style="border-style : hidden;  width: 60%;">: <b>{{ $siswa->nis_siswa }}</b></td>

                    <td style="border-style : hidden; width: 10%;">Kelas
                    </td>
                    <td style="border-style : hidden;  width: 20%;">: <b>{{ $siswa->kelas->nm_kelas }}</b>
                    </td>
                </tr>
            </table>
            <br>
            @php
                $abjad = range('A', 'Z');
                $last_key = 0;
            @endphp


            {{-- <h1>{{ $siswa->pengguna->nm_pengguna }}</h1> --}}

            @foreach ($siswa->kelompok_kpi as $key => $kelompok_kpi)
                @php
                    $last_key = $key;
                @endphp

                <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;border-style : hidden;">
                    <tr>
                        <td> <b>{{ $abjad[$key] . '. ' . $kelompok_kpi->nm_kelompok_kpi }}</b></td>
                    </tr>
                </table>

                {{-- <h3>{{ $kelompok_kpi->nm_kelompok_kpi }}</h3> --}}
                <table cellspacing="0" cellpadding="10"
                    style="width: 90%;  margin-top: 0;
			margin-bottom: 20px;
			margin-right: auto;
			margin-left: auto;">
                    <thead class="head">
                        <tr style="background-color: #e3e1e1">
                            <th>No</th>
                            <th>{{ $kelompok_kpi->nm_header_table_point }}</th>
                            <th>Predikat</th>
                            <th>{{ $kelompok_kpi->nm_header_table_deskripsi }}</th>
                        </tr>
                    </thead>
                    <tbody class="body">
                        @foreach ($kelompok_kpi->point_kpi as $key => $point_kpi)
                            <tr>
                                @php
                                    $no = $kelompok_kpi->point_kpi->where('urutan', $point_kpi->urutan);
                                @endphp
                                <td style="text-align:center">
                                    @if (count($no) > 1)
                                        @if ($loop->first)
                                            {{ $point_kpi->urutan }}
                                        @endif
                                    @else
                                        {{ $point_kpi->urutan }}
                                    @endif
                                </td>
                                <td @if ($point_kpi->jenis == '0') colspan="2" @endif>{{ $point_kpi->nm_point_kpi }}
                                </td>
                                @if ($point_kpi->jenis != 0)
                                    @php
                                        $predikat = $siswa->predikat
                                            ->where('id_point_kpi', $point_kpi->id_point_kpi)
                                            ->first()?->predikat;
                                    @endphp
                                    <td style="text-align:center">{{ $predikat }}</td>
                                    <td>
                                        @php
                                            $array = explode('<br>', $point_kpi->deskripsi[$predikat] ?? '');
                                        @endphp
                                        <table>
                                            @foreach ($array as $item)
                                                <tr style="border-style : hidden">
                                                    <td style=" vertical-align: top;border-style : hidden">- </td>
                                                    <td> {{ trim($item) }}<br></td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </td>
                                @else
                                    <td></td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach

        </div>
        {{-- mengaji --}}

        <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;border-style : hidden;">
            <tr>
                <td> <b>{{ $abjad[$last_key + 1] . ". Tingkat Kemampuan Baca Al-Qur'an *)" }}</b></td>
            </tr>
        </table>

        <table cellspacing="0" cellpadding="10"
            style="width: 78%;  margin-top: 0; margin-bottom: 30px; margin-right: auto; margin-left: 60px;">
            <thead class="head">
                <tr style="background-color: #e3e1e1">
                    <th colspan="2">
                        Tingkat Al-Qur'an
                    </th>
                    <th colspan="5">Tingkat Pra Al-Qur'an (Sulamut Tilawah)</th>
                </tr>
                <tr style="background-color: #e3e1e1">
                    <th>Kategori</th>
                    <th>Nilai</th>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                    <th>Nilai</th>
                </tr>
            </thead>
            <tbody class="body">
                @foreach ($siswa->dataMengaji as $point_mengaji)
                    @php
                        $predikat_mengaji = $siswa->predikat->where('id_point_kpi', $point_kpi->id_point_kpi)->first()
                            ?->predikat;
                    @endphp
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;
                            {{ $point_mengaji->$predikat_mengaji == 'Y' ? '✔ ' : '- ' }} Tersertifikasi
                        </td>
                        <td style="text-align:center">
                            {{ $point_mengaji->$predikat_mengaji == 'Y' ? $point_mengaji->$predikat_mengaji : '- ' }}
                        </td>
                        <td style="text-align:center" rowspan="2">
                            {{ $point_mengaji->$predikat_mengaji == '1' ? '✔ ' : '- ' }}
                        </td>
                        <td style="text-align:center" rowspan="2">
                            {{ $point_mengaji->$predikat_mengaji == '2' ? '✔ ' : '- ' }}
                        </td>
                        <td style="text-align:center" rowspan="2">
                            {{ $point_mengaji->$predikat_mengaji == '3' ? '✔ ' : '- ' }}
                        </td>
                        <td style="text-align:center" rowspan="2">
                            {{ $point_mengaji->$predikat_mengaji == '4' ? '✔ ' : '- ' }}
                        </td>
                        <td style="text-align:center" rowspan="2">
                            {{ $point_mengaji->$predikat_mengaji == '4' ? '✔ ' : '- ' }}
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;
                            {{ $point_mengaji->$predikat_mengaji == 'T' ? '✔ ' : '- ' }} Belum
                        </td>
                        <td style="text-align:center">
                            {{ $point_mengaji->$predikat_mengaji == 'T' ? $point_mengaji->$predikat_mengaji : '-' }}
                        </td>
                    </tr>
                @break
            @endforeach
        </tbody>
    </table>
    <br>


    <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;border-style : hidden;">
        <tr>
            <td width="30%" style="border-style : hidden; text-align:center"><br>Mengetahui,<br>Orang Tua/Wali
                Murid
                <br><br><br><br><br><br>
                ____________________
            </td>
            <td width="30%" style="border-style : hidden; "></td>
            <td width="30%" style="border-style : hidden;text-align:center ">Sidoarjo, 23 Desember 2023
                {{-- {{ indonesiaDate(\Carbon\Carbon::now()->format('Y-m-d')) }} --}}
                <br>
                <br>
                Wali Kelas
                <br><br><br><br><br><br><u><b>
                        {{ $auth_data->pengguna->nm_pengguna }} {{ $auth_data->pengguna->gelar_belakang }}</b></u>
            </td>

        </tr>
    </table>
@endforeach




</div>
</body>
<script>
    window.print();
</script>

</html>
