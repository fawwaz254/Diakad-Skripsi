<head>
<title>
    Rekap Absen Kelas {{$data_kelas->nm_kelas}}, Hari {{$data_kelas->nm_jadwal_hari}}, {{$data_kelas->nm_mata_pelajaran}} ({{$semester_aktif->tahun_ajaran}} {{$semester_aktif->nm_semester}})
</title>
    <style>
        table.is-bordered, table.is-bordered th, table.is-bordered td{
            border: 1px solid black;
            border-spacing: 0;
        }
        table.is-bordered th, table.is-bordered td{
            padding: 0.5em;
        }
        table.is-bordered th, .is-center{
            text-align:center; 
            vertical-align:middle !important;
        }
        table th.is-left, table td.is-left{
            text-align:left;
        }
    </style>
    <style type="text/css" media="print">
        @page { size: landscape; }
    </style>
</head>

<body>
    <h2>
        REKAP ABSEN KELAS
    </h2>
    <table>
        <tbody>
            <tr>
                <th class="is-left">Kelas</th>
                <td style="width: 50%;">: {{$data_kelas->nm_kelas}}</td>
                <th class="is-left">Mata pelajaran</th>
                <td class="is-left">: {{$data_kelas->nm_mata_pelajaran}}</td>
            </tr>
            <tr>
                <th class="is-left">Hari</th>
                <td style="width: 50%;">: {{$data_kelas->nm_jadwal_hari}}</td>
                <th class="is-left">Semester</th>
                <td class="is-left">: {{$semester_aktif->tahun_ajaran}} {{$semester_aktif->nm_semester}}</td>
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
                <th colspan="25">Pertemuan pekan ke</th>
            </tr>
            <tr>
                @foreach($data_presensi as $presensi_mp)
                <th>{{$presensi_mp->pertemuan_ke}}<br>{{date_format(date_create($presensi_mp->tgl_presensi),"d/m/y")}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $rekap_presensi = [];
            @endphp
            @foreach($data_siswa as $siswa)
            <tr>
                <td>{{$no++}}</td>
                <td>{{$siswa->nis_siswa}}</td>
                <td>{{$siswa->nisn_siswa}}</td>
                <td>{{$siswa->nm_pengguna}}</td>
                @foreach($data_presensi as $presensi_mp)
                    @php
                        $rekap_absen[$presensi_mp->pertemuan_ke]['total_siswa'] = $presensi_mp->presensi_mp_siswa->count();
                        $rekap_absen[$presensi_mp->pertemuan_ke]['total_hadir'] = $presensi_mp->presensi_mp_siswa->where('kehadiran', 1)->count();
                    @endphp
                    @if($presensi_mp_siswa = $presensi_mp->presensi_mp_siswa->firstWhere('id_siswa', $siswa->id_siswa))
                        @if($presensi_mp_siswa->kehadiran == 1)
                        <td class="is-center"> &#10004; </td>
                        @elseif($presensi_mp_siswa->kehadiran == 2)
                        <td class="is-center">S</td>
                        @elseif($presensi_mp_siswa->kehadiran == 3)
                        <td class="is-center">I</td>
                        @elseif($presensi_mp_siswa->kehadiran == 4)
                        <td class="is-center">A</td>
                        @else
                        <td class="is-center"></td>
                        @endif
                    @else
                        <td></td>
                    @endif
                @endforeach
            </tr>
            @endforeach
            <tr>
                <th colspan="4">Persentase Absen</th>
                @foreach($data_presensi as $presensi_mp)
                <td>{{round(($rekap_absen[$presensi_mp->pertemuan_ke]['total_hadir'] / $rekap_absen[$presensi_mp->pertemuan_ke]['total_siswa'] * 100), 2)}}%</td>
                @endforeach
            </tr>
        </tbody>
    </table>
</body>
<script>
    window.print();
</script>