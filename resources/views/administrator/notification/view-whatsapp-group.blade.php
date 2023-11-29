<div class="container-fluid">
    <div class="card" style="margin-bottom: 3rem">
        <div class="header">
            <h2 style="float:left; font-size:2rem">TEMPLATE PESAN</h2>

            <div style="clear: both;"></div>
        </div>
        <div class="body">
            <label style="margin-top:1rem">Jadwal Hari Notifikasi</label>
            <input type="text" name="day_schedule" class="form-control"
                placeholder="MONDAY|TUESDAY|WEDNESDAY|THURSDAY|FRIDAY|SATURDAY" value="{{ $day_schedule_setting }}">
            <label style="margin-top:1rem">Mode Kehadiran/Ketidakhadiran Siswa</label>
            <select class="form-control show-tick" name="attendance_mode">
                <option value="ABSENT_ONLY" {{ $mode_attendance_setting == 'ABSENT_ONLY' ? 'selected' : '' }}>Tidak
                    Hadir Saja</option>
                <option value="PRESENT_ONLY" {{ $mode_attendance_setting == 'PRESENT_ONLY' ? 'selected' : '' }}>Hadir
                    Saja
                </option>
                <option value="ALL" {{ $mode_attendance_setting == 'ALL' ? 'selected' : '' }}>Semua</option>
            </select>
            <div style="margin-top:1rem">
                <small>
                    <strong>KODE TEMPLATE:</strong><br>
                    <strong>@{{ CLASS }}</strong> : data kelas dinamis <small style="color: red">*wajib
                        ada</small><br>
                    <strong>@{{ DATE }}</strong> : data tanggal dinamis <small style="color: red">*wajib
                        ada</small><br>
                    <strong>@{{ SCHOOL }}</strong> : data sekolah dinamis <small style="color: red">*wajib
                        ada</small><br>
                    <strong>@{{ STUDENTS }}</strong> : data list siswa dinamis <small style="color: red">*wajib
                        ada</small><br>
                    <strong>*text*</strong> : kode untuk BOLD text<br>
                    <strong>_text_</strong> : kode untuk ITALIC text<br>
                </small>
            </div>
            <label>Template Kehadiran/Ketidakhadiran Siswa</label>
            <textarea name="attendance_template" class="form-control" rows="20">{!! $template_attendance_setting !!}</textarea>
            <label style="margin-top:1rem">Jadwal Kehadiran/Ketidakhadiran Siswa</label>
            <input type="time" name="attendance_time_schedule" class="form-control"
                value="{{ $attendance_time_setting }}">
            <hr>
            <div style="margin-top:1rem">
                <small>
                    <strong>KODE TEMPLATE:</strong><br>
                    <strong>@{{ CLASS }}</strong> : data kelas dinamis <small style="color: red">*wajib
                        ada</small><br>
                    <strong>@{{ DATE }}</strong> : data tanggal dinamis <small style="color: red">*wajib
                        ada</small><br>
                    <strong>@{{ SCHOOL }}</strong> : data sekolah dinamis <small style="color: red">*wajib
                        ada</small><br>
                    <strong>@{{ STUDENTS }}</strong> : data list siswa dinamis <small style="color: red">*wajib
                        ada</small><br>
                    <strong>*text*</strong> : kode untuk BOLD text<br>
                    <strong>_text_</strong> : kode untuk ITALIC text<br>
                </small>
            </div>
            <label style="margin-top:1rem">Template Pembayaran SPP Siswa</label>
            <textarea name="payment_template" class="form-control" rows="20">{{ $template_payment_setting }}</textarea>
            <label style="margin-top:1rem">Jadwal Pembayaran SPP Siswa</label>
            <input type="time" name="payment_time_schedule" class="form-control" value="{{ $payment_time_setting }}">

            <button class="btn bg-green waves-effect" style="float:right;margin-top:1rem" onclick="actionUpdate()"><i
                    class="material-icons">save</i><span>Update</span></button>
            <div style="clear: both;"></div>
        </div>
    </div>

    <div class="card" style="margin-bottom: 3rem">
        <div class="header">
            <h2 style="float:left; font-size:2rem">LIST GRUP</h2>
            <a style="float:right; font-size:2rem" class="btn bg-green"
                href="{{ request()->segment(1) . request()->segment(2) . '#notification/whatsapp/scan' }}"
                target="_blank">
                <i class="material-icons">sync</i> SCAN QR CODE
            </a>

            <div style="clear: both;"></div>
        </div>
        <div class="body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover responsive" id="primary_table">
                    <thead>
                        <tr>
                            <th width="5">No</th>
                            <th>Nama Grup</th>
                            <th>ID Grup</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="data-fetch">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="modal-action" class="modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Simpan Grup</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <label>Nama Grup</label>
                        <input type="hidden" class="form-control disable" name="id_group" disabled>
                        <input type="text" class="form-control disable" name="nm_group" disabled>
                    </div>
                    <div class="col-md-6">
                        <label>Kelas</label>
                        <select class="form-control" name="id_kelas" required>
                            <option value="">Pilih Kelas</option>
                            @foreach ($list_kelas as $kelas)
                                <option value="{{ $kelas->id_kelas }}">{{ $kelas->nm_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn bg-green" onclick="actionWhatsappGroup()">Save changes</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $.ajax({
            type: "POST",
            url: base_url + '/administrator/notification/whatsapp/group',
            success: function(response) {
                if (response.message) {
                    vex.dialog.alert(response.message);
                } else {
                    var primary_table = $('#primary_table').DataTable({
                        data: response.data,
                        columns: [{
                                data: null,
                                searchable: false,
                                orderable: false
                            },
                            {
                                data: '0'
                            },
                            {
                                data: '1'
                            },
                            {
                                data: '2'
                            },
                            {
                                data: null,
                                searchable: false,
                                orderable: false,
                                render: function(data, type, row) {
                                    if (data[2] !== 'TERSIMPAN') {
                                        html = `
                                        <button class="btn bg-green" data-group-id="${data[1]}" data-group-name="${data[0]}" onclick="showModal(this)"><i class="material-icons">add_box</i><span>Simpan</span></button>
                                        `;
                                    } else {
                                        html = `
                                        <button class="btn bg-red" data-mode="delete" data-group-id="${data[1]}" data-group-name="${data[0]}" onclick="actionWhatsappGroup(this)"><i class="material-icons">delete</i><span>Hapus</span></button>
                                        `;
                                    }

                                    return html;
                                }
                            }
                        ]
                    });

                    primary_table.on('draw', function() {
                        primary_table.column(0, {
                            search: 'applied',
                            order: 'applied'
                        }).nodes().each(function(cell, i) {
                            var start = this.page.info().page * this.page.info()
                                .length;
                            cell.innerHTML = start + i + 1;
                        });
                    }).draw();
                }
            },
        });
    });

    function showModal(el) {
        var id_group = $(el).data('group-id');
        var nm_group = $(el).data('group-name');

        $('input[name=id_group]').val(id_group);
        $('input[name=nm_group]').val(nm_group);

        $('#modal-action').modal('show');
    }

    function actionWhatsappGroup(el) {
        $('#modal-action').modal('hide');
        $('button').attr('disabled', 'disabled');

        var mode = $(el).data('mode') ?? 'add';

        $.ajax({
            type: 'POST',
            url: base_url + '/administrator/notification/whatsapp/group/' + mode,
            data: {
                id_kelas: $('select[name=id_kelas]').val(),
                id_group: mode == 'add' ? $('input[name=id_group]').val() : $(el).data('group-id'),
                nm_group: $('input[name=nm_group]').val(),
            },
            success: function(response) {
                if (response.status_code == 200) {
                    vex.dialog.alert(response.message);
                } else if (response.status_code == 201) {
                    vex.dialog.alert(response.message);
                    window.location.href = response.link;
                } else if (response.status_code == 202) {
                    vex.dialog.alert(response.message);
                    setTimeout(function() {
                        loadURI(response.path);
                    }, 2000);

                } else if (response.status_code == 203) {
                    vex.dialog.alert(response.message);
                    primary_table.ajax.reload(null, false);
                } else if (response.status_code == 204) {
                    loadURI(response.path);
                } else if (response.status_code == 300) {
                    vex.dialog.alert(response.message);
                }
            },
            complete: function() {
                $('button').removeAttr('disabled', 'disabled');
            }
        });
    }

    function actionUpdate() {
        $.ajax({
            type: 'POST',
            url: base_url + '/administrator/notification/whatsapp/setting/update',
            data: {
                attendance_mode: $('select[name=attendance_mode]').val(),
                attendance_template: $('textarea[name=attendance_template]').val(),
                attendance_time_schedule: $('input[name=attendance_time_schedule]').val(),
                payment_template: $('textarea[name=payment_template]').val(),
                payment_time_schedule: $('input[name=payment_time_schedule]').val(),
                day_schedule: $('input[name=day_schedule]').val(),
            },
            success: function(response) {
                if (response.status_code == 200) {
                    vex.dialog.alert(response.message);
                } else if (response.status_code == 201) {
                    vex.dialog.alert(response.message);
                    window.location.href = response.link;
                } else if (response.status_code == 202) {
                    vex.dialog.alert(response.message);
                    setTimeout(function() {
                        loadURI(response.path);
                    }, 2000);

                } else if (response.status_code == 203) {
                    vex.dialog.alert(response.message);
                    primary_table.ajax.reload(null, false);
                } else if (response.status_code == 204) {
                    loadURI(response.path);
                } else if (response.status_code == 300) {
                    vex.dialog.alert(response.message);
                }
            },
            complete: function() {
                $('button').removeAttr('disabled', 'disabled');
            }
        });
    }
</script>
