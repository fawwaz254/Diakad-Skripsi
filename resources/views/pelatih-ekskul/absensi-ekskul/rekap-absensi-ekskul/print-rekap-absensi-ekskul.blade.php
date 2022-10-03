<head>
    <title>
        REKAP ABSEN EKSKUL {{ $data_ekskul->nm_ekskul }} Semester {{ $semester_aktif->tahun_ajaran }}
        {{ $semester_aktif->nm_semester }}
    </title>
    <style>
        table.is-bordered,
        table.is-bordered th,
        table.is-bordered td {
            border: 1px solid black;
            border-spacing: 0;
        }

        table.is-bordered th,
        table.is-bordered td {
            padding: 0.5em;
        }

        table.is-bordered th,
        .is-center {
            text-align: center;
            vertical-align: middle !important;
        }

        table th.is-right,
        table td.is-right {
            text-align: right;
        }
    </style>
    <style type="text/css" media="print">
        @page {
            size: landscape;
        }
    </style>
</head>

<body>
    <h2>
        REKAP ABSEN EKSKUL
    </h2>
    <table>
        <tbody>
            <tr>
                <th>Ekskul</th>
                <td style="width: 50%;">: {{ $data_ekskul->nm_ekskul }}</td>
                <th class="is-right">Semester</th>
                <td class="is-right">: {{ $semester_aktif->tahun_ajaran . ' - ' . $semester_aktif->nm_semester }}</td>
            </tr>
            <tr>
                <th>Pelatih</th>
                <td colspan="3">: {{ implode(', ', $data_pelatih) }}</td>
            </tr>
        </tbody>
    </table>
    <br>
    <br>

    <table class="is-bordered">
        <thead>
            <tr>
                <th rowspan="2">No. </th>
                <th rowspan="2">NIS</th>
                <th rowspan="2">NISN</th>
                <th rowspan="2">Nama</th>
                <th rowspan="2">Kelas</th>
                <th colspan="25">Pertemuan pekan ke</th>
            </tr>
            <tr>
                @foreach ($data_presensi as $presensi_ekskul)
                    <th>{{ $presensi_ekskul->pertemuan_ke }}<br>{{ date_format(date_create($presensi_ekskul->tgl_entry), 'd/m/y') }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $rekap_presensi = [];
            @endphp
            @foreach ($data_siswa as $siswa)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $siswa->siswa->nis_siswa }}</td>
                    <td>{{ $siswa->siswa->nisn_siswa }}</td>
                    <td>{{ $siswa->siswa->pengguna->nm_pengguna }}</td>
                    <td>{{ $siswa->kelas->nm_kelas }}</td>
                    @foreach ($data_presensi as $presensi_ekskul)
                        @php
                            $rekap_absen[$presensi_ekskul->pertemuan_ke]['total_siswa'] = $presensi_ekskul->presensi_ekskul_peserta->count();
                            $rekap_absen[$presensi_ekskul->pertemuan_ke]['total_hadir'] = $presensi_ekskul->presensi_ekskul_peserta->where('kehadiran', 1)->count();
                        @endphp
                        @if ($presensi_ekskul_peserta = $presensi_ekskul->presensi_ekskul_peserta->firstWhere(
                            'id_siswa',
                            $siswa->id_siswa))
                            @if ($presensi_ekskul_peserta->kehadiran == 1)
                                <td class="is-center" style="background-color: greenyellow"> &#10004; </td>
                            @elseif($presensi_ekskul_peserta->kehadiran == 2)
                                <td class="is-center" style="background-color: orange">S</td>
                            @elseif($presensi_ekskul_peserta->kehadiran == 3)
                                <td class="is-center" style="background-color: lightblue">I</td>
                            @elseif($presensi_ekskul_peserta->kehadiran == 4)
                                <td class="is-center" style="background-color: red">A</td>
                            @else
                                <td></td>
                            @endif
                        @else
                            <td></td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
            <tr>
                <th colspan="5">Persentase Absen</th>
                @foreach ($data_presensi as $presensi_ekskul)
                    <td>{{ round(($rekap_absen[$presensi_ekskul->pertemuan_ke]['total_hadir'] / $rekap_absen[$presensi_ekskul->pertemuan_ke]['total_siswa']) * 100, 2) }}%
                    </td>
                @endforeach
            </tr>
        </tbody>
    </table>
</body>
<script>
    window.print();
</script>
