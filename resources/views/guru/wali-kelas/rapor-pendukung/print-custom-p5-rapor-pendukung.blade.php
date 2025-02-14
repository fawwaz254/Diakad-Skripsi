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

        .signature-container {
            display: flex;
            justify-content: space-around;
            margin-top: 50px;
        }

        .signature {
            text-align: center;
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

            .signature-container {
                display: flex;
                justify-content: space-around;
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
                        {{-- : {{ $auth_data->sekolah_data->alamat_jalan }} --}}
                        : JL. MANYAR SAMBONGAN 119
                    </td>
                    <td style="width: 20%">
                        Fase
                    </td>
                    <td style="width: 20%">
                        :@if ($kelas->tingkat === 1)
                            E
                        @else
                            F
                        @endif
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
            @if ($kelas->tingkat === 1)
                <h3 style="width: 90%; margin: 2rem auto;">TEMA KEWIRAUSAHAAN : Wirausaha Muda Berkarya secara Inovatif
                    dan Kompetitif (membuat buket)
                </h3>
            @elseif($kelas->tingkat === 2)
                <h3 style="width: 90%; margin: 2rem auto;">TEMA KEWIRAUSAHAAN : HIDUP SEHAT DENGAN TOGA (Membuat Jamu
                    Bubuk)
                </h3>
            @elseif($kelas->tingkat === 3)
                <h3 style="width: 90%; margin: 2rem auto;">TEMA SUARA DEMOKRASI : KEBEBASAN BERPENDAPAT WUJUD DEMOKRASI
                    SUATU BANGSA
                </h3>
            @endif
            <table style="width: 90%; margin: 2rem auto;">
                <tr>
                    @if ($kelas->tingkat === 1)
                        <th style="font-weight: bold;background-color: #f7b2ab">TEMA KEWIRAUSAHAAN : Wirausaha Muda
                            Berkarya secara Inovatif dan Kompetitif (membuat buket)
                        </th>
                    @elseif($kelas->tingkat === 2)
                        <th style="font-weight: bold;background-color: #f7b2ab">TEMA KEWIRAUSAHAAN : HIDUP SEHAT DENGAN
                            TOGA (Membuat Jamu Bubuk)
                        </th>
                    @elseif($kelas->tingkat === 3)
                        <th style="font-weight: bold;background-color: #f7b2ab">TEMA SUARA DEMOKRASI : KEBEBASAN
                            BERPENDAPAT WUJUD DEMOKRASI SUATU BANGSA
                        </th>
                    @endif
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
                        @php
                            $nilai = '';
                        @endphp
                        @foreach ($indikator->predikat_rapor_pendukung as $predikat)
                            @if ($predikat->id_siswa == $siswa->id_siswa)
                                @php
                                    $nilai = $predikat->nilai;
                                @endphp
                            @endif
                        @endforeach
                        <tr>
                            <td style="font-weight: bold">
                                {{ $indikator->nm_indikator }}
                            </td>
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
            @if ($kelas->tingkat === 1)
                <h3 style="width: 90%; margin: 2rem auto;">TEMA GAYA HIDUP BERKELANJUTAN : Pengelolaan Sampah
                </h3>
            @elseif($kelas->tingkat === 2)
                <h3 style="width: 90%; margin: 2rem auto;">TEMA KEARIFAN LOKAL : MEMANFAATKAN KEKAYAAN ALAM DENGAN
                    MEMBUAT BATIK ECOPRINT.
                </h3>
            @elseif($kelas->tingkat === 3)
                <h3 style="width: 90%; margin: 2rem auto;">TEMA KEARIFAN LOKAL : MAKANAN TRADISIONAL KHAS KOTA
                    SURABAYA DI LINGKUNGAN SEKOLAH
                </h3>
            @endif
            <table style="width: 90%; margin: 2rem auto;">
                <tr>
                    @if ($kelas->tingkat === 1)
                        <th style="font-weight: bold;background-color: #f7b2ab">TEMA GAYA HIDUP BERKELANJUTAN :
                            Pengelolaan Sampah
                        </th>
                    @elseif($kelas->tingkat === 2)
                        <th style="font-weight: bold;background-color: #f7b2ab">TEMA KEARIFAN LOKAL : MEMANFAATKAN
                            KEKAYAAN ALAM DENGAN MEMBUAT BATIK ECOPRINT
                        </th>
                    @elseif($kelas->tingkat === 3)
                        <th style="font-weight: bold;background-color: #f7b2ab">TEMA KEARIFAN LOKAL : MAKANAN
                            TRADISIONAL KHAS KOTA SURABAYA DI LINGKUNGAN SEKOLAH
                        </th>
                    @endif
                    <th style="font-weight: bold;background-color: #abb9f7">BB</th>
                    <th style="font-weight: bold;background-color: #abb9f7">MB</th>
                    <th style="font-weight: bold;background-color: #abb9f7">BSH</th>
                    <th style="font-weight: bold;background-color: #abb9f7">SB</th>
                </tr>

                @php
                    $filter_komponen = $list_komponen_rapor
                        ->filter(function ($komponen) {
                            return str_contains($komponen->nm_komponen, 'Projek 2');
                        })
                        ->values();
                @endphp
                @foreach ($filter_komponen as $komponen)
                    <tr style="background-color: #f7ebab">
                        <td colspan="5" style="font-weight: bold">
                            {{ str_replace('Projek 2:', '', $komponen->nm_komponen) }}</td>
                    </tr>

                    @foreach ($komponen->indikator_rapor_pendukung as $indikator)
                        @php
                            $nilai = '';
                        @endphp
                        @foreach ($indikator->predikat_rapor_pendukung as $predikat)
                            @if ($predikat->id_siswa == $siswa->id_siswa)
                                @php
                                    $nilai = $predikat->nilai;
                                @endphp
                            @endif
                        @endforeach
                        <tr>
                            <td style="font-weight: bold">
                                {{ $indikator->nm_indikator }}
                            </td>
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
            @if ($kelas->tingkat === 1)
                <h3 style="width: 90%; margin: 2rem auto;">TEMA BHINEKA TUNGGAL IKA : Eksplorasi Budaya Bangsa
                </h3>
                <table style="width: 90%; margin: 2rem auto;">
                    <tr>
                        <th style="font-weight: bold;background-color: #f7b2ab">TEMA BHINEKA TUNGGAL IKA :
                            Eksplorasi
                            Budaya Bangsa
                        </th>
                        <th style="font-weight: bold;background-color: #abb9f7">BB</th>
                        <th style="font-weight: bold;background-color: #abb9f7">MB</th>
                        <th style="font-weight: bold;background-color: #abb9f7">BSH</th>
                        <th style="font-weight: bold;background-color: #abb9f7">SB</th>
                    </tr>

                    @php
                        $filter_komponen = $list_komponen_rapor
                            ->filter(function ($komponen) {
                                return str_contains($komponen->nm_komponen, 'Projek 3');
                            })
                            ->values();
                    @endphp
                    @foreach ($filter_komponen as $komponen)
                        <tr style="background-color: #f7ebab">
                            <td colspan="5" style="font-weight: bold">
                                {{ str_replace('Projek 3:', '', $komponen->nm_komponen) }}</td>
                        </tr>

                        @foreach ($komponen->indikator_rapor_pendukung as $indikator)
                            @php
                                $nilai = '';
                            @endphp
                            @foreach ($indikator->predikat_rapor_pendukung as $predikat)
                                @if ($predikat->id_siswa == $siswa->id_siswa)
                                    @php
                                        $nilai = $predikat->nilai;
                                    @endphp
                                @endif
                            @endforeach
                            <tr>
                                <td style="font-weight: bold">
                                    {{ $indikator->nm_indikator }}
                                </td>
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
            @endif

            {{-- CATATAN PROSES --}}
            <table style="width: 90%; margin: 2rem auto;">
                <tr style="text-align: left">
                    <th>Catatan Proses:</th>
                </tr>
                <tr>
                    <td>
                        {{ App\Libraries\Akademik\LibAkademik::catatanProsesSiswa($siswa->id_siswa, $rapor->id_rapor_pendukung) }}
                    </td>
                </tr>
            </table>

            {{-- KETERANGAN --}}
            <table style="width: 90%; margin: 2rem auto" class="tabel-keterangan">
                <thead style="background-color: #f2f2f2;">
                    <th>BB<br>Belum Berkembang</th>
                    <th>MB<br>Mulai Berkembang</th>
                    <th>BSH<br>Berkembang Sesuai Harapan</th>
                    <th>SB<br>Sangat Berkembang</th>
                </thead>
                <tr>
                    <td colspan="1">Peserta didik masih membutuhkan bimbingan dalam mengembangkan kemampuan</td>
                    <td colspan="1">Peserta didik mulai mengembangkan kemampuan namun masih belum ajek</td>
                    <td colspan="1">Peserta didik telah mengembangkan kemampuan hingga berada dalam tahap ajek</td>
                    <td colspan="1">Peserta didik mengembangkan kemampuannya melampaui harapan</td>
                </tr>
            </table>

            {{-- TANDA TANGAN --}}
            <div class="signature-container">
                <div class="signature" style="margin-top:50px;">
                    Mengetahui,
                    <br>Orang Tua/Wali Murid
                    <div style="margin-top:115px; width: 200px; border-top: 1px solid #000;"></div>
                </div>

                <div class="signature" style="margin-top:50px;">
                    Sidoarjo,
                    @if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm2')
                        {{ $tanggal_cetak }}
                    @else
                        {{ $tanggal_cetak }}
                    @endif
                    <br> Wali Kelas,
                    <div style="margin-top:100px;">
                        {{ $wali_kelas->gelar_depan }} {{ $wali_kelas->nm_wali_kelas }}
                        {{ $wali_kelas->gelar_belakang }}
                    </div>
                </div>
            </div>

        </div>
    @endforeach
</body>

<script>
    window.print();
</script>

</html>
