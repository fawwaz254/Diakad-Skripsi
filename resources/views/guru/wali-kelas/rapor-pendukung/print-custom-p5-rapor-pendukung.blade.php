<!DOCTYPE html>
<html lang="eng">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapor {{ $rapor->nm_rapor }} ({{ $kelas->nm_kelas }})</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Yellowtail&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            font-family: 'Tahoma';
            letter-spacing: 1.5px;
        }

        .column {
            float: left;
            width: 33%;
            text-align: center;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        table#kop,
        table#kop td,
        table#kop th {
            border: none;
            padding: 10px;
        }

        table#bio,
        table#bio td,
        table#bio th {
            border: none;
            padding: 5px;
        }

        table#ttd,
        table#ttd td,
        table#ttd th {
            border: none;
            font-weight: bold;
        }

        table,
        td,
        th {
            border: 3px solid;
            padding: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            /* border: 5px double; */
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

        .yellowtail-regular {
            font-family: "Yellowtail", cursive;
            font-weight: 400;
            font-style: normal;
        }
    </style>

    <style type="text/css" media="print">
        @page {
            /* margin: 125mm 125mm 125mm 125mm;    */
            size: portrait;
            size: auto;
            margin: 0mm;

            @bottom-left {
                content: counter(page) "/" counter(pages);
            }
        }

        @media print {
            * {
                color-adjust: exact !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .page {
                page-break-after: always;
            }
        }
    </style>
</head>

<body>

    @foreach ($list_siswa as $siswa)
        <div class="page" style="padding-top: 3rem">

            <h1 style="text-align: center; font-size: 1.8rem;"><u>RAPOR {{ strtoupper($rapor->nm_rapor) }}</u></h1>

            <table id="bio" style="width: 90%; margin: 1rem auto;">
                <tr>
                    <td style="width: 20%">
                        Satuan Pendidikan
                    </td>
                    <td style="width: 40%">
                        : {{ $auth_data->sekolah_data->nm_sekolah }}
                    </td>
                    <td style="width: 20%">
                        Kelas
                    </td>
                    <td style="width: 20%">
                        : {{ explode('-', $kelas->nm_kelas)[0] }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 20%">
                        Alamat
                    </td>
                    <td style="width: 40%">
                        : {{ $auth_data->sekolah_data->alamat_jalan }}
                    </td>
                    <td style="width: 20%">
                        Fase
                    </td>
                    <td style="width: 20%">
                        : {{ explode('-', $kelas->nm_kelas)[1] }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 20%">
                        Nama Peserta Didik
                    </td>
                    <td style="width: 40%">
                        : {{ $siswa->pengguna->nm_pengguna }}
                    </td>
                    <td style="width: 20%">
                        Tahun Ajaran
                    </td>
                    <td style="width: 20%">
                        : {{ $semester_aktif->tahun_ajaran }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 20%">
                        NIS
                    </td>
                    <td style="width: 40%">
                        : {{ $siswa->nis_siswa }}
                    </td>
                </tr>
            </table>

            {{-- PROJEK 1 --}}
            <h3 style="width: 90%; margin: 2rem auto;">Projek 1 | Bhinneka Tunggal Ika | “SEKOLAHKU NYAMAN, NO
                BULLYING!”</h3>
            <table style="width: 90%; margin: 2rem auto;">
                <tr>
                    <th style="font-weight: bold;background-color: #f7b2ab">Bhinneka Tunggal Ika | “SEKOLAHKU NYAMAN, NO
                        BULLYING!”</th>
                    <th style="font-weight: bold;background-color: #abb9f7">BB</th>
                    <th style="font-weight: bold;background-color: #abb9f7">MB</th>
                    <th style="font-weight: bold;background-color: #abb9f7">BSH</th>
                    <th style="font-weight: bold;background-color: #abb9f7">SB</th>
                </tr>

                @php

                    $filter_komponen = $list_komponen_rapor
                        ->filter(function ($komponen) {
                            return str_contains($komponen->nm_komponen, 'Projek 1');
                        })
                        ->values();
                @endphp
                @foreach ($filter_komponen as $komponen)
                    <tr style="background-color: #f7ebab">
                        <td colspan="5" style="font-weight: bold">
                            {{ str_replace('Projek 1:', '', $komponen->nm_komponen) }}</td>
                    </tr>

                    @foreach ($komponen->indikator_rapor_pendukung as $indikator)
                        <tr>
                            <td style="font-weight: bold">
                                {{ $indikator->nm_indikator }}</td>

                            @php
                                $nilai = '';
                                foreach ($indikator->predikat_rapor_pendukung as $predikat) {
                                    if ($predikat->id_siswa == $siswa->id_siswa) {
                                        $nilai = $predikat->nilai;
                                    }
                                }
                            @endphp
                            <td style="text-align: center">
                                @if ($nilai == 'BB')
                                    &#10003;
                                @endif
                            </td>
                            <td style="text-align: center">
                                @if ($nilai == 'MB')
                                    &#10003;
                                @endif
                            </td>
                            <td style="text-align: center">
                                @if ($nilai == 'BSH')
                                    &#10003;
                                @endif
                            </td>
                            <td style="text-align: center">
                                @if ($nilai == 'SB')
                                    &#10003;
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </table>
            {{-- PROJEK 2 --}}
            <h3 style="width: 90%; margin: 2rem auto;">Projek 2 | “WIRAUSAHA MUDA BERKARYA SECARA INOVATIF & KOMPETITIF”
            </h3>
            <table style="width: 90%; margin: 2rem auto;">
                <tr>
                    <th style="font-weight: bold;background-color: #f7b2ab">Bhinneka Tunggal Ika | “SEKOLAHKU NYAMAN, NO
                        BULLYING!”</th>
                    <th style="font-weight: bold;background-color: #abb9f7">BB</th>
                    <th style="font-weight: bold;background-color: #abb9f7">MB</th>
                    <th style="font-weight: bold;background-color: #abb9f7">BSH</th>
                    <th style="font-weight: bold;background-color: #abb9f7">SB</th>
                </tr>

                @php

                    $filter_komponen = $list_komponen_rapor
                        ->filter(function ($komponen) {
                            return str_contains($komponen->nm_komponen, 'Projek 1');
                        })
                        ->values();
                @endphp
                @foreach ($filter_komponen as $komponen)
                    <tr style="background-color: #f7ebab">
                        <td colspan="5" style="font-weight: bold">
                            {{ str_replace('Projek 1:', '', $komponen->nm_komponen) }}</td>
                    </tr>

                    @foreach ($komponen->indikator_rapor_pendukung as $indikator)
                        <tr>
                            <td style="font-weight: bold">
                                {{ $indikator->nm_indikator }}</td>

                            @php
                                $nilai = '';
                                foreach ($indikator->predikat_rapor_pendukung as $predikat) {
                                    if ($predikat->id_siswa == $siswa->id_siswa) {
                                        $nilai = $predikat->nilai;
                                    }
                                }
                            @endphp
                            <td style="text-align: center">
                                @if ($nilai == 'BB')
                                    &#10003;
                                @endif
                            </td>
                            <td style="text-align: center">
                                @if ($nilai == 'MB')
                                    &#10003;
                                @endif
                            </td>
                            <td style="text-align: center">
                                @if ($nilai == 'BSH')
                                    &#10003;
                                @endif
                            </td>
                            <td style="text-align: center">
                                @if ($nilai == 'SB')
                                    &#10003;
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </table>
            {{-- PROJEK 3 --}}
            <h3 style="width: 90%; margin: 2rem auto;">Projek 3 | GAYA HIDUP BERKELANJUTAN| “PENGHIJAUAN DISEKITAR
                SEKOLAHKU”</h3>
            <table style="width: 90%; margin: 2rem auto;">
                <tr>
                    <th style="font-weight: bold;background-color: #f7b2ab">Bhinneka Tunggal Ika | “SEKOLAHKU NYAMAN, NO
                        BULLYING!”</th>
                    <th style="font-weight: bold;background-color: #abb9f7">BB</th>
                    <th style="font-weight: bold;background-color: #abb9f7">MB</th>
                    <th style="font-weight: bold;background-color: #abb9f7">BSH</th>
                    <th style="font-weight: bold;background-color: #abb9f7">SB</th>
                </tr>

                @php

                    $filter_komponen = $list_komponen_rapor
                        ->filter(function ($komponen) {
                            return str_contains($komponen->nm_komponen, 'Projek 1');
                        })
                        ->values();
                @endphp
                @foreach ($filter_komponen as $komponen)
                    <tr style="background-color: #f7ebab">
                        <td colspan="5" style="font-weight: bold">
                            {{ str_replace('Projek 1:', '', $komponen->nm_komponen) }}</td>
                    </tr>

                    @foreach ($komponen->indikator_rapor_pendukung as $indikator)
                        <tr>
                            <td style="font-weight: bold">
                                {{ $indikator->nm_indikator }}</td>

                            @php
                                $nilai = '';
                                foreach ($indikator->predikat_rapor_pendukung as $predikat) {
                                    if ($predikat->id_siswa == $siswa->id_siswa) {
                                        $nilai = $predikat->nilai;
                                    }
                                }
                            @endphp
                            <td style="text-align: center">
                                @if ($nilai == 'BB')
                                    &#10003;
                                @endif
                            </td>
                            <td style="text-align: center">
                                @if ($nilai == 'MB')
                                    &#10003;
                                @endif
                            </td>
                            <td style="text-align: center">
                                @if ($nilai == 'BSH')
                                    &#10003;
                                @endif
                            </td>
                            <td style="text-align: center">
                                @if ($nilai == 'SB')
                                    &#10003;
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </table>

            <div style="width: 90%; margin: 0 auto; padding: 15px;border:3px solid black">
                @php
                    $catatan_siswa = $list_catatan_siswa->where('id_siswa', $siswa->id_siswa)->first()->nilai;
                @endphp
                <strong>Catatan Proses:</strong> <br><br>
                {{ $catatan_siswa }}
            </div>

            <table style="width: 90%; margin: 2rem auto;">
                <tr style="background-color: #ccc">
                    <th>BB <br> Belum Berkembang</th>
                    <th>MB <br> Mulai Berkembang</th>
                    <th>BSH <br> Berkembang Sesuai Harapan</th>
                    <th>SB <br> Sangat Berkembang</th>
                </tr>
                <tr>
                    <td>Peserta didik masih membutuhkan bimbingan dalam memgembangkan kemampuan</td>
                    <td>Peserta didik mulai mengembangkan kemampuan namun masih belum ajek</td>
                    <td>Peserta didik telah mengembangkan kemampuan hingga berada dalam tahap ajek</td>
                    <td>Peserta didik mengembangkan kemampuannya melampui harapan</td>
                </tr>
            </table>

            <div style="width: 90%; margin: 0 auto;">
                <table id="ttd" style="border: none !impartant">
                    <tr>
                        <td>
                            Mengetahui, <br>
                            Orang Tua/Wali Murid <br><br><br><br><br><br><br>

                            <u>................................</u>
                        </td>
                        <td style="width: 30%">
                            {{ $auth_data->sekolah_data->alamat_kota == '136' ? 'Surabaya' : 'Sidoarjo' }},
                            {{ \Carbon\Carbon::now()->translatedFormat('d M Y') }} <br>
                            Wali Kelas <br><br><br><br><br><br><br>

                            <u>{{ $auth_data->pengguna->nm_pengguna }} {{ $auth_data->pengguna->gelar_belakang }}</u>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    @endforeach
</body>
<script>
    window.print();
</script>

</html>
