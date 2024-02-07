<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-blue">
                    <h2>
                        Data Siswa Asrama
                    </h2>
                </div>
                <div class="body">
                    <ul class="nav nav-tabs tab-nav-right" role="tablist">
                        <li role="presentation" class="active"><a href="#selected" data-toggle="tab"
                                class="col-green">List Siswa Asrama</a></li>
                        <li role="presentation"><a href="#not" data-toggle="tab" class="col-pink">List Siswa Tanpa
                                Asrama</a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="selected">
                            <a type="button" class="btn btn-success" style="margin-bottom: 15px"
                                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/data-siswa-asrama/add-ruangan') }}">
                                <i class="material-icons">add_box</i>
                                <span>Tambah Ruangan Siswa Asrama</span>
                            </a>
                            <div class="table-responsive">
                                <table id="primary_table"
                                    class="table table-bordered table-striped table-hover dataTable"
                                    style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nis</th>
                                            <th>Kelas</th>
                                            <th>Nama</th>
                                            <th>Ruangan</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade " id="not">
                            <div class="table-responsive">
                                <table id="secondary_table"
                                    class="table table-bordered table-striped table-hover dataTable"
                                    style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nis</th>
                                            <th>Kelas</th>
                                            <th>Nama</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var modul_url = '{{ Request::segment(2) }}';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/data-siswa-asrama/datatables/asrama';
    var datatable2_url = base_url + '/' + role_url + '/' + modul_url + '/data-siswa-asrama/datatables/nonasrama';
    var add_url = base_url + '/' + role_url + '/' + modul_url + '/data-siswa-asrama/add';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/data-siswa-asrama/delete';
    // var detail_url = role_url + '#' + modul_url + '/' + 'paket-soal/bank-soal';
    // var delete_url2 = role_url + '/' + modul_url + '/' + 'paket-soal/bank-soal';

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="token"]').attr('content')
        }
    });


    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
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
                data: 'nis_siswa'
            },
            {
                data: 'kelas.nm_kelas'
            },
            {
                data: 'pengguna.nm_pengguna'
            },
            {
                data: 'ruangan'
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<button type="button" class="btn btn-danger btn-circle waves-effect waves-circle waves-float" data-id="' +
                        data.id_siswa + '" onclick="actionDelete(this)">' +
                        '    <i class="material-icons">delete</i>' +
                        '</button>';
                }
            }
        ]
    });

    primary_table.on('draw', function() {
        primary_table.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = i + 1;
        });
    }).draw();

    var secondary_table = $('#secondary_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: datatable2_url,
            type: 'GET'
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'nis_siswa'
            },
            {
                data: 'kelas.nm_kelas'
            },
            {
                data: 'pengguna.nm_pengguna'
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<button type="button" class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-id="' +
                        data.id_siswa + '" onclick="actionAdd(this)">' +
                        '    <i class="material-icons">library_add</i>' +
                        '</button>';
                }
            }
            // {
            //     data: 'action',
            //     name: 'action',
            //     searchable: false,
            //     orderable: false,
            //     render: function(data) {
            //         return '<a type="button" class="btn btn-success btn-circle waves-effect waves-circle waves-float" target="_blank" href="' +
            //             detail_url + '/edit/' + data.id_siswa + '">' +
            //             '    <i class="material-icons">mode_edit</i>' +
            //             '</a>' +
            //             '<button type="button" class="btn btn-warning btn-circle waves-effect waves-circle waves-float" data-id="' +
            //             data.id + '" onclick="actionDelete(this)">' +
            //             '    <i class="material-icons">delete</i>' +
            //             '</button>';
            //     }
            // }
        ],
        // order: [
        //     [3, 'asc']
        // ],
    });

    secondary_table.on('draw', function() {
        secondary_table.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = i + 1;
        });
    }).draw();

    function actionAdd(element) {
        var item = $(element);
        item.prop('disabled', true);
        var url = add_url;
        if (item.is(":disabled")) {
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    id_siswa: item.attr('data-id')
                },
                success: function(result) {
                    secondary_table.ajax.reload(null, false);
                    primary_table.ajax.reload(null, false);

                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }
    }

    function actionDelete(element) {
        var item = $(element);
        item.prop('disabled', true);
        var url = delete_url;
        if (item.is(":disabled")) {
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    id_siswa: item.attr('data-id')
                },
                success: function(result) {
                    primary_table.ajax.reload(null, false);
                    secondary_table.ajax.reload(null, false);
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }
    }
</script>
