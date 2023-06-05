<head>
    <title>
        REKAP PRESENSI MAGANG {{ $pembimbing_magang->rekanan->nm_rekanan_magang }} TANGGAL
        {{ $pembimbing_magang->periode->tgl_magang_mulai . ' - ' . $pembimbing_magang->periode->tgl_magang_selesai }}
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

        .is-left {
            text-align: left;
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
        REKAP PRESENSI MAGANG
    </h2>
    <table>
        <tbody>
            <tr>
                <th class="is-left">Rekanan</th>
                <td>: {{ $pembimbing_magang->rekanan->nm_rekanan_magang }}</td>
            </tr>
            <tr>
                <th class="is-left">Tanggal</th>
                <td>:
                    {{ $pembimbing_magang->periode->tgl_magang_mulai . ' - ' . $pembimbing_magang->periode->tgl_magang_selesai }}
                </td>
            </tr>
            <tr>
                <th class="is-left">Pembimbing</th>
                <td>: {{ $pembimbing_magang->pengguna->nm_pengguna }}</td>
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
                <th rowspan="2">Nama</th>
                <th colspan="25">Tanggal</th>
            </tr>
            <tr>
                @foreach ($presensi_magang as $presensi)
                    <th> {{ $presensi->convertDateFormat('tanggal', 'l, j F Y ') }}
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
                    <td>{{ $siswa->siswa->pengguna->nm_pengguna }}</td>

                    @foreach ($presensi_magang as $presensi)
                        @if ($presensi_magang_siswa = $presensi->presensiMagangSiswa->firstWhere('id_siswa', $siswa->id_siswa))
                            @if ($presensi_magang_siswa->kehadiran == 1)
                                <td class="is-center" style="background-color: greenyellow"> &#10004; </td>
                            @elseif($presensi_magang_siswa->kehadiran == 2)
                                <td class="is-center" style="background-color: orange">S</td>
                            @elseif($presensi_magang_siswa->kehadiran == 3)
                                <td class="is-center" style="background-color: lightblue">I</td>
                            @elseif($presensi_magang_siswa->kehadiran == 0)
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
                <th colspan="3">Persentase Absen</th>
                @foreach ($presensi_magang as $presensi)
                    <td class="is-center">
                        {{ ($presensi->presensiMagangSiswa->where('kehadiran', '1')->count() / $presensi->presensiMagangSiswa->count()) * 100 }}%
                    </td>
                @endforeach
            </tr>
        </tbody>
    </table>
</body>
<script>
    window.print();
</script>
