<div class="container-fluid">
    <div class="block-header">
        <h2><a type="button" class="btn bg-grey waves-effect"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/paket-soal') }}">
                <i class="material-icons">keyboard_backspace</i>
                <span>Kembali</span>
            </a>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-pink">
                    <h2>
                        Pilih Soal {{ $question_package->text }}
                    </h2>
                </div>
                <div class="body">
                    <ul class="nav nav-tabs tab-nav-right" role="tablist">
                        <li role="presentation" class="active"><a href="#selected" data-toggle="tab"
                                class="col-green">Soal Sudah
                                Dipilih</a></li>
                        <li role="presentation"><a href="#not" data-toggle="tab" class="col-pink">Bank Soal Kategori
                                Ini</a>
                        </li>
                        <li role="presentation"><a href="#order" data-toggle="tab" class="col-pink">Bank Soal Kategori
                                Lain</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="selected">
                            <a type="button" class="btn btn-success" style="margin-bottom: 15px"
                                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/paket-soal/input-soal/new/pilihan-ganda/' . $question_package->id_paket_soal) }}">
                                <i class="material-icons">add_box</i>
                                <span>Type Pilihan Ganda</span>
                            </a>
                            <a type="button" class="btn btn-success" style="margin-bottom: 15px"
                                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/paket-soal/input-soal/new/pilihan-ganda-batch/' . $question_package->id_paket_soal) }}">
                                <i class="material-icons">add_box</i>
                                <span>Type Pilihan Ganda Batch</span>
                            </a>
                            <a type="button" class="btn btn-success" style="margin-bottom: 15px"
                                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/paket-soal/input-soal/new/essay/' . $question_package->id_paket_soal) }}">
                                <i class="material-icons">add_box</i>
                                <span>Type Essay</span>
                            </a>
                            <a type="button" class="btn btn-success" style="margin-bottom: 15px"
                                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/paket-soal/input-soal/new/submit/' . $question_package->id_paket_soal) }}">
                                <i class="material-icons">add_box</i>
                                <span>Type File</span>
                            </a>
                            <a type="button" class="btn btn-success" style="margin-bottom: 15px"
                                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/paket-soal/input-soal/new/pilihan-ganda-kompleks/' . $question_package->id_paket_soal) }}">
                                <i class="material-icons">add_box</i>
                                <span>Type Pilihan Ganda Kompleks</span>
                            </a>
                            <a type="button" class="btn btn-success" style="margin-bottom: 15px"
                                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/paket-soal/input-soal/new/simple-essay/' . $question_package->id_paket_soal) }}">
                                <i class="material-icons">add_box</i>
                                <span>Type Isian Singkat</span>
                            </a>
                            <a type="button" class="btn btn-success" style="margin-bottom: 15px"
                                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/paket-soal/input-soal/new/match/' . $question_package->id_paket_soal) }}">
                                <i class="material-icons">add_box</i>
                                <span>Type Penjodohan</span>
                            </a>
                            <a type="button" class="btn btn-success" style="margin-bottom: 15px"
                                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/paket-soal/input-soal/new/true-false/' . $question_package->id_paket_soal) }}">
                                <i class="material-icons">add_box</i>
                                <span>Type True/False</span>
                            </a>
                            <div class="table-responsive">
                                <table id="secondary_table"
                                    class="table table-bordered table-striped table-hover dataTable"
                                    style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tipe Soal</th>
                                            <th>Mapel</th>
                                            <th>Soal</th>
                                            <th>Pembuat</th>
                                            <th>Kelas</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade " id="not">
                            <button class="btn btn-success" id="addAll" style="margin-bottom: 15px"><i
                                    class="material-icons">add_box</i> <span>Tambah
                                    Semua Soal</span></button>
                            <br>
                            <div class="table-responsive">
                                <table id="primary_table"
                                    class="table table-bordered table-striped table-hover dataTable"
                                    style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tipe Soal</th>
                                            <th>Mapel</th>
                                            <th>Soal</th>
                                            <th>Pembuat</th>
                                            <th>Kelas</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="order">
                            <div class="table-responsive">
                                <table id="primary_table2"
                                    class="table table-bordered table-striped table-hover dataTable"
                                    style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tipe Soal</th>
                                            <th>Mapel</th>
                                            <th>Soal</th>
                                            <th>Pembuat</th>
                                            <th>Kelas</th>
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
    var test = "{{ $question_package->id_paket_soal }}";
    var modul_url = '{{ Request::segment(2) }}';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'paket-soal/detail/table/' + test + '/1';
    var datatable2_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'paket-soal/detail/table/' + test + '/2';
    var datatableorder_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'paket-soal/detail/table/' + test +
        '/0';
    var add_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'paket-soal/detail/add';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'paket-soal/detail/delete';
    var detail_url = role_url + '#' + modul_url + '/' + 'paket-soal/bank-soal';
    var delete_url2 = role_url + '/' + modul_url + '/' + 'paket-soal/bank-soal';

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="token"]').attr('content')
        }
    });

    var secondary_table = $('#secondary_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: datatable2_url,
            type: 'POST'
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'tipe_soal'
            },
            {
                data: 'kategori_soal.nm_kategori_soal'
            },
            {
                data: 'gambar',
                name: 'text',
                render: function(data) {
                    if (data.gambar) {
                        return '<img width="75" src=' + data.text + '>';
                    } else {
                        return data.text;
                    }

                }
            },
            {
                data: 'pengguna.nm_pengguna'
            },
            {
                data: 'kelas',
                orderable: false,
                render: function(data) {
                    let html = '';
                    data.forEach(element => {
                        html += '- ' +
                            element + ` <br>`;
                    });
                    return html;

                }
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    // if (data.edit) {
                    //     return '<a type="button" class="btn btn-success btn-circle waves-effect waves-circle waves-float" href="' +
                    //         detail_url + '/edit/' + data.id + '">' +
                    //         '    <i class="material-icons">mode_edit</i>' +
                    //         '</a>' +
                    //         '<a type="button" class="btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                    //         detail_url + '/test/' + data.id + '">' +
                    //         '    T' +
                    //         '</a>' +
                    //         '<button type="button" class="btn btn-danger btn-circle waves-effect waves-circle waves-float" data-id="' +
                    //         data.id + '" onclick="actionDelete2(this)">' +
                    //         '    <i class="material-icons">delete_forever</i>' +
                    //         '</button>' +
                    //         '<button type="button" class="btn btn-warning btn-circle waves-effect waves-circle waves-float" data-id="' +
                    //         data.id + '" onclick="actionDelete(this)">' +
                    //         '    <i class="material-icons">delete</i>' +
                    //         '</button>';
                    // } else {
                    return '<a type="button" class="btn btn-success btn-circle waves-effect waves-circle waves-float" target="_blank" href="' +
                        detail_url + '/edit/' + data.id + '">' +
                        '    <i class="material-icons">mode_edit</i>' +
                        '</a>' +
                        '<button type="button" class="btn btn-warning btn-circle waves-effect waves-circle waves-float" data-id="' +
                        data.id + '" onclick="actionDelete(this)">' +
                        '    <i class="material-icons">delete</i>' +
                        '</button>';
                    // }
                }
            }
        ],
        order: [
            [3, 'asc']
        ],
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

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: datatable_url,
            type: 'POST'
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'tipe_soal'
            },
            {
                data: 'kategori_soal.nm_kategori_soal'
            },
            {
                data: 'gambar',
                name: 'text',
                orderable: false,
                render: function(data) {
                    if (data.gambar) {
                        return '<img width="75" src=' + data.text + '>';
                    } else {
                        return data.text;
                    }

                }
            },
            {
                data: 'pengguna.nm_pengguna'
            },
            {
                data: 'kelas',
                orderable: false,
                render: function(data) {
                    let html = '';
                    data.forEach(element => {
                        html += '- ' +
                            element + ` <br>`;
                    });
                    return html;

                }
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<button type="button" class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-id="' +
                        data.id + '" onclick="actionAdd(this)">' +
                        '    <i class="material-icons">library_add</i>' +
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

    var primary_table2 = $('#primary_table2').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: datatableorder_url,
            type: 'POST'
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'tipe_soal'
            },
            {
                data: 'kategori_soal.nm_kategori_soal'
            },
            {
                data: 'gambar',
                name: 'text',
                orderable: false,
                render: function(data) {
                    if (data.gambar) {
                        return '<img width="75" src=' + data.text + '>';
                    } else {
                        return data.text;
                    }

                }
            },
            {
                data: 'pengguna.nm_pengguna'
            },
            {
                data: 'kelas',
                orderable: false,
                render: function(data) {
                    let html = '';
                    data.forEach(element => {
                        html += '- ' +
                            element + ` <br>`;
                    });
                    return html;

                }
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<button type="button" class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-id="' +
                        data.id + '" onclick="actionAdd(this)">' +
                        '    <i class="material-icons">library_add</i>' +
                        '</button>';
                }
            }
        ]
    });

    primary_table2.on('draw', function() {
        primary_table2.column(0, {
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
                    id_paket_soal: '{{ $question_package->id_paket_soal }}',
                    id_soal: item.attr('data-id')
                },
                success: function(result) {
                    primary_table.ajax.reload(null, false);
                    primary_table2.ajax.reload(null, false);
                    secondary_table.ajax.reload(null, false);
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
                    id_paket_soal: '{{ $question_package->id_paket_soal }}',
                    id_soal: item.attr('data-id')
                },
                success: function(result) {
                    primary_table.ajax.reload(null, false);
                    primary_table2.ajax.reload(null, false);
                    secondary_table.ajax.reload(null, false);
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }
    }

    $('#addAll').click(function() {
        var button = $(this);
        button.prop('disabled', true);
        // item.prop('disabled', true);
        var url = add_url;
        if (button.is(":disabled")) {
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    id_paket_soal: '{{ $question_package->id_paket_soal }}',
                    id_soal: '0'
                },
                success: function(result) {
                    vex.dialog.alert(result.message);
                    primary_table.ajax.reload(null, false);
                    primary_table2.ajax.reload(null, false);
                    secondary_table.ajax.reload(null, false);
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }
    });

    // function actionDelete2(element) {
    //     var item = $(element);
    //     item.prop('disabled', true);
    //     var url = delete_url2 + '/delete';
    //     vex.dialog.confirm({
    //         message: 'Apakah yakin mau menghapus soal?',
    //         callback: function(value) {
    //             if (value) {
    //                 $.ajax({
    //                     type: "POST",
    //                     url: url,
    //                     data: {
    //                         id_soal: item.attr('data-id')
    //                     },
    //                     success: function(data) {
    //                         vex.dialog.alert(data.message);
    //                         setTimeout(() => {
    //                             secondary_table.ajax.reload(null, false);
    //                         }, 2000)
    //                     },
    //                     error: function(xhr, status, error) {
    //                         console.log(xhr.responseText);
    //                     }
    //                 });
    //             } else {
    //                 item.prop('disabled', false);
    //             }
    //         }
    //     })
    // }
</script>
