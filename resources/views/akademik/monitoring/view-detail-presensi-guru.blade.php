<div class="container-fluid">
    <h2>
        <a class="btn bg-blue waves-effect target-link"
            href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3) . '/' . $id_bulan . '/' . $tahun) }}">
            <i class="material-icons">backspace</i><span>Kembali</span>
        </a>
    </h2>

    <div class="row clearfix card">
        <div class="header" style="padding-top: 0px; padding-bottom: 0px">
            <h3>Presensi KBM </h3>
        </div>
        <div class="body">
            <table class="table table-striped" id="primary_table">
                {{-- <br> --}}
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th>Tanggal Presensi</th>
                        <th>Pertemuan Ke-</th>
                        <th>Jadwal</th>
                        <th>Kelas</th>
                        <th>Uraian Materi</th>
                        <th style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data_presensi as $presensi)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::create($presensi->tgl_presensi)->isoFormat('dddd, D MMMM Y') }}</td>
                            <td>{{ $presensi->pertemuan_ke }}</td>
                            <td>{{ $presensi->waktu_mulai }} - {{ $presensi->waktu_selesai }}</td>
                            <td>{{ $presensi->nm_kelas_mp }}</td>
                            <td>{{ $presensi->uraian_materi }}</td>

                            <td> <button data-id="{{ $presensi->id_presensi_mp }}" style="margin-left:3px;"
                                    class="btn bg-red waves-effect delete-record">
                                    <i class="material-icons">delete</i>
                                </button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <hr>
    <div class="row clearfix card">
        <div class="header" style="padding-top: 0px; padding-bottom: 0px">
            <h3>Presensi KBM Tanpa Jadwal (BLOK) </h3>
        </div>
        <div class="body">
            <table class="table table-striped" id="primary_table2">
                <br>
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th>Tanggal Presensi</th>
                        <th>Pertemuan Ke-</th>
                        <th>Jadwal</th>
                        <th>Kelas</th>
                        <th>Uraian Materi</th>
                        <th style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data_presensi_kbmTanpaJadwal as $presensiTanpaJadwal)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::create($presensiTanpaJadwal->tgl_presensi)->isoFormat('dddd, D MMMM Y') }}
                            </td>
                            <td>{{ $presensiTanpaJadwal->pertemuan_ke }}</td>
                            <td>{{ $presensiTanpaJadwal->waktu_mulai }} - {{ $presensiTanpaJadwal->waktu_selesai }}
                            </td>
                            <td>{{ $presensiTanpaJadwal->nm_kelas_mp }}</td>
                            <td>{{ $presensiTanpaJadwal->uraian_materi }}</td>

                            <td> <button data-id="{{ $presensiTanpaJadwal->id_presensi_mp }}" style="margin-left:3px;"
                                    class="btn bg-red waves-effect delete-record">
                                    <i class="material-icons">delete</i>
                                </button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
    var primary_table = $('#primary_table').DataTable({
        dom: 'Bfrtip',
        lengthMenu: dtLengButton,
        buttons: dtButtonConfig,
    })

    var primary_table2 = $('#primary_table2').DataTable({
        dom: 'Bfrtip',
        lengthMenu: dtLengButton,
        buttons: dtButtonConfig,
    })

    $(".delete-record").click(function() {
        var token = $("meta[name='csrf-token']").attr("content");
        var id = $(this).data("id");

        swal({
                title: "Are you sure?",
                showCancelButton: true
            },
            function(isConfirm) {
                if (isConfirm) {
                    $('.delete-record').attr("disabled", true);
                    //swall
                    $.ajax({
                        url: ` /akademik/monitoring/monitoring-presensi-guru/action-delete/${id}`,
                        type: "post",

                        data: {
                            _token: token,
                        },

                        success: function() {
                            swal({
                                title: "Delete Success",
                                text: "data berhasil dihapus",
                                icon: "success",
                            });
                            location.reload(true);
                            // loadURI('{{ Request::segment(2) }}/{{ Request::segment(3) }}/' +
                            //     $('input[name=date]').val() + '/0/0');
                        },
                    });
                }
                return;
            }
        );
    });
</script>
