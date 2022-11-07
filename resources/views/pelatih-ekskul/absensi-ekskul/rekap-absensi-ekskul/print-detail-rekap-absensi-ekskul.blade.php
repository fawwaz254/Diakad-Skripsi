<!DOCTYPE html>
<html lang="eng">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Rekap Absensi Ekstrakurikuler</title>


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
            REKAP ABSENSI EKSTRAKURIKULER<br>
            Semester {{ $semester_aktif->tahun_ajaran }} ({{ $semester_aktif->nm_semester }})
        </h2>
        
        <table style="border-style:hidden; width: 90%; margin: 0 auto;font-weight: bold; margin-top:5px;">
            <tbody>
                <tr style="border-style:hidden;">
                    <td>Nama: {{ $data_siswa->siswa->pengguna->nm_pengguna }}</td>
                </tr>
                <tr style="border-style:hidden;">
                    <td>Kelas: {{ $data_siswa->kelas->nm_kelas }}</td>
                </tr>
                <tr style="border-style:hidden;">
                    <td>Ekstrakurikuler: {{ $data_ekskul->nm_ekskul }}</td>
                </tr>
                <tr style="border-style:hidden;">
                    <td>Pelatih: {{ implode(', ', $data_pelatih) }}</td>
                </tr>
            </tbody>
        </table>

        <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
            <thead class="head">
                <tr>
                    <td style="text-align: center;font-weight: bold; width:5%;">No.</td>
                    <td style="text-align: center;font-weight: bold; width:15%;">Status<br></td>
                    <td style="text-align: center;font-weight: bold; width:15%;">Jumlah</td>
                    <td style="text-align: center;font-weight: bold;">Hari, Tanggal</td>
                </tr>
            </thead>
            <tbody class="body">
                @php
                    $no = 0;
                @endphp
                {{-- Hadir --}}
                <tr>
                    <td style="text-align: center;">{{ ++$no }}</td>
                    <td style="text-align: center;">Hadir</td>
                    <td style="text-align: center;">{{$hadir}} x</td>
                    <td>
                        @foreach($data_presensi as $presensi_ekskul)
                        @php
                            $presensi_ekskul_peserta = $presensi_ekskul->presensi_ekskul_peserta->firstWhere('id_siswa', $data_siswa->id_siswa)
                        @endphp
                        @if($presensi_ekskul_peserta->kehadiran == 1)
                            Pertemuan ke-{{$presensi_ekskul->pertemuan_ke}}: {{\Carbon\Carbon::create($presensi_ekskul->tgl_entry)->isoFormat('dddd, D MMMM Y')}}<br>
                        @endif
                        @endforeach
                    </td>
                </tr>
                {{-- Izin --}}
                <tr>
                    <td style="text-align: center;">{{ ++$no }}</td>
                    <td style="text-align: center;">Izin</td>
                    <td style="text-align: center;">{{$izin}} x</td>
                    <td>
                        @foreach($data_presensi as $presensi_ekskul)
                        @php
                            $presensi_ekskul_peserta = $presensi_ekskul->presensi_ekskul_peserta->firstWhere('id_siswa', $data_siswa->id_siswa)
                        @endphp
                        @if($presensi_ekskul_peserta->kehadiran == 3)
                            Pertemuan ke-{{$presensi_ekskul->pertemuan_ke}}: {{\Carbon\Carbon::create($presensi_ekskul->tgl_entry)->isoFormat('dddd, D MMMM Y')}}<br>
                        @endif
                        @endforeach
                    </td>
                </tr>
                {{-- Sakit --}}
                <tr>
                    <td style="text-align: center;">{{ ++$no }}</td>
                    <td style="text-align: center;">Sakit</td>
                    <td style="text-align: center;">{{$sakit}} x</td>
                    <td>
                        @foreach($data_presensi as $presensi_ekskul)
                        @php
                            $presensi_ekskul_peserta = $presensi_ekskul->presensi_ekskul_peserta->firstWhere('id_siswa', $data_siswa->id_siswa)
                        @endphp
                        @if($presensi_ekskul_peserta->kehadiran == 2)
                            Pertemuan ke-{{$presensi_ekskul->pertemuan_ke}}: {{\Carbon\Carbon::create($presensi_ekskul->tgl_entry)->isoFormat('dddd, D MMMM Y')}}<br>
                        @endif
                        @endforeach
                    </td>
                </tr>
                {{-- Alpha --}}
                <tr>
                    <td style="text-align: center;">{{ ++$no }}</td>
                    <td style="text-align: center;">Alpha</td>
                    <td style="text-align: center;">{{$alpha}} x</td>
                    <td>
                        {{-- @foreach($data_presensi as $presensi_ekskul)
                        @php
                            $presensi_ekskul_peserta = $presensi_ekskul->presensi_ekskul_peserta->firstWhere('id_siswa', $data_siswa->id_siswa)
                        @endphp
                        @if($presensi_ekskul_peserta->kehadiran == 4)
                            Pertemuan ke-{{$presensi_ekskul->pertemuan_ke}}: {{date_format(date_create($presensi_ekskul->tgl_entry), 'l, d/m/Y')}} <br>
                        @endif
                        @endforeach --}}
                        @foreach($data_presensi as $presensi_ekskul)
                        @php
                            $presensi_ekskul_peserta = $presensi_ekskul->presensi_ekskul_peserta->firstWhere('id_siswa', $data_siswa->id_siswa)
                        @endphp
                        @if($presensi_ekskul_peserta->kehadiran == 4)
                            Pertemuan ke-{{$presensi_ekskul->pertemuan_ke}}: {{\Carbon\Carbon::create($presensi_ekskul->tgl_entry)->isoFormat('dddd, D MMMM Y')}}<br>
                        @endif
                        @endforeach
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
<script>
    window.print();
</script>

</html>

