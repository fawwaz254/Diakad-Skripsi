<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#penanganan-siswa/input-pelanggaran/add') }}"><i
                    class="material-icons">note_add</i><span>Tambah Pelanggaran Siswa</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>DATA PELANGGARAN SISWA</h2>
                </div>
                <div class="container" style="margin-top: 3rem">
                    <div class="row">
                        <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                            <h4 class="card-inside-title">Pilih Tanggal</h4>
                            <input type="date" class="form-control" id="filter_tanggal" name="tanggal"
                                aria-required="true" aria-invalid="true">
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                            <h4 class="card-inside-title">Status Tampil Data Siswa</h4>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="aktif" name="status_siswa"
                                    value="1" checked>
                                <label class="form-check-label" for="aktif">Siswa Aktif</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="all" name="status_siswa"
                                    value="0">
                                <label class="form-check-label" for="all">Semua Siswa</label>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-12 col-xs-12" style="margin-top: 3rem">
                            <button class="btn btn-block btn-danger waves-effect align-items-start" type="button"
                                onclick="filterAction()">
                                <i class="material-icons">save</i> Tampilkan
                            </button>
                        </div>
                    </div>
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
                                                <th>NIS</th>
                                                <th>Nama Siswa</th>
                                                <th>Kelas</th>
                                                <th>Sub Kategori</th>
                                                <th>Tanggal Pelanggaran</th>
                                                <th>Nama Guru Input</th>
                                                <th>Action</th>
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
                                        id="primary_table_belum_kbm" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>NIS</th>
                                                <th>Nama Siswa</th>
                                                <th>Kelas</th>
                                                <th>Mapel</th>
                                                <th>Sub Kategori</th>
                                                <th>Tanggal Pelanggaran</th>
                                                <th>Nama Guru Input</th>
                                                <th>Action</th>
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
                                        id="primary_table_sudah" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>NIS</th>
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
    var current_url = window.location.href;
    var param_status_siswa = current_url.split('/')[5] ?? '1';
    var param_tanggal = current_url.split('/')[6] ?? '0';

    if (param_tanggal !== '0') {
        $('#filter_tanggal').val(param_tanggal)
    }

    // var modul_url = location.hash.replace('#','').split('/')[0];
    var modul_url = 'penanganan-siswa';
    var datatable_url_belum_nonkbm = base_url + '/' + role_url + '/' + modul_url + '/' +
        'tindakan-pelanggaran/datatables-belum-nonkbm/' + param_status_siswa + '/' + param_tanggal;
    var datatable_url_belum_kbm = base_url + '/' + role_url + '/' + modul_url + '/' +
        'tindakan-pelanggaran/datatables-belum-kbm/' + param_status_siswa + '/' + param_tanggal;
    var datatable_url_sudah = base_url + '/' + role_url + '/' + modul_url + '/' +
        'tindakan-pelanggaran/datatables-sudah/' + param_status_siswa + '/' + param_tanggal;
    var add_url_nonkbm = role_url + '#' + modul_url + '/' + 'tindakan-pelanggaran/add-nonkbm';
    var add_url_kbm = role_url + '#' + modul_url + '/' + 'tindakan-pelanggaran/add-kbm';
    var edit_url = role_url + '#' + modul_url + '/' + 'tindakan-pelanggaran/edit';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-tindakan-pelanggaran/delete';
    var delete_nonkbm_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-tindakan-pelanggaran-nonkbm';

    var primary_table_belum_nonkbm = $('#primary_table_belum_nonkbm').DataTable({
        processing: true,
        serverSide: true,
        // "bFilter": false,
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
                data: 'nis_siswa',
                name: 'siswa.nis_siswa',
                // searchable: false,
                orderable: false
            },
            {
                data: 'nm_siswa',
                name: 'pengguna.nm_pengguna',
                // searchable: false,
                orderable: false
            },
            {
                data: 'nm_kelas',
                name: 'kelas.nm_kelas',
                // searchable: false,
                orderable: false
            },
            {
                data: 'nm_subkategori_pelanggaran',
                name: 'nm_subkategori_pelanggaran',
                searchable: false,
                orderable: false
            },
            {
                data: 'tgl_pelanggaran',
                name: 'pelanggaran_siswa.tgl_pelanggaran',
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
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                        add_url_nonkbm + '/' + data.id + '">' +
                        '    <i class="material-icons">done_all</i>' +
                        '</a>' +
                        '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteActionTindakan(\'' +
                        delete_nonkbm_url + '\', this)" data-id="' + data.id + '">' +
                        '    <i class="material-icons">delete_forever</i>' +
                        '</button>';
                }
            }
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
        // "bFilter": false,
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
                data: 'nis_siswa',
                name: 'siswa.nis_siswa',
                // searchable: false,
                orderable: false
            },
            {
                data: 'nm_siswa',
                name: 'pengguna.nm_pengguna',
                // searchable: false,
                orderable: false
            },
            {
                data: 'nm_kelas',
                name: 'kelas.nm_kelas',
                // searchable: false,
                orderable: false
            },
            {
                data: 'nm_mapel',
                name: 'mata_pelajaran.nm_mata_pelajaran',
                // searchable: false,
                orderable: false,
            },
            {
                data: 'nm_subkategori_pelanggaran',
                name: 'nm_subkategori_pelanggaran',
                searchable: false,
                orderable: false,
            },
            {
                data: 'tgl_pelanggaran',
                name: 'presensi_mp_pelanggaran.created_at',
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
        // "bFilter": false,
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
                data: 'nis_siswa',
                name: 'siswa.nis_siswa',
                // searchable: false,
                orderable: false
            },
            {
                data: 'nm_siswa',
                // searchable: false,
                orderable: false
            },
            {
                data: 'nm_kelas',
                // searchable: false,
                orderable: false
            },
            {
                data: 'nm_subkategori_pelanggaran',
                name: 'nm_subkategori_pelanggaran',
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
                data: 'nm_input',
                name: 'nm_input',
                searchable: false,
                orderable: false
            },
            {
                data: 'nm_input_tindakan',
                name: 'p_tindakan.nm_pengguna',
                searchable: false,
                orderable: false
            },
            // { data: 'catatan_tindakan_pelanggaran', name: 'tindakan_pelanggaran.catatan_tindakan_pelanggaran' },
            // { data: 'catatan_tindakan_pelanggaran_khusus', name: 'catatan_tindakan_pelanggaran_khusus', searchable: false, orderable: false },

            // { data: 'tgl_tindakan_pelanggaran', name: 'tindakan_pelanggaran.tgl_tindakan_pelanggaran' },
            // { data: 'aktor_input_tindakan_pelanggaran', name: 'aktor_input_tindakan_pelanggaran', searchable: false, orderable: false },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                        edit_url + '/' + data.id + '">' +
                        '    <i class="material-icons">edit</i>' +
                        '</a> ' +
                        '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteActionTindakan(\'' +
                        delete_url + '\', this)" data-id="' + data.id + '">' +
                        '    <i class="material-icons">delete_forever</i>' +
                        '</button>';
                }
            }
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

    function filterAction() {
        var tanggal = $('#filter_tanggal').val();
        var status_siswa = $('input[name="status_siswa"]:checked').val();

        console.log(status_siswa);

        if (tanggal == '') {
            tanggal = '0';
        }
        window.location.href = "bimbingan-konseling#penanganan-siswa/tindakan-pelanggaran/" + status_siswa + "/" +
            tanggal;
    }
</script>
