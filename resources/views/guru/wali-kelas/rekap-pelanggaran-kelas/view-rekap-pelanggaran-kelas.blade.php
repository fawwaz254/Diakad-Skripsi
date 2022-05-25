<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>DATA PELANGGARAN SISWA</h2>
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
                                <i class="material-icons">done_all</i> SUDAH DITINDAK
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="belum_tindakan_nonkbm">
                            <div class="body">
                                <div class="table-responsive" style="overflow-x: auto;width: 100%;">
                                    <table class="table table-bordered table-striped table-hover dataTable display"
                                        id="primary_table_belum_nonkbm">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Siswa</th>
                                                <th>Kelas</th>
                                                <th>Sub Kategori</th>
                                                <th>Tanggal Pelanggaran</th>
                                                <th>Nama Guru Input</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="belum_tindakan_kbm">
                            <div class="body">
                                <div class="table-responsive" style="overflow-x: auto;width: 100%;">
                                    <table class="table table-bordered table-striped table-hover dataTable display"
                                        id="primary_table_belum_kbm">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Siswa</th>
                                                <th>Kelas</th>
                                                <th>Mapel</th>
                                                <th>Sub Kategori</th>
                                                <th>Tanggal Pelanggaran</th>
                                                <th>Nama Guru Input</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="sudah_tindakan">
                            <div class="body">
                                <div class="table-responsive" style="overflow-x: auto;width: 100%;">
                                    <table class="table table-bordered table-striped table-hover dataTable display"
                                        id="primary_table_sudah">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Siswa</th>
                                                <th>Kelas</th>
                                                <th>Sub Kategori</th>
                                                <th>Tanggal Pelanggaran</th>
                                                <th>Nama Guru Input</th>
                                                <th>Nama Input Tindakan</th>
                                                <!-- <th>Catatan Tindakan</th>
                                                <th>Catatan Khusus</th> -->

                                                <!--    <th>Tanggal Tindakan</th>
                                                <th>Aktor Input Tindakan</th> -->
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
    var modul_url = 'wali-kelas';
    var datatable_url_belum_nonkbm = base_url + '/' + role_url + '/' + modul_url + '/' +
        'rekap-pelanggaran-kelas/datatables-belum-nonkbm';
    var datatable_url_belum_kbm = base_url + '/' + role_url + '/' + modul_url + '/' +
        'rekap-pelanggaran-kelas/datatables-belum-kbm';
    var datatable_url_sudah = base_url + '/' + role_url + '/' + modul_url + '/' +
        'rekap-pelanggaran-kelas/datatables-sudah';
    var add_url_nonkbm = role_url + '#' + modul_url + '/' + 'rekap-pelanggaran-kelas/add-nonkbm';
    var add_url_kbm = role_url + '#' + modul_url + '/' + 'rekap-pelanggaran-kelas/add-kbm';
    var edit_url = role_url + '#' + modul_url + '/' + 'rekap-pelanggaran-kelas/edit';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-tindakan-pelanggaran/delete';
    var delete_nonkbm_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-tindakan-pelanggaran-nonkbm';
    console.log(datatable_url_belum_kbm);
    var primary_table_belum_nonkbm = $('#primary_table_belum_nonkbm').DataTable({
        processing: true,
        serverSide: true,
        dom: 'Bfrtip',
        lengthMenu: dtLengButton,
        buttons: dtButtonConfig,
        ajax: {
            url: datatable_url_belum_nonkbm,
            type: 'GET'
        },
        columns: [{
                data: 'index_table',
                defaultContent: '',
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
                data: 'nm_subkategori_pelanggaran',
                name: 'nm_subkategori_pelanggaran'
            },
            {
                data: 'tgl_pelanggaran',
                name: 'pelanggaran_siswa.tgl_pelanggaran'
            },
            {
                data: 'nm_input',
                name: 'nm_input',
                searchable: false,
                orderable: false
            },
        ],
        createdRow: function(row, data, dataIndex) {
            if (data.cek_pj_bk) {
                $(row).css('background-color', 'hsl(28, 80%, 61%)');
            }
        }
    });

    primary_table_belum_nonkbm.on('draw', function() {
        primary_table_belum_nonkbm.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
            primary_table_belum_nonkbm.cell(cell).invalidate('dom');
        });
    }).draw();

    var primary_table_belum_kbm = $('#primary_table_belum_kbm').DataTable({
        processing: true,
        serverSide: true,
        dom: 'Bfrtip',
        lengthMenu: dtLengButton,
        buttons: dtButtonConfig,
        ajax: {
            url: datatable_url_belum_kbm,
            type: 'GET'
        },
        columns: [{
                data: 'index_table',
                defaultContent: '',
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
                data: 'nm_mapel',
                name: 'mata_pelajaran.nm_mata_pelajaran'
            },
            {
                data: 'nm_subkategori_pelanggaran',
                name: 'nm_subkategori_pelanggaran'
            },
            {
                data: 'tgl_pelanggaran',
                name: 'presensi_mp_pelanggaran.created_at'
            },
            {
                data: 'nm_input',
                name: 'nm_input',
                searchable: false,
                orderable: false
            },
        ],
        createdRow: function(row, data, dataIndex) {
            if (data.cek_pj_bk) {
                $(row).css('background-color', 'hsl(28, 80%, 61%)');
            }
        }
    });

    primary_table_belum_kbm.on('draw', function() {
        primary_table_belum_kbm.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
            primary_table_belum_kbm.cell(cell).invalidate('dom');
        });
    }).draw();

    var primary_table_sudah = $('#primary_table_sudah').DataTable({
        processing: true,
        serverSide: true,
        dom: 'Bfrtip',
        lengthMenu: dtLengButton,
        buttons: dtButtonConfig,
        ajax: {
            url: datatable_url_sudah,
            type: 'GET'
        },
        columns: [{
                data: 'index_table',
                defaultContent: '',
                searchable: false,
                orderable: false
            },
            {
                data: 'nm_siswa',
                searchable: false,
                orderable: false
            },
            {
                data: 'nm_kelas',
                searchable: false,
                orderable: false
            },
            {
                data: 'nm_subkategori_pelanggaran',
                name: 'nm_subkategori_pelanggaran'
            },
            {
                data: 'tgl_pelanggaran',
                name: 'tgl_pelanggaran',
                searchable: false,
                orderable: false
            },
            {
                data: 'nm_input',
                name: 'nm_input',
                searchable: false,
                orderable: false
            },
            {
                data: 'nm_input_tindakan',
                name: 'p_tindakan.nm_pengguna'
            },
        ],
        createdRow: function(row, data, dataIndex) {
            if (data.cek_pj_bk) {
                $(row).css('background-color', 'hsl(28, 80%, 61%)');
            }
        }
    });

    primary_table_sudah.on('draw', function() {
        primary_table_sudah.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
            primary_table_sudah.cell(cell).invalidate('dom');
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
