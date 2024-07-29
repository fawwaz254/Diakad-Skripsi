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
            font-weight: bold;
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
            border: 1px solid;
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
    {{-- COVER SECTION --}}
    <div class="page" style="margin: 0 auto; width:70%; padding-top:8rem; text-align:center">
        <h1 style="text-transform: uppercase; font-size: 3rem; margin-bottom:10rem">
            RAPOR {{ $rapor->nm_rapor }} <br>
            {{ $auth_data->sekolah_data->nm_sekolah }} <br>
            <span style="font-size: 1.8rem">TAHUN AJARAN {{ $semester_aktif->tahun_ajaran }}</span>
        </h1>

        <img width="250px" src="{{ 'https://diakad.sgp1.digitaloceanspaces.com/' . $auth_data->sekolah_data->nm_singkat_sekolah . '/global/logo-sekolah' }}">

        <div style="font-size: 2rem;font-weight:bold;padding:20px;border:1px solid black;margin-top:10rem;border-radius:20px">
            {{ $siswa->pengguna->nm_pengguna }} <br>
            {{ $siswa->nis_siswa }}/{{ $siswa->nisn_siswa }}
        </div>

        <div style="padding:20px;border:1px solid black;margin-top:10rem;border-radius:20px;">
            <div class="yellowtail-regular" style="font-size: 2.5rem;font-weight:bold;color: #2b5f94">
                {{ $auth_data->sekolah_data->nm_yayasan_sekolah }}
            </div>
            <div style="font-weight: bolder;font-size:3rem; color: #49872e;text-transform:uppercase;">
                {{ $auth_data->sekolah_data->nm_sekolah }}
            </div>
            <div style="font-weight: bolder;font-size:2rem; color: #49872e;text-transform:uppercase;">
                {{ $auth_data->sekolah_data->akreditasi }}
            </div>

            <div style="font-size: 1.6rem;font-weight:bold;">
                {{ $auth_data->sekolah_data->alamat_jalan }}, Telp.
                {{ $auth_data->sekolah_data->nomor_telp_sekolah }}
                {{ $auth_data->sekolah_data->alamat_kecamatan }}
                {{ $auth_data->sekolah_data->alamat_kodepos }} <br>

                {{ $auth_data->sekolah_data->alamat_kecamatan == 136 ? 'Surabaya' : 'Sidoarjo' }} Jawa Timur
            </div>
        </div>
    </div>

    {{-- CONTENT SECTION --}}
    <div class="page" style="padding-top: 2rem">
        <table id="kop" cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; text-align:center;">
            <tr>
                <td style="width: 10%">
                    <img width="120px" src="{{ 'https://diakad.sgp1.digitaloceanspaces.com/' . $auth_data->sekolah_data->nm_singkat_sekolah . '/global/logo-sekolah' }}">
                </td>
                <td>
                    <div style="font-weight: bold; font-size:2.5rem; color: #2b5f94">
                        {{ $auth_data->sekolah_data->nm_yayasan_sekolah }}
                    </div>
                    <div style="font-weight: bolder; font-size:3rem; color: #49872e">
                        {{ $auth_data->sekolah_data->nm_sekolah }}
                    </div>
                    <div style="font-weight: bolder;">{{ $auth_data->sekolah_data->akreditasi }}</div>
                    <div class="row">
                        <div class="column" style="text-align: center;">NSS:
                            {{ $auth_data->sekolah_data->nss_sekolah }}
                        </div>
                        <div class="column" style="text-align: center;">NDS: 2005020203</div>
                        <div class="column" style="text-align: center;">NPSN:
                            {{ $auth_data->sekolah_data->npsn_sekolah }}
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        <div style="width: 90%; margin: 0 auto; text-align:center; border: 1px solid black; padding:10px; background-color: #ddf5d5">
            {{ $auth_data->sekolah_data->alamat_jalan }}, Telp. {{ $auth_data->sekolah_data->nomor_telp_sekolah }}
            -
            {{ $auth_data->sekolah_data->nomor_fax_sekolah }} {{ $auth_data->sekolah_data->alamat_kecamatan }}
            {{ $auth_data->sekolah_data->alamat_kecamatan == 136 ? 'Surabaya' : 'Sidoarjo' }} Jawa Timur
            Kd. Pos {{ $auth_data->sekolah_data->alamat_kodepos }} <br>
            Website : {{ $auth_data->sekolah_data->website_sekolah }} &nbsp;&nbsp;&nbsp; e-mail :
            {{ $auth_data->sekolah_data->email_sekolah }}
        </div>

        <h1 style="text-align: center">RAPOR {{ strtoupper($rapor->nm_rapor) }}</h1>

        <table id="bio" style="width: 90%; margin: 0 auto;">
            <tr>
                <td style="width: 15%">
                    Nama
                </td>
                <td style="width: 50%">
                    : {{ $siswa->pengguna->nm_pengguna }}
                </td>
                <td style="width: 15%">
                    No. Induk
                </td>
                <td style="width: 20%">
                    : {{ $siswa->nis_siswa }}
                </td>
            </tr>
            <tr>
                <td style="width: 15%">
                    Kelas
                </td>
                <td style="width: 50%">
                    : {{ $kelas->nm_kelas }}
                </td>
                <td style="width: 15%">
                    Tahun ajaran
                </td>
                <td style="width: 20%">
                    : {{ $semester_aktif->tahun_ajaran }}
                </td>
            </tr>
        </table>

        @php
        switch ($kelas->tingkat) {
        case '7':
        $semester_kelas = '1 & 2';
        break;

        case '8':
        $semester_kelas = '3 & 4';
        break;

        case '9':
        $semester_kelas = '5 & 6';
        break;

        default:
        $semester_kelas = '';
        break;
        }
        @endphp

        <table style="width: 90%; margin: 0 auto;">
            <tr style="background-color: #b2fc83">
                <th style="font-weight: bold">NO</th>
                <th style="font-weight: bold">SEMESTER</th>
                <th style="font-weight: bold">KOMPONEN</th>
                <th style="font-weight: bold">INDIKATOR</th>
                <th style="font-weight: bold">PENILAIAN SIKAP</th>
            </tr>
            <tr style="background-color: #fafc83">
                <th style="font-style: italic; font-weight: bold">a</th>
                <th style="font-style: italic; font-weight: bold">b</th>
                <th style="font-style: italic; font-weight: bold">c</th>
                <th style="font-style: italic; font-weight: bold">d</th>
                <th style="font-style: italic; font-weight: bold">e</th>
            </tr>

            @foreach ($list_komponen_rapor as $key_komponen => $komponen)
            @foreach ($komponen->indikator_rapor_pendukung as $key_indikator => $indikator)
            <tr>
                @if ($key_indikator == 0)
                <td style="text-align: center" rowspan="{{ $komponen->indikator_rapor_pendukung->count() }}">
                    {{ $key_komponen + 1 }}
                </td>
                <td style="text-align: center" rowspan="{{ $komponen->indikator_rapor_pendukung->count() }}">
                    {{ $semester_kelas }}
                </td>
                <td rowspan="{{ $komponen->indikator_rapor_pendukung->count() }}">
                    {{ $komponen->nm_komponen }}
                </td>
                @endif
                <td>{{ $loop->iteration }}. {{ $indikator->nm_indikator }}</td>
                <td style="text-align: center">
                    {{ $predikat_rapor_pendukung->where('id_indikator_rapor_pendukung', $indikator->id_indikator_rapor_pendukung)->where('id_siswa', $siswa->id_siswa)->first()?->nilai }}
                </td>
            </tr>
            @endforeach
            @endforeach
        </table>
        <div style="width: 90%; margin: 0 auto;">
            <h5>Keterangan Predikat:</h5>
            <ol>
                <li>A (Sangat Baik)</li>
                <li>B (Baik)</li>
                <li>C (Cukup Baik)</li>
                <li>D (Kurang Baik)</li>
            </ol>

            <table id="ttd" style="border: none !impartant">
                <tr>
                    <td></td>
                    <td style="width: 30%">
                        @if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm2')
                        {{ $auth_data->sekolah_data->alamat_kota == '136' ? 'Surabaya' : 'Sidoarjo' }}, {{ $tanggal_cetak }} <br>
                        @elseif ($auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm1')
                        {{ $auth_data->sekolah_data->alamat_kota == '136' ? 'Surabaya' : 'Sidoarjo' }}, {{ $tanggal_cetak }} <br>
                        @else
                        {{ $tanggal_cetak }} <br>
                        @endif
                        Kepala Sekolah <br><br><br><br><br><br><br>

                        {{ $auth_data->sekolah_data->nm_kepala_sekolah }}
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