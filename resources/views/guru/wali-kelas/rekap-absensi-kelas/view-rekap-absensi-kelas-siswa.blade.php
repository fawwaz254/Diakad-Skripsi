<style>
    table th,
    .is-center {
        text-align: center;
        vertical-align: middle !important;
    }

</style>
<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link "
                href="{{ url(Request::segment(1) . '#wali-kelas/rekap-absensi-kelas') }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        REKAP ABSEN KELAS {{ $data_kelas->kelas_mp->kelas->nm_kelas }} <br>
                        HARI {{ $data_kelas->jadwal_hari->nm_jadwal_hari }} <br>
                        MAPEL {{ $data_kelas->kelas_mp->mata_pelajaran->nm_mata_pelajaran }} <br>
                        SEMESTER {{ $semester_aktif->tahun_ajaran }} {{ $semester_aktif->nm_semester }}</h2>
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            style="overflow-x:auto;" id="primary_table">
                         <thead>
                            <tr>
                                <th rowspan="2">No.</th>
                                <th rowspan="2">NIS</th>
                                <th rowspan="2">NISN</th>
                                <th rowspan="2">Nama</th>
                                <th colspan="{{ count($data_presensi) }}">Pertemuan pekan ke</th>
                            </tr>
                            <tr>
                                @foreach ($data_presensi as $presensi_mp)
                                    <th>{{ $presensi_mp->pertemuan_ke }}<br>{{ date('d/m/y', strtotime($presensi_mp->tgl_presensi)) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                                $rekap_absen = [];
                            @endphp
                            
                            @foreach ($data_siswa as $siswa)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $siswa->nis_siswa }}</td>
                                    <td>{{ $siswa->nisn_siswa }}</td>
                                    <td>{{ $siswa->nm_pengguna }}</td>
                                    
                                    @foreach ($data_presensi as $presensi_mp)
                                        @php
                                            $siswa_presensi = $data_presensi_siswa[$presensi_mp->id_presensi_mp][$siswa->id_siswa] ?? null;

                                            $rekap_absen[$presensi_mp->id_presensi_mp]['total_siswa'] = 
                                                ($rekap_absen[$presensi_mp->id_presensi_mp]['total_siswa'] ?? 0) + 1;

                                            if ($siswa_presensi && $siswa_presensi->kehadiran == 1) {
                                                $rekap_absen[$presensi_mp->id_presensi_mp]['total_hadir'] =
                                                    ($rekap_absen[$presensi_mp->id_presensi_mp]['total_hadir'] ?? 0) + 1;
                                            }
                                        @endphp
                                        @if ($siswa_presensi)
                                            @if ($siswa_presensi->kehadiran == 1)
                                                <td class="is-center bg-light-green"></td>
                                            @elseif($siswa_presensi->kehadiran == 2)
                                                <td class="is-center bg-amber">S</td>
                                            @elseif($siswa_presensi->kehadiran == 3)
                                                <td class="is-center bg-cyan">I</td>
                                            @elseif($siswa_presensi->kehadiran == 4)
                                                <td class="is-center bg-red">A</td>
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
                                <th colspan="4">Persentase Absen</th>
                                @foreach ($data_presensi as $presensi_mp)
                                    @php
                                        $rekap = $rekap_absen[$presensi_mp->id_presensi_mp] ?? ['total_siswa' => 0, 'total_hadir' => 0];
                                        $persentase = $rekap['total_siswa'] > 0
                                            ? round(($rekap['total_hadir'] / $rekap['total_siswa']) * 100, 2)
                                            : 0;
                                    @endphp
                                    <td>{{ $persentase }}%</td>
                                @endforeach
                            </tr>
                        </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // var primary_table = $('#primary_table').DataTable({
    //     pageLength: 100
    // });
</script>
