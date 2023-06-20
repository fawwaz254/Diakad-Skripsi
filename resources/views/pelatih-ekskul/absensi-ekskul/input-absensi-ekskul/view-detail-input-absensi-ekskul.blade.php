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
                href="{{ url(Request::segment(1) . '#' . $auth_data->modul_url . '/' . $auth_data->menu_url . '/' . $semester_aktif->id_semester . '/' . $data_ekskul->id_ekskul) }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        ABSEN HARIAN KELAS {{ $data_ekskul->nm_ekskul }} Semester {{ $semester_aktif->tahun_ajaran }}
                        {{ $semester_aktif->nm_semester }} Bulan {{ $bulan->nm_bulan }}</h2>
                    </h2>
                </div>
                <div class="body">
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
                                    <th rowspan="2">Kelas</th>
                                    <th colspan="{{ $data_presensi->count() }}">Tanggal</th>
                                </tr>
                                <tr>
                                    @foreach ($data_presensi as $presensi_ekskul)
                                        <th>
                                            {{ $presensi_ekskul->pertemuan_ke }}
                                            <br>
                                            {{ $presensi_ekskul->convertDateFormat('tgl_entry', 'd/m/y') }}
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
                                        <td>{{ $siswa->siswa->nisn_siswa }}</td>
                                        <td>{{ $siswa->siswa->pengguna->nm_pengguna }}</td>
                                        <td>{{ $siswa->kelas->nm_kelas }}</td>
                                        @foreach ($data_presensi as $presensi_ekskul)
                                            @if ($presensi_ekskul_peserta = $presensi_ekskul->presensi_ekskul_peserta->firstWhere('id_siswa', $siswa->id_siswa))
                                                @if ($presensi_ekskul_peserta->kehadiran == '1')
                                                    <td class="is-center bg-light-green"></td>
                                                @elseif($presensi_ekskul_peserta->kehadiran == '2')
                                                    <td class="is-center bg-amber">S</td>
                                                @elseif($presensi_ekskul_peserta->kehadiran == '3')
                                                    <td class="is-center bg-cyan">I</td>
                                                @elseif($presensi_ekskul_peserta->kehadiran == '4')
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
                                    <th colspan="5">Persentase Absen</th>
                                    @foreach ($data_presensi as $presensi_ekskul)
                                        <td>{{ round($presensi_ekskul->persentase_presensi_ekskul * 100, 2) }}%</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <th colspan="5">Action Edit</th>
                                    @foreach ($data_presensi as $presensi_ekskul)
                                        <td>
                                            <a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float"
                                                href="{{ url(Request::segment(1) . '#' . $auth_data->modul_url . '/' . $auth_data->menu_url . '/manage' . '/' . $semester_aktif->id_semester . '/' . $data_ekskul->id_ekskul . '/' . $presensi_ekskul->id_presensi_ekskul) }}">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <button
                                                class="btn btn-danger btn-circle waves-effect waves-circle waves-float"
                                                onclick="deleteAbsensiAction('{{ url(Request::segment(1) . '/' . $auth_data->modul_url . '/' . $auth_data->menu_url . '/action/delete') }}', this)"
                                                data-id="{{ $presensi_ekskul->id_presensi_ekskul }}">
                                                <i class="material-icons">delete_forever</i>
                                            </button>
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
    function deleteAbsensiAction(delete_url, element) {
        var item = $(element);
        $('button').attr('disabled', 'disabled');

        swal({
            title: "Are you sure?",
            text: "You won't be able to delete this!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(result) {
            if (result) {
                $.ajax({
                    type: "POST",
                    url: delete_url + '/' + item.attr('data-id'),
                    success: function(response) {
                        if (response.status == 200) {
                            vex.dialog.alert(response.message);
                            loadContent(
                                '{{ $auth_data->modul_url }}/{{ $auth_data->menu_url }}/detail/{{ $semester_aktif->id_semester }}/{{ $data_ekskul->id_ekskul }}/{{ $tahun }}/{{ $bulan->id_bulan }}'
                            );
                        } else if (response.status == 300) {
                            vex.dialog.alert(response.message);
                        }
                    },
                    complete: function() {
                        $('button').removeAttr('disabled', 'disabled');
                    }
                });
            } else {
                $('button').removeAttr('disabled', 'disabled');
            }
        });
    }
</script>
