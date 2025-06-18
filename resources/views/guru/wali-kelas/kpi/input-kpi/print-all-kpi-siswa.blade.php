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

        .page-break {
            page-break-after: always;
        }
    </style>

    <style type="text/css" media="print">
        @page {
            /* margin: 125mm 125mm 125mm 125mm;    */
            /* size: portrait; */
            size: 210mm 330mm;
            margin: 0mm;
        }

        @media print {
            .break {
                page-break-after: always;

            }
        }
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
                <!-- dimunculkan per 3 kategori -->
                @if ($key == 3)
                    <div class="page-break"></div>
                    @if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smawh2')
                        <img id="kop" src="{{ asset('media/kop-surat-logo-sma-wh-2.png') }}">
                    @endif
                    <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 20px  auto;"
                        style="border-style : hidden">
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
                            <td style="border-style : hidden;  width: 60%;">: <b>{{ $siswa->pengguna->nm_pengguna }}</b>
                            </td>

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
                @endif
                <!-- dimunculkan per 3 kategori -->

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
                    style="width: 90%;  margin-top: 0;margin-bottom: 20px;margin-right: auto;margin-left: auto;">
                    <thead class="head">
                        <tr style="background-color: #e3e1e1">
                            <th>No</th>
                            <th>{{ $kelompok_kpi->nm_header_table_point }}</th>
                            <th>Predikat</th>
                            <th>{{ $kelompok_kpi->nm_header_table_deskripsi }}</th>
                        </tr>
                    </thead>
                    @php
                        $urutan_x = 0;
                    @endphp
                    <tbody class="body">
                        @foreach ($kelompok_kpi->point_kpi as $key => $point_kpi)
                            <tr @if ($point_kpi->urutan != 0 && $point_kpi->urutan % 2 == 0) style="background-color: #e3e1e1" @endif>
                                @php
                                    $data_kelompok_kpi = $kelompok_kpi->point_kpi
                                        ->where('urutan', $point_kpi->urutan)
                                        ->values();
                                    $jumlah_kelompok = $data_kelompok_kpi->count();
                                @endphp
                                @if ($jumlah_kelompok > 1)
                                    @if ($urutan_x != $point_kpi->urutan)
                                        <td style="text-align:center" rowspan="{{ $jumlah_kelompok }}">
                                            {{ $point_kpi->urutan }}
                                        </td>
                                    @endif
                                @else
                                    <td style="text-align:center">
                                        {{ $point_kpi->urutan }}
                                    </td>
                                @endif
                                <td @if ($point_kpi->jenis == '0') colspan="2" @endif>
                                    {{ $point_kpi->nm_point_kpi }}
                                </td>
                                @if ($point_kpi->jenis != 0)
                                    @php
                                        $predikat = $siswa->predikat
                                            ->where('id_point_kpi', $point_kpi->id_point_kpi)
                                            ->first()?->predikat;
                                    @endphp
                                    <td style="text-align:center">{{ $predikat }}</td>
                                @else
                                    @php
                                        $predikat = '';
                                    @endphp
                                @endif
                                {{-- @if ($jumlah_kelompok > 1)
                                    @if ($urutan_x != $point_kpi->urutan)
                                        <td rowspan="{{ $jumlah_kelompok }}">
                                            <table>
                                                @foreach ($data_kelompok_kpi as $unit_point_kpi)
                                                    @php
                                                        $array = explode(
                                                            '<br>',
                                                            $unit_point_kpi->deskripsi[$predikat] ?? '',
                                                        );
                                                    @endphp
                                                    @foreach ($array as $item)
                                                        @if (!empty($item))
                                                            <tr style="border-style : hidden">
                                                                <td style=" vertical-align: top;border-style : hidden">-
                                                                </td>
                                                                <td> {{ trim($item) }}<br></td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            </table>
                                        </td>
                                    @endif
                                @else --}}
                                    <td>
                                        <table>
                                            @foreach ($data_kelompok_kpi as $unit_point_kpi)
                                                @php
                                                    $array = explode(
                                                        '<br>',
                                                        $unit_point_kpi->deskripsi[$predikat] ?? '',
                                                    );
                                                @endphp
                                                @foreach ($array as $item)
                                                    @if (!empty($item))
                                                        <tr style="border-style : hidden">
                                                            <td style=" vertical-align: top;border-style : hidden">-
                                                            </td>
                                                            <td> {{ trim($item) }}<br></td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        </table>
                                    </td>
                                {{-- @endif --}}
                            </tr>

                            @if ($jumlah_kelompok > 1)
                                @if ($urutan_x != $point_kpi->urutan)
                                    @php
                                        $urutan_x = $point_kpi->urutan;
                                        // $urutan_x++;
                                    @endphp
                                @endif
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @endforeach


            {{-- mengaji --}}

            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;border-style : hidden;">
                <tr>
                    <td> <b>{{ $abjad[$last_key + 1] . ". Tingkat Kemampuan Baca Al-Qur'an *)" }}</b></td>
                </tr>
            </table>

            <table cellspacing="0" cellpadding="10"
                style="width: 90%;  margin-top: 0;
                    margin-bottom: 30px;
                    margin-right: auto;
                    margin-left: auto;">
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
                    @if ($siswa->dataMengaji)
                        <tr>
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;
                                {{ $siswa->dataMengaji['Sertifikasi'] == 'Y' ? '✔ ' : '- ' }} Tersertifikasi
                            </td>
                            <td style="text-align:center">
                                {{ $siswa->dataMengaji['Sertifikasi'] == 'Y' ? $siswa->dataMengaji['Nilai Sertifikasi'] : '-' }}
                            </td>
                            <td style="text-align:center" rowspan="2">
                                {{ $siswa->dataMengaji['Tingkat/Jilid'] == '1' ? '✔ ' : '- ' }}</td>
                            <td style="text-align:center" rowspan="2">
                                {{ $siswa->dataMengaji['Tingkat/Jilid'] == '2' ? '✔ ' : '- ' }}</td>
                            <td style="text-align:center" rowspan="2">
                                {{ $siswa->dataMengaji['Tingkat/Jilid'] == '3' ? '✔ ' : '- ' }}</td>
                            <td style="text-align:center" rowspan="2">
                                {{ $siswa->dataMengaji['Tingkat/Jilid'] == '4' ? '✔ ' : '- ' }}</td>
                            <td style="text-align:center" rowspan="2">
                                {{ $siswa->dataMengaji['Nilai'] }}</td>
                        </tr>
                        <tr>
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;
                                {{ $siswa->dataMengaji['Sertifikasi'] == 'T' ? '✔ ' : '- ' }} Belum
                            </td>
                            <td style="text-align:center">
                                {{ $siswa->dataMengaji['Sertifikasi'] == 'T' ? $siswa->dataMengaji['Nilai Sertifikasi'] : '-' }}
                            </td>

                        </tr>
                    @endif
                </tbody>
            </table>
            <br>


            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;border-style : hidden;">
                <tr>
                    <td width="30%" style="border-style : hidden; text-align:center"><br>Mengetahui,<br>Orang
                        Tua/Wali Murid
                        <br><br><br><br><br><br>
                        ____________________
                    </td>
                    <td width="30%" style="border-style : hidden; "></td>
                    <td width="30%" style="border-style : hidden;text-align:center ">
                        @if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm2')
                            Sidoarjo, {{ $tanggal_cetak }} <br>
                        @elseif ($auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm1')
                            Sidoarjo, {{ $tanggal_cetak }} <br>
                        @elseif ($auth_data->sekolah_data->nm_singkat_sekolah == 'smkypm3taman')
                            Sidoarjo, {{ $tanggal_cetak }} <br>
                        @elseif ($auth_data->sekolah_data->nm_singkat_sekolah == 'smkypm1taman')
                            Sidoarjo, {{ $tanggal_cetak }} <br>
                        @elseif ($auth_data->sekolah_data->nm_singkat_sekolah == 'smkypm2')
                            Sidoarjo, {{ $tanggal_cetak }} <br>
                        @else
                            Surabaya, {{ \Carbon\Carbon::now()->translatedFormat('M Y') }} <br>
                        @endif
                        {{-- {{ indonesiaDate(\Carbon\Carbon::now()->format('Y-m-d')) }} --}}
                        <br>
                        Wali Kelas
                        <br><br><br><br><br><br><u><b>
                                {{ $auth_data->pengguna->nm_pengguna }}
                                {{ $auth_data->pengguna->gelar_belakang }}</b></u>
                    </td>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;border-style : hidden;">
                <tr style="position: relative">
                    <td width="30%" style="border-style : hidden; text-align:center">
                    </td>
                    <td width="30%" style="border-style : hidden; text-align:center"><br>Kepala Sekolah,
                        <br><br><br><br><br><br>
                        @if($auth_data->sekolah_data->nm_singkat_sekolah == 'smkypm3taman')
                        <img style="width: 11rem; position: absolute; top: 29%; right: 43%; "
                            src="{{ asset('media/ttd/kepsek-ttd-smkypm3.png') }}" alt="Tanda Tangan Kepala Sekolah">
                        @endif
                        <div>{{ $auth_data->sekolah_data->nm_kepala_sekolah }}</div>
                    </td>
                    <td width="30%" style="border-style : hidden;text-align:center ">
                    </td>
                </tr>
            </table>
            <div class="page-break"></div>
        </div>
    @endforeach
</body>
<script>
    window.print();
</script>

</html>
