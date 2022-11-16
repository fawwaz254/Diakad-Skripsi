<!DOCTYPE html>
<html lang="eng">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Rekap Absensi Harian Siswa</title>


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

        .page {
            width: 1200px;
        }

        .borderless {
            border: none !important;
            padding: 5px;
        }
    </style>

    <style type="text/css" media="print">
        @page {
            /* margin: 125mm 125mm 125mm 125mm;    */
            size: portrait;
            size: auto;
            margin: 0mm;

        }
    </style>
</head>

<body>
    <div class="page">
        <table style="width: 40%; margin-left:5%;">
            <tr>
                <td colspan="4">
                    <h2 align="center" style="margin-top: 1px; font-family: 'Calibri';">
                        {{ $auth_data->sekolah_data->nm_yayasan_sekolah }}
                        <br>
                        {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}<br>
                    </h2>
                </td>
            </tr>
        </table>
        <br>

        <h2 align="center" style="margin-top: 3px">
            REKAP ABSENSI HARIAN SISWA<br>
            Semester {{ $semester_aktif->tahun_ajaran }} ({{ $semester_aktif->nm_semester }})
        </h2>

        <table class="borderless" style="width: 90%; margin: 0 auto;font-weight: bold; margin-top:5px; padding: 10px;">
            <tbody>
                <tr>
                    <td class="borderless" style="width:10%;">NAMA</td>
                    <td class="borderless" style="width:1%;">:</td>
                    <td class="borderless">{{ $siswa->nm_pengguna }}</td>
                </tr>
                <tr>
                    <td class="borderless">NIS</td>
                    <td class="borderless">:</td>
                    <td class="borderless">{{ $siswa->nis_siswa }}</td>
                </tr>
                <tr>
                    <td class="borderless">KELAS</td>
                    <td class="borderless">:</td>
                    <td class="borderless">{{ $siswa->nm_kelas }}</td>
                </tr>
            </tbody>
        </table>

        <table class="table" style="width: 90%; margin: 0 auto; margin-top:5px;">
            <thead style="font-weight: bold;">
                <tr>
                    <th scope="col" style="width: 5%">No.</th>
                    <th scope="col">Hari dan Tanggal</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $hadir = 0;
                    $sakit = 0;
                    $izin = 0;
                    $alpha = 0;
                @endphp
                @foreach ($data_presensi as $presensi)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ \Carbon\Carbon::create($presensi->tgl_entry)->isoFormat('dddd, D MMMM Y') }}</td>
                        @if ($presensi_harian_siswa = $presensi->presensi_harian_siswa->firstWhere('id_siswa', $siswa->id_siswa))
                            @if ($presensi_harian_siswa->kehadiran == 1)
                                <td align="center">Hadir</td>
                                @php
                                    $hadir++;
                                @endphp
                            @elseif($presensi_harian_siswa->kehadiran == 2)
                                <td align="center">Sakit</td>
                                @php
                                    $sakit++;
                                @endphp
                            @elseif($presensi_harian_siswa->kehadiran == 3)
                                <td align="center">Izin</td>
                                @php
                                    $izin++;
                                @endphp
                            @elseif($presensi_harian_siswa->kehadiran == 4)
                                <td align="center">Alpha</td>
                                @php
                                    $alpha++;
                                @endphp
                            @else
                                <td></td>
                            @endif
                        @else
                            <td></td>
                        @endif
                    </tr>
                @endforeach
                <tr>
                    <td rowspan="5" colspan="2" style="font-weight: bold">TOTAL:</td>
                </tr>
                <tr>
                    <td style="font-weight: bold">Hadir {{ $hadir }}x</td>
                </tr>
                <tr>
                    <td style="font-weight: bold">Sakit {{ $sakit }}x</td>
                </tr>
                <tr>
                    <td style="font-weight: bold">Izin {{ $izin }}x</td>
                </tr>
                <tr>
                    <td style="font-weight: bold">Alpha {{ $alpha }}x</td>
                </tr>
            </tbody>
        </table>
        <br><br>
        <table cellspacing="0" cellpadding="10" style="width: 90%; margin-left:10%; border-style : hidden;">
            <tr>
                <td style=" border-style : hidden; width:15%; vertical-align: text-top; padding:0">
                    {{-- <br>
                    Orang Tua/Wali,
                    <br><br><br><br><br><br><br>
                    <p style="width: 250px;
                    border-bottom: 1px solid   black;"></p> --}}
                </td>
                <td style="width:40%; border-style : hidden"></td>

                <td style="width:25%;border-style : hidden;">Sidoarjo,
                    {{ indonesiaDate(\Carbon\Carbon::now()->format('Y-m-d')) }}
                    <br>
                    Wali Kelas
                    <br><br><br><br><br><br><br>
                    <u><b>
                            {{ $wali_kelas->guru->pengguna->gelar_depan }}
                            {{ $wali_kelas->guru->pengguna->nm_pengguna }}
                            {{ $wali_kelas->guru->pengguna->gelar_belakang }}</b></u>
                </td>
            </tr>
        </table>
</body>
<script>
    window.print();
</script>

</html>
