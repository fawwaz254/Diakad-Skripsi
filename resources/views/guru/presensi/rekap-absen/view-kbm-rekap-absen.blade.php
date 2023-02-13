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
                href="{{ url(Request::segment(1) . '#presensi/rekap-absen') }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        REKAP ABSEN KELAS {{ $data_kelas->nm_kelas }} <br>
                        HARI {{ $data_kelas->nm_jadwal_hari }} <br>
                        MAPEL {{ $data_kelas->nm_mata_pelajaran }} <br>
                        SEMESTER {{ $semester_aktif->tahun_ajaran }} {{ $semester_aktif->nm_semester }}</h2>
                    </h2>
                </div>
                <div class="body">
                    <div class="block-header">
                        <h2><a class="btn bg-blue waves-effect" target="_blank"
                                href="{{ url(Request::segment(1) . '/presensi/rekap-absen/print/' . $id_jadwal_kelas_mp) }}"><i
                                    class="material-icons">print</i><span>Cetak</span></a></h2>
                    </div>
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            style="overflow-x:auto;" id="primary_table">
                            <thead>
                                <tr>
                                    <th rowspan="2">No. </th>
                                    <th rowspan="2">NIS</th>
                                    <th rowspan="2">NISN</th>
                                    <th rowspan="2">Nama</th>
                                    <th colspan="25">Pertemuan pekan ke</th>
                                </tr>
                                <tr>
                                    @foreach ($data_presensi as $presensi_mp)
                                        <th>{{ $presensi_mp->pertemuan_ke }}<br>{{ date_format(date_create($presensi_mp->tgl_presensi), 'd/m/y') }}
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
                                        <td>{{ $siswa->nis_siswa }}</td>
                                        <td>{{ $siswa->nisn_siswa }}</td>
                                        <td>{{ $siswa->nm_pengguna }}</td>
                                        @foreach ($data_presensi as $presensi_mp)
                                            @php
                                                $rekap_absen[$presensi_mp->pertemuan_ke]['total_siswa'] = $presensi_mp->presensi_mp_siswa->count();
                                                $rekap_absen[$presensi_mp->pertemuan_ke]['total_hadir'] = $presensi_mp->presensi_mp_siswa->where('kehadiran', 1)->count();
                                            @endphp
                                            @if ($presensi_mp_siswa = $presensi_mp->presensi_mp_siswa->firstWhere('id_siswa', $siswa->id_siswa))
                                                @if ($presensi_mp_siswa->kehadiran == 1)
                                                    <td class="is-center bg-light-green"></td>
                                                @elseif($presensi_mp_siswa->kehadiran == 2)
                                                    <td class="is-center bg-amber">S</td>
                                                @elseif($presensi_mp_siswa->kehadiran == 3)
                                                    <td class="is-center bg-cyan">I</td>
                                                @elseif($presensi_mp_siswa->kehadiran == 4)
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
                                        <td>{{ round(($rekap_absen[$presensi_mp->pertemuan_ke]['total_hadir'] / $rekap_absen[$presensi_mp->pertemuan_ke]['total_siswa']) * 100, 2) }}%
                                        </td>
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
