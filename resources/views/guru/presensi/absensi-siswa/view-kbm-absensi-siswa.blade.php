<style>

</style>
<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link "
                href="{{ url(Request::segment(1) . '#presensi/absensi-siswa') }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    {{-- data-id="{{ $id_jadwal_kelas_mp }}" pertemuan-id="{{ $pertemuan_ke }}" --}}
    <input type="hidden" id="data-id" value="{{ $id_jadwal_kelas_mp }}">
    <input type="hidden" id="pertemuan-id" value="{{ $pertemuan_ke }}">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>KELAS {{ $data_kelas->nm_kelas }} <br>
                        MAPEL {{ $data_kelas->nm_mata_pelajaran }}
                        <br>
                        SEMESTER {{ $semester_aktif->tahun_ajaran }} {{ $semester_aktif->nm_semester }}
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/action-absensi-siswa/add-kbm/' . $id_jadwal_kelas_mp . '/' . $pertemuan_ke) }}">
                        {{ csrf_field() }}
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display nowrap"
                                id="primary_table" style="overflow-x: scroll;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIS</th>
                                        <th>Kehadiran siswa</th>
                                        <th>
                                            Nilai Karakter
                                            <br/>
                                            <input id="checkbox_select_all_karakter" type="checkbox" name="select_all" checked class="filled-in" data-type="is-karakter">
                                            <label for="checkbox_select_all_karakter" style="margin-bottom: -10px;"><small>Select all</small></label>
                                        </th>
                                        <th>
                                            Kegiatan
                                            <br/>
                                            <input id="checkbox_select_all_kegiatan" type="checkbox" name="select_all" checked class="filled-in" data-type="is-kegiatan">
                                            <label for="checkbox_select_all_kegiatan" style="margin-bottom: -10px;"><small>Select all</small></label>
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <h2 class="card-inside-title">
                            Pertemuan pekan ke
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="pertemuan_ke" required=""
                                    aria-required="true" aria-invalid="true" value="{{ $pertemuan_ke }}" readonly>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                @if ($presensi_mp_aktif)
                                    <input type="text" class="datepicker form-control" name="tgl_presensi"
                                        required="" aria-required="true" aria-invalid="true"
                                        value="{{ $presensi_mp_aktif->tgl_presensi }}">
                                @else
                                    <input type="text" class="datepicker form-control" name="tgl_presensi"
                                        required="" aria-required="true" aria-invalid="true"
                                        value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}">
                                @endif
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Uraian Materi
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                @if ($presensi_mp_aktif)
                                    <textarea class="form-control" name="uraian_materi" rows="4" cols="100">{{ $presensi_mp_aktif->uraian_materi }}</textarea>
                                @else
                                    <textarea class="form-control" name="uraian_materi" rows="4" cols="100">{{ !empty($mapel_rpp_detail)? $mapel_rpp_detail->deskripsi : ''  }}</textarea>
                                @endif
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Waktu Mulai
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                @if ($presensi_mp_aktif)
                                    <input type="text" class="datepicker-time form-control" name="waktu_mulai"
                                        required="" aria-required="true" aria-invalid="true"
                                        value="{{ $presensi_mp_aktif->waktu_mulai }}">
                                @else
                                    <input type="text" class="datepicker-time form-control" name="waktu_mulai"
                                        required="" aria-required="true" aria-invalid="true"
                                        value="{{ \Carbon\Carbon::parse($data_kelas->jam_mulai . ':' . $data_kelas->menit_mulai)->format('H:i') }}">
                                @endif
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Waktu Selesai
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                @if ($presensi_mp_aktif)
                                    <input type="text" class="datepicker-time form-control" name="waktu_selesai"
                                        required="" aria-required="true" aria-invalid="true"
                                        value="{{ $presensi_mp_aktif->waktu_selesai }}">
                                @else
                                    <input type="text" class="datepicker-time form-control" name="waktu_selesai"
                                        required="" aria-required="true" aria-invalid="true"
                                        value="{{ \Carbon\Carbon::parse($data_kelas->jam_selesai . ':' . $data_kelas->menit_selesai)->format('H:i') }}">
                                @endif
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                                style="margin-bottom: 30px; margin-left: 20">
                                <button class="btn btn-block bg-green waves-effect" type="submit"><i
                                        class="material-icons">save</i><span>Save</span></button>
                            </div>

                        </div>
                    </form>
                    @if ($data)
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-6 col-xs-6" style="margin-top: -25px">
                                <button class="btn btn-block bg-red waves-effect delete-record"><i
                                        class="material-icons">delete
                                    </i><span>Delete</span></button>
                            </div>
                        </div>
                    @else
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
</div>
</div>
@include('scriptjs')
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var id_jadwal_kelas_mp = {!! json_encode($id_jadwal_kelas_mp) !!};
    var pertemuan_ke = {!! json_encode($pertemuan_ke) !!};

    var modul_url = 'presensi';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'absensi-siswa/datatables-kbm/' +
        id_jadwal_kelas_mp + '/' + pertemuan_ke;
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'absensi-siswa/datatables-kbm/delete' +
        id_jadwal_kelas_mp;

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        // serverSide: true,
        pageLength: 100,
        ajax: {
            url: datatable_url,
            type: 'GET'
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'nis_siswa',
                name: 'siswa.nis_siswa',
                visible: false,
            },
            {
                data: 'alasan',
                name: 'pengguna.nm_pengguna',
                render: function(data, type, row) {
                    var data_siswa = `${row.nis_siswa.nis_siswa}<br/>
                    ${row.nm_pengguna}<br/>`;

                    if (data.status_pengguna.status == 1) {
                        var html = '';
                        $.each(data.options, function(index, item) {
                            if (data.kehadiran == item.id) {
                                html += '<option value="' + item.id + '" selected>' + item
                                    .text + '</option>';
                            } else {
                                html += '<option value="' + item.id + '">' + item.text +
                                    '</option>';
                            }
                        })
                        return data_siswa + '<br><input type="hidden" name="id_siswa[]" value="' + row.nis_siswa.id_siswa + '"/><select class="form-control show-tick" style="width:85px;" name="alasan[]">' +
                            html +
                            '</select>';
                    } else {
                        return data_siswa + '<p class="font-underline col-orange font-24">' + data.status_pengguna
                            .nm_status + '</p>';
                    }
                }
            },
            {
                data: 'nilai_karakter',
                searchable: false,
                orderable: false,
                render: function(data, type, row) {
                    if (data.status_pengguna.status == 1) {
                        var html = '';
                        var i = 0;
                        $.each(data.options, function(index, item) {
                            html += `<span><input id="ck-${row.nis_siswa.id_siswa}-${i}" type="checkbox" name="id_karakter_siswa[${row.nis_siswa.id_siswa}][]" checked class="is-karakter filled-in" value="${item}">
                                        <label for="ck-${row.nis_siswa.id_siswa}-${i}">${item}</label></span><br/>`;
                            i++;
                        })
                        return html;
                    } else {
                        return '';
                    }
                }
            },
            {
                data: 'reward_kegiatan',
                searchable: false,
                orderable: false,
                render: function(data, type, row) {
                    if (data.status_pengguna.status == 1) {
                        var html = '';
                        var i = 0;
                        $.each(data.options, function(index, item) {
                            html += `<span><input id="ars-${row.nis_siswa.id_siswa}-${i}" type="checkbox" name="aktivitas_reward[${row.nis_siswa.id_siswa}][]" checked class="is-kegiatan filled-in" value="${item}">
                                        <label for="ars-${row.nis_siswa.id_siswa}-${i}">${item}</label></span><br/>`;
                            i++;
                        })
                        return html;
                    } else {
                        return '';
                    }
                }
            },
        ],
        order: [
            [1, 'asc']
        ]
    });

    primary_table.on('draw', function() {
        primary_table.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        });
    }).draw();


    $(function() {
        $('.datepicker-time').bootstrapMaterialDatePicker({
            format: 'HH:mm',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: true,
            date: false
        });

        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD',
            clearButton: true,
            weekStart: 1,
            time: false
        });
    });


    $(".delete-record").click(function() {
        var token = $("meta[name='csrf-token']").attr("content");
        var pertemuan_id = $('#pertemuan-id').val();
        var data_id = $('#data-id').val();

        swal({
                title: "Are you sure?",
                showCancelButton: true
            },
            function(isConfirm) {
                if (isConfirm) {
                    $('.delete-record').attr("disabled", true);
                    //swall 

                    $.ajax({
                        // url: url(Request::segment(1).'/'.Request::segment(2).'/action-absensi-siswa/add-kbm/'.data_id.'/'.pertemuan_id),
                        url: '{{ Request::segment(1) }}/{{ Request::segment(2) }}/action-absensi-siswa/delete-kbm/' +
                            data_id + '/' + pertemuan_id,
                        type: "post",

                        data: {
                            _token: token,
                        },
                        success: function(response) {
                            swal({
                                title: "Delete Success",
                                text: "data berhasil dihapus",
                                icon: "success",
                            });
                            loadURI(response.path);
                        },
                    });
                }
                return;
            }
        );
    });

    $('input[name="select_all"]').change(function() {
        var select_all_checked = this.checked;
        var rows = primary_table.rows({
            'search': 'applied'
        }).nodes();

        console.log($(this).attr('data-type'));
        $('.' + $(this).attr('data-type'), rows).prop('checked', this.checked);
    });
</script>
