<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>DATA BIAYA SISWA</h2>
                </div>
                <div class="body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#belum" data-toggle="tab" aria-expanded="true">
                                <i class="material-icons">cancel_presentation</i> BELUM SET BIAYA
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#sudah" data-toggle="tab">
                                <i class="material-icons">done_all</i> SUDAH SET BIAYA
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="belum" style="width: 100%">
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable display"
                                        id="primary_table_belum" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>NIS Siswa</th>
                                                <th>NISN Siswa</th>
                                                <th>Nama Siswa</th>
                                                <th>Kelas</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="sudah">
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable display"
                                        id="primary_table_sudah" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>NIS Siswa</th>
                                                <th>NISN Siswa</th>
                                                <th>Nama Siswa</th>
                                                <th>Kelas</th>
                                                <th>Kelompok Biaya</th>
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
    var modul_url = 'utility';
    var datatable_url_belum = base_url + '/' + role_url + '/' + modul_url + '/' + 'biaya-siswa/datatables-belum';
    var datatable_url_sudah = base_url + '/' + role_url + '/' + modul_url + '/' + 'biaya-siswa/datatables-sudah';
    var set_url = role_url + '#' + modul_url + '/' + 'biaya-siswa/set';
    var edit_url = role_url + '#' + modul_url + '/' + 'biaya-siswa/edit';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-biaya-siswa/delete';

    var primary_table_belum = $('#primary_table_belum').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        ajax: {
            url: datatable_url_belum,
            type: 'GET'
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'nis_siswa',
                name: 'siswa.nis_siswa'
            },
            {
                data: 'nisn_siswa',
                name: 'siswa.nisn_siswa'
            },
            {
                data: 'nm_pengguna',
                name: 'pengguna.nm_pengguna'
            },
            {
                data: 'nm_kelas',
                name: 'kelas.nm_kelas'
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                        set_url + '/' + data.id + '">' +
                        '    <i class="material-icons">done_all</i>' +
                        '</a>';
                }
            }
        ]
    });

    primary_table_belum.on('draw', function() {
        primary_table_belum.column(0, {
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
        responsive: false,
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
                data: 'nis_siswa',
                name: 'siswa.nis_siswa'
            },
            {
                data: 'nisn_siswa',
                name: 'siswa.nisn_siswa'
            },
            {
                data: 'nm_pengguna',
                name: 'pengguna.nm_pengguna'
            },
            {
                data: 'nm_kelas',
                name: 'kelas.nm_kelas'
            },
            {
                data: 'kelompok_biaya',
                name: 'kelompok_biaya.nm_kelompok_biaya'
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return `<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="${edit_url}/${data.id}">
                                <i class="material-icons">edit</i>
                            </a>
                            <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteActionBiaya(\'${delete_url}\', this)" data-id="${data.id}">
                                <i class="material-icons">delete_forever</i>
                            </button>`;
                }
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

    var companies2 = $('#primary_table_sudah');
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        if (e.target.hash == '#sudah') {
            companies2.columns.adjust().draw()
        }
    });


    function deleteActionBiaya(delete_url, element) {
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
                            primary_table_belum.ajax.reload(null, false);
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
