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
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3)) }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
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
                                    <th rowspan="2">Nama</th>
                                    <th>Tanggal</th>
                                </tr>
                                <tr>
                                    <th> {{ $presensi_magang->convertDateFormat('tanggal', 'l, j F Y ') }}</th>

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
                                        <td>{{ $siswa->siswa->pengguna->nm_pengguna }}</td>
                                        @if ($presensi_magang_siswa = $presensi_magang->presensiMagangSiswa->firstWhere('id_siswa', $siswa->id_siswa))
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
                                    </tr>
                                @endforeach
                                <tr>
                                    <th colspan="3">Persentase Absen</th>
                                    <td class="is-center">
                                        {{ ($presensi_magang->presensiMagangSiswa->where('kehadiran', '1')->count() / $presensi_magang->presensiMagangSiswa->count()) * 100 }}%
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="3">Action Edit</th>
                                    <td class="is-center">
                                        <a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float"
                                            href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3) . '/manage/' . $presensi_magang->id_presensi_magang) }}">
                                            <i class="material-icons">edit</i>
                                        </a>
                                        <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float"
                                            onclick="deleteAbsensiAction('{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/action/delete') }}', this)"
                                            data-id="{{ $presensi_magang->id_presensi_magang }}">
                                            <i class="material-icons">delete_forever</i>
                                        </button>
                                    </td>

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
                                '{{ Request::segment(1) }}/{{ Request::segment(2) }}/{{ Request::segment(3) }}'
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
