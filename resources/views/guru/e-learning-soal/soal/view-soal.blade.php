<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <a type="button" class="btn bg-grey waves-effect" style="margin-bottom: 15px"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/paket-soal') }}">
                <i class="material-icons">keyboard_backspace</i>
                <span>Kembali</span>
            </a>
            <a type="button" class="btn btn-success" style="margin-bottom: 15px"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/paket-soal/bank-soal/new/pilihan-ganda') }}">
                <i class="material-icons">add_box</i>
                <span>Type Pilihan Ganda</span>
            </a>
            <a type="button" class="btn btn-success" style="margin-bottom: 15px"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/paket-soal/bank-soal/new/essay') }}">
                <i class="material-icons">add_box</i>
                <span>Type Isian</span>
            </a>
            <a type="button" class="btn btn-success" style="margin-bottom: 15px"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/paket-soal/bank-soal/new/submit') }}">
                <i class="material-icons">add_box</i>
                <span>Type File</span>
            </a>
            <a type="button" class="btn btn-success" style="margin-bottom: 15px"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/paket-soal/bank-soal/new/pilihan-ganda-kompleks') }}">
                <i class="material-icons">add_box</i>
                <span>Type Pilihan Ganda Kompleks</span>
            </a>
            <a type="button" class="btn btn-success" style="margin-bottom: 15px"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/paket-soal/bank-soal/new/simple-essay') }}">
                <i class="material-icons">add_box</i>
                <span>Type Isian Singkat</span>
            </a>
            <a type="button" class="btn btn-success" style="margin-bottom: 15px"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/paket-soal/bank-soal/new/match') }}">
                <i class="material-icons">add_box</i>
                <span>Type Penjodohan</span>
            </a>
            <a type="button" class="btn btn-success" style="margin-bottom: 15px"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/paket-soal/bank-soal/new/true-false') }}">
                <i class="material-icons">add_box</i>
                <span>Type True/False</span>
            </a>
            {{-- <a type="button" class="btn btn-primary" style="margin-bottom: 15px"
                href="{{ url('guru#e-learning-soal/soal/kategori') }}">
                <i class="material-icons">settings</i>
                <span>Mata Pelajaran</span>
            </a> --}}
            <div class="card">
                <div class="header">
                    <h2>
                        Bank Soal
                    </h2>
                </div>

                <div class="body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#belum" data-toggle="tab">
                                <i class="material-icons">person</i>Pribadi
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#sudah" data-toggle="tab">
                                <i class="material-icons">people</i> Semua Guru
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="belum">

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable display"
                                    id="primary_table">
                                    <thead>
                                        <tr>

                                            <th>No</th>
                                            <th>Tipe Soal</th>
                                            <th>Mata Pelajaran</th>
                                            <th>Pertanyaan</th>
                                            <th>Action</th>
                                            <th>Tanggal Pembuatan</th>
                                        </tr>
                                    </thead>
                                </table>

                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="sudah">

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable display"
                                    id="secondary_table" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tipe Soal</th>
                                            <th>Mata Pelajaran</th>
                                            <th>Pertanyaan</th>
                                            <th>Action</th>
                                            <th>Pembuat</th>
                                            <th>Tanggal Pembuatan</th>
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
@include('scriptjs')
<script>
    var modul_url = '{{ Request::segment(2) }}';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'paket-soal/bank-soal/table';
    var detail_url = role_url + '#' + modul_url + '/' + 'paket-soal/bank-soal';
    var delete_url = role_url + '/' + modul_url + '/' + 'paket-soal/bank-soal';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: datatable_url,
            type: 'POST',
            data: function(params) {
                params.status = 0;
            },
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
                data: 'text',
                name: 'text',
                orderable: false
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    if (data.delete) {
                        return '<a type="button" class="btn btn-success btn-circle waves-effect waves-circle waves-float" href="' +
                            detail_url + '/edit/' + data.id + '">' +
                            '    <i class="material-icons">mode_edit</i>' +
                            '</a>' +
                            '<a type="button" class="btn  btn-circle btn-info waves-effect waves-circle waves-float" href="' +
                            detail_url + '/test/' + data.id + '">' +
                            '    T' +
                            '</a>' +
                            '<button type="button" class="btn btn-danger btn-circle waves-effect waves-circle waves-float" data-id="' +
                            data.id + '" onclick="actionDelete(this)">' +
                            '    <i class="material-icons">delete</i>' +
                            '</button>';
                    } else {
                        return '<a type="button" class="btn btn-success btn-circle waves-effect waves-circle waves-float" href="' +
                            detail_url + '/edit/' + data.id + '">' +
                            '    <i class="material-icons">mode_edit</i>' +
                            '</a>' +
                            '<a type="button" class="btn btn-info  btn-circle waves-effect waves-circle waves-float" href="' +
                            detail_url + '/test/' + data.id + '">' +
                            '    T' +
                            '</a>';
                    }
                }
            },
            {
                data: 'time'
            },
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
            url: datatable_url,
            type: 'POST',
            data: function(params) {
                params.status = 1;
            },
        },
        columns: [{
                data: 'index_table',
                defaultContent: '',
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
                data: 'text',
                name: 'text',
                orderable: false
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<center><a type="button" class="btn btn-info   btn-circle waves-effect waves-circle waves-float" href="' +
                        detail_url + '/test/' + data.id + '">' +
                        '    T' +
                        '</a></center>';
                }
            },
            {
                data: 'pengguna.nm_pengguna'
            },
            {
                data: 'time'
            }
        ]
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

    function actionDelete(element) {
        var item = $(element);
        item.prop('disabled', true);
        var url = delete_url + '/delete';
        vex.dialog.confirm({
            message: 'Apakah yakin mau menghapus soal?',
            callback: function(value) {
                if (value) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {
                            id_soal: item.attr('data-id')
                        },
                        success: function(data) {
                            vex.dialog.alert(data.message);
                            setTimeout(() => {
                                primary_table.ajax.reload(null, false);
                            }, 2000)
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText);
                        }
                    });
                } else {
                    item.prop('disabled', false);
                }
            }
        })
    }
</script>
