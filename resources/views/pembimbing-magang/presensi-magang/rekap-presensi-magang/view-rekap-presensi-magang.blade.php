<style>
    table th,
    .is-center {
        text-align: center;
        vertical-align: middle !important;
    }
</style>
<div class="container-fluid">
    <div class="block-header">
        {{-- <h2><a class="btn bg-blue waves-effect target-link "
                href="{{ url(Request::segment(1) . '#' . $auth_data->modul_url . '/' . $auth_data->menu_url) }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2> --}}
    </div>
    <div class="block-header">
        {{-- <h2><a class="btn bg-blue waves-effect "
                href="{{ url(Request::segment(1) . '/' . $auth_data->modul_url . '/' . $auth_data->menu_url . '/print/' . $id_semester . '/' . $id_ekskul) }}"
                target="_blank"><i class="material-icons">print</i><span>Cetak</span></a></h2> --}}
    </div>
    {{-- <div class="block-header">
        <h2><a class="btn bg-blue waves-effect" onclick="show()"><i
                    class="material-icons">remove_red_eye</i><span>Tampilkan Detail Pertemuan Ekskul</span></a></h2>
    </div> --}}
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        REKAP PRESENSI MAGANG
                        {{ $pembimbing_magang->rekanan->nm_rekanan_magang }} <br>
                        {{ $pembimbing_magang->periode->tgl_magang_mulai }} sampai
                        {{ $pembimbing_magang->periode->tgl_magang_selesai }}

                    </h2>
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            style="overflow-x:auto;" id="primary_table">
                            <thead>
                                <tr>
                                    <th rowspan="2">No. </th>
                                    <th rowspan="2">NIS</th>
                                    <th rowspan="2">Nama</th>
                                    <th colspan="{{ $presensi_magang->count() }}">Tanggal</th>
                                    <th rowspan="2">Action</th>
                                </tr>
                                <tr>
                                    @foreach ($presensi_magang as $presensi)
                                        <th>
                                            {{ $presensi->convertDateFormat('tanggal', 'l, j F Y ') }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($data_siswa as $siswa)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $siswa->siswa->nis_siswa }}</td>
                                        {{-- <td>{{ $siswa->siswa->nisn_siswa }}</td> --}}
                                        <td>{{ $siswa->siswa->pengguna->nm_pengguna }}</td>
                                        {{-- <td>{{ $siswa->kelas->nm_kelas }}</td> --}}
                                        @foreach ($presensi_magang as $presensi)
                                            @if ($presensi_magang_siswa = $presensi->presensiMagangSiswa->firstWhere('id_siswa', $siswa->id_siswa))
                                                @if ($presensi_magang_siswa->kehadiran == 1)
                                                    <td class="is-center bg-light-green"></td>
                                                @elseif($presensi_magang_siswa->kehadiran == 2)
                                                    <td class="is-center bg-amber">S</td>
                                                @elseif($presensi_magang_siswa->kehadiran == 3)
                                                    <td class="is-center bg-cyan">I</td>
                                                @elseif($presensi_magang_siswa->kehadiran == 0)
                                                    <td class="is-center bg-red">A</td>
                                                @else
                                                    <td></td>
                                                @endif
                                            @else
                                                <td></td>
                                            @endif
                                        @endforeach
                                        <td class="is-center">
                                            <a class=" btn btn-success btn-circle waves-effect waves-circle waves-float justify-content-center align-items-center"
                                                href="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/print/' . $pembimbing_magang->id_presensi_magang . '/' . $siswa->id_siswa) }}"
                                                target="_blank">
                                                <i class="material-icons">picture_as_pdf</i>
                                            </a>
                                        </td>
                                    </tr>
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
                    </div>
                </div>
                <div id="field2" style="display:none;">
                    <div class="header">
                        <h2>
                            {{-- DETAIL PERTEMUAN EKSKUL {{ $data_ekskul->nm_ekskul }} --}}
                        </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table
                                class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                                style="overflow-x:auto;" id="primary_table">
                                <thead>
                                    <th>No</th>
                                    <th>Pertemuan Ke</th>
                                    <th>Tanggal Entry</th>
                                    <th>Materi Ekskul</th>
                                    <th>Waktu Mulai</th>
                                    <th>Waktu Selesai</th>
                                </thead>
                                <tbody>
                                    @php
                                        $number = 1;
                                    @endphp
                                    {{-- @foreach ($data_presensi as $presensi_ekskul)
                                        <tr>
                                            <td>{{ $number++ }}</td>
                                            <td>{{ $presensi_ekskul->pertemuan_ke }}</td>
                                            <td>{{ $presensi_ekskul->convertDateFormat('tgl_entry', 'd/m/y') }}</td>
                                            <td>{{ $presensi_ekskul->materi_ekskul }}</td>
                                            <td>{{ $presensi_ekskul->waktu_mulai }}</td>
                                            <td>{{ $presensi_ekskul->waktu_selesai }}</td>
                                        </tr>
                                    @endforeach --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function show() {
        var x = document.getElementById("field2");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }

    function deleteAbsensiAction(delete_url, element) {
        var item = $(element);
        $('button').attr('disabled', 'disabled');


    }
</script>
