<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>DATA PELANGGARAN & TINDAKAN PELANGGARAN</h2>
                </div>
                <div class="body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#belum_tindakan_nonkbm" data-toggle="tab" aria-expanded="true">
                                <i class="material-icons">cancel_presentation</i> PELANGGARAN NON-KBM
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#belum_tindakan_kbm" data-toggle="tab">
                                <i class="material-icons">cancel_presentation</i> PELANGGARAN KBM
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#sudah_tindakan" data-toggle="tab">
                                <i class="material-icons">done_all</i> SUDAH TINDAKAN
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="belum_tindakan_nonkbm">
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable display"
                                        id="primary_table_belum_nonkbm">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Siswa</th>
                                                <th>Kelas</th>
                                                <th>Nama Guru Input</th>
                                                <th>Tingkat Pelanggaran</th>
                                                <th>Keterangan Sub-Kategori</th>
                                                <th>Catatan Pelanggaran</th>
                                                <th>Catatan Khusus</th>
                                                <th>Tanggal Pelanggaran</th>
                                                <th>Aktor Input Pelanggaran</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="belum_tindakan_kbm">
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable display"
                                        id="primary_table_belum_kbm" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Siswa</th>
                                                <th>Kelas</th>
                                                <th>Nama Guru Input</th>
                                                <th>Mapel</th>
                                                <th>Catatan Pelanggaran</th>
                                                <th>Tanggal Pelanggaran</th>
                                                <th>Aktor Input Pelanggaran</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="sudah_tindakan">
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable display"
                                        id="primary_table_sudah">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Siswa</th>
                                                <th>Kelas</th>
                                                <th>Nama Siswa Presensi</th>
                                                <th>Nama Guru Input</th>
                                                <th>Tingkat Pelanggaran</th>
                                                <th>Keterangan Sub-Kategori</th>
                                                <th>Catatan Pelanggaran</th>
                                                <th>Catatan Khusus</th>
                                                <th>Tanggal Pelanggaran</th>
                                                <th>Aktor Input Pelanggaran</th>
                                                <th>Nama Input Tindakan</th>
                                                <th>Catatan Tindakan</th>
                                                <th>Catatan Khusus</th>
                                                <th>Tanggal Tindakan</th>
                                                <th>Aktor Input Tindakan</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var modul_url = 'penanganan-siswa';
    var datatable_url_belum_nonkbm = base_url + '/' + role_url + '/' + modul_url + '/' +
        'tindakan-pelanggaran/datatables-belum-nonkbm';
    var datatable_url_belum_kbm = base_url + '/' + role_url + '/' + modul_url + '/' +
        'tindakan-pelanggaran/datatables-belum-kbm';
    var datatable_url_sudah = base_url + '/' + role_url + '/' + modul_url + '/' +
        'tindakan-pelanggaran/datatables-sudah';
    var add_url_nonkbm = role_url + '#' + modul_url + '/' + 'tindakan-pelanggaran/add-nonkbm';
    var add_url_kbm = role_url + '#' + modul_url + '/' + 'tindakan-pelanggaran/add-kbm';
    var edit_url = role_url + '#' + modul_url + '/' + 'tindakan-pelanggaran/edit';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-tindakan-pelanggaran/delete';

    var primary_table_belum_nonkbm = $('#primary_table_belum_nonkbm').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: datatable_url_belum_nonkbm,
            type: 'GET'
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'nm_siswa',
                name: 'pengguna.nm_pengguna'
            },
            {
                data: 'nm_kelas',
                name: 'kelas.nm_kelas'
            },
            {
                data: 'nm_input',
                name: 'nm_input',
                searchable: false,
                orderable: false
            },
            {
                data: 'tingkat_pelanggaran',
                name: 'tingkat_pelanggaran',
                searchable: false,
                orderable: false
            },
            {
                data: 'keterangan_subkategori_pelanggaran',
                name: 'subkategori_pelanggaran.keterangan_subkategori_pelanggaran'
            },
            {
                data: 'catatan_pelanggaran',
                name: 'pelanggaran_siswa.catatan_pelanggaran'
            },
            {
                data: 'catatan_pelanggaran_khusus',
                name: 'catatan_pelanggaran_khusus',
                searchable: false,
                orderable: false
            },
            {
                data: 'tgl_pelanggaran',
                name: 'pelanggaran_siswa.tgl_pelanggaran'
            },
            {
                data: 'aktor_input_pelanggaran',
                name: 'aktor_input_pelanggaran',
                searchable: false,
                orderable: false
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                        add_url_nonkbm + '/' + data.id + '">' +
                        '    <i class="material-icons">done_all</i>' +
                        '</a>';
                }
            }
        ]
    });

    primary_table_belum_nonkbm.on('draw', function() {
        primary_table_belum_nonkbm.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        });
    }).draw();

    var primary_table_belum_kbm = $('#primary_table_belum_kbm').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: datatable_url_belum_kbm,
            type: 'GET'
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'nm_siswa',
                name: 'pengguna.nm_pengguna'
            },
            {
                data: 'nm_kelas',
                name: 'kelas.nm_kelas'
            },
            {
                data: 'nm_input',
                name: 'nm_input',
                searchable: false,
                orderable: false
            },
            {
                data: 'nm_mapel',
                name: 'mata_pelajaran.nm_mata_pelajaran'
            },
            {
                data: 'catatan_pelanggaran',
                name: 'presensi_mp_pelanggaran.catatan_pelanggaran'
            },
            {
                data: 'tgl_pelanggaran',
                name: 'presensi_mp_pelanggaran.created_at'
            },
            {
                data: 'aktor_input_pelanggaran',
                name: 'aktor_input_pelanggaran',
                searchable: false,
                orderable: false
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                        add_url_kbm + '/' + data.id + '">' +
                        '    <i class="material-icons">done_all</i>' +
                        '</a>';
                }
            }
        ]
    });

    primary_table_belum_kbm.on('draw', function() {
        primary_table_belum_kbm.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        });
    }).draw();

    var primary_table_sudah = $('#primary_table_sudah').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: datatable_url_sudah,
            type: 'GET'
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'nm_siswa',
                name: 'pengguna.nm_pengguna'
            },
            {
                data: 'nm_kelas',
                name: 'kelas.nm_kelas'
            },
            {
                data: 'nm_siswa_presensi',
                name: 'p_siswa_presensi.nm_pengguna'
            },
            {
                data: 'nm_input',
                name: 'nm_input',
                searchable: false,
                orderable: false
            },
            {
                data: 'tingkat_pelanggaran',
                name: 'tingkat_pelanggaran',
                searchable: false,
                orderable: false
            },
            {
                data: 'keterangan_subkategori_pelanggaran',
                name: 'subkategori_pelanggaran.keterangan_subkategori_pelanggaran'
            },
            {
                data: 'catatan_pelanggaran',
                name: 'catatan_pelanggaran',
                searchable: false,
                orderable: false
            },
            {
                data: 'catatan_pelanggaran_khusus',
                name: 'catatan_pelanggaran_khusus',
                searchable: false,
                orderable: false
            },
            {
                data: 'tgl_pelanggaran',
                name: 'tgl_pelanggaran',
                searchable: false,
                orderable: false
            },
            {
                data: 'aktor_input_pelanggaran',
                name: 'aktor_input_pelanggaran',
                searchable: false,
                orderable: false
            },
            {
                data: 'nm_input_tindakan',
                name: 'p_tindakan.nm_pengguna'
            },
            {
                data: 'catatan_tindakan_pelanggaran',
                name: 'tindakan_pelanggaran.catatan_tindakan_pelanggaran'
            },
            {
                data: 'catatan_tindakan_pelanggaran_khusus',
                name: 'catatan_tindakan_pelanggaran_khusus',
                searchable: false,
                orderable: false
            },
            {
                data: 'tgl_tindakan_pelanggaran',
                name: 'tindakan_pelanggaran.tgl_tindakan_pelanggaran'
            },
            {
                data: 'aktor_input_tindakan_pelanggaran',
                name: 'aktor_input_tindakan_pelanggaran',
                searchable: false,
                orderable: false
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                  render: function(data) {
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                        edit_url + '/' + data.id + '">' +
                        '    <i class="material-icons">edit</i>' +
                        '</a> ';
                  }
                // render: function(data) {
                //     return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                //         edit_url + '/' + data.id + '">' +
                //         '    <i class="material-icons">edit</i>' +
                //         '</a> ' +
                //         '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteActionTindakan(\'' +
                //         delete_url + '\', this)" data-id="' + data.id + '">' +
                //         '    <i class="material-icons">delete_forever</i>' +
                //         '</button>';
                // }
            }
        ]
    });

    primary_table_sudah.on('draw', function() {
        primary_table_sudah.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        });
    }).draw();

    function deleteActionTindakan(delete_url, element) {
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
                        } else if (response.status == 201) {
                            vex.dialog.alert(response.message);
                            window.location.href = response.link;
                        } else if (response.status == 202) {
                            vex.dialog.alert(response.message);
                            loadURI(response.path);
                        } else if (response.status == 203) {
                            vex.dialog.alert(response.message);
                            primary_table_belum_nonkbm.ajax.reload(null, false);
                            primary_table_belum_kbm.ajax.reload(null, false);
                            primary_table_sudah.ajax.reload(null, false);
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
