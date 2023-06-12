<head>
    <title>
        {{-- Rekap Absen Kelas {{ $data_kelas->nm_kelas }}, Hari {{ $data_kelas->nm_jadwal_hari }},
        {{ $data_kelas->nm_mata_pelajaran }} ({{ $semester_aktif->tahun_ajaran }} {{ $semester_aktif->nm_semester }}) --}}
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

        table th.is-left,
        table td.is-left {
            text-align: left;
        }
    </style>
    <style type="text/css" media="print">
        @page {
            size: landscape;
        }
    </style>
</head>

<body>
    <h2 style="text-align: center; text-transform: capitalize;">
        REKAP ABSEN GURU {{ strtoupper($bulan->nm_bulan) }} {{ $tahun }}
    </h2>
    <h3 style="text-align: center">
        {{ $pengguna->gelar_depan . ' ' . $pengguna->nm_pengguna . ', ' . $pengguna->gelar_belakang }}
    </h3>
    <br>
    <br>
    <table class="is-bordered">
        <thead>
            <tr>
                <th rowspan="2">No.</th>
                <th rowspan="2">Nama</th>
                <th rowspan="2">Total Presensi</th>
                <th colspan="{{ $dates->count() }}">Tanggal</th>
            </tr>
            <tr>
                @foreach ($dates as $date)
                    <th>
                        {{ substr(\Carbon\Carbon::create($date)->isoFormat('dddd'), 0, 3) }}
                        <br>
                        {{ $date->format('d') }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($data_guru as $guru)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $guru->nm_pengguna }}</td>
                    <td style="text-align: center">
                        {{ $data_presensi->where('id_pengguna', $guru->id_pengguna)->count() }}
                    </td>
                    @foreach ($dates as $date)
                        @php
                            $all_presensi = $data_presensi
                                ->where('tgl_presensi', $date->format('Y-m-d'))
                                ->where('id_pengguna', $guru->id_pengguna)
                                ->all();
                        @endphp
                        @if ($auth_data->sekolah_data->nm_sekolah == 'SMK YPM 3 Taman')
                            @if (count($all_presensi) > 0)
                                <td style="background: #91d18b; text-align:center;">
                                    <b><a href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/monitoring-presensi-guru/' . $date->format('d') . '/' . $bulan->id_bulan . '/' . $tahun . '/' . $guru->id_pengguna) }}"
                                            class="target-link">{{ count($all_presensi) }}x</a></b>
                                </td>
                            @elseif($date->format('l') == 'Saturday' || $date->format('l') == 'Sunday')
                                <td style="background: #ffffff; text-align:center;">
                                    <b><a class="target-link">Libur</a></b>
                                </td>
                            @else
                                <td></td>
                            @endif
                        @else
                            @if (count($all_presensi) > 0)
                                <td style="background: #91d18b; text-align:center;">
                                    <b><a class="target-link">{{ count($all_presensi) }}x</a></b>
                                </td>
                            @elseif($date->format('l') == 'Sunday')
                                <td style="background: #ffffff; text-align:center;">
                                    <b><a class="target-link">Libur</a></b>
                                </td>
                            @else
                                <td></td>
                            @endif
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
<script>
    window.print();
</script>
