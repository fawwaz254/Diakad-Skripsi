<style>
    .wali-kelas-link {
        color: black;
        text-decoration: none;
    }

    .wali-kelas-link:hover {
        color: #007bff;
        text-decoration: none;
        font-weight: bold;
    }
</style>

<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#setting-kelas/kelas/add') }}"><i
                    class="material-icons">note_add</i><span>Tambah Kelas</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>DATA KELAS</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jurusan</th>
                                    <th>Kelas</th>
                                    <th>Tingkat</th>
                                    <th>Keterangan</th>
                                    <th>Sekretaris</th>
                                    <th>Ruangan</th>
                                    <th>Wali Kelas</th>
                                    <th>Status Kelas</th>
                                    <th>BK</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <a class="target-link btn btn-block bg-blue waves-effect"
                                href="{{ url(Request::segment(1) . '#setting-kelas/kelas/copy') }}"><i
                                    class="material-icons">file_copy</i><span>Copy Sekretaris, Ruangan, Wali
                                    Kelas</span></a>
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
    var modul_url = 'setting-kelas';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'kelas/datatables';
    var edit_url = role_url + '#' + modul_url + '/' + 'kelas/edit';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-kelas/delete';

    var sekretaris_url = role_url + '#' + modul_url + '/' + 'sekretaris-kelas/view-kelas';
    var ruangan_url = role_url + '#' + modul_url + '/' + 'ruangan-kelas/view-kelas';
    var wali_kelas_url = role_url + '#' + modul_url + '/' + 'wali-kelas/view-kelas';
    var bk_kelas_url = role_url + '#' + modul_url + '/' + 'bk-kelas/add';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
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
                data: 'nm_jurusan',
                name: 'nm_jurusan'
            },
            {
                data: 'nm_kelas',
                name: 'nm_kelas'
            },
            {
                data: 'tingkat',
                name: 'tingkat'
            },
            {
                data: 'keterangan_kelas',
                name: 'keterangan_kelas'
            },
            {
                data: 'nm_sekretaris',
                name: 'nm_sekretaris',
                render: function(data) {
                    if (data.nm_sekretaris == 0) {
                        return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                            sekretaris_url + '/' + data.id + '">' +
                            '    <i class="material-icons">playlist_add</i>' +
                            '</a>';
                    } else {
                        return data.nm_sekretaris;
                    }
                }
            },
            {
                data: 'nm_ruangan',
                name: 'nm_ruangan',
                render: function(data) {
                    if (data.nm_ruangan == 0) {
                        return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                            ruangan_url + '/' + data.id + '">' +
                            '    <i class="material-icons">playlist_add</i>' +
                            '</a>';
                    } else {
                        return data.nm_ruangan;
                    };
                }
            },
            {
                data: 'nm_wali_kelas',
                name: 'nm_wali_kelas',
                render: function(data, type, row) {
                    if (data.nm_wali_kelas == 0) {
                        return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                            wali_kelas_url + '/' + row.id_kelas + '">' +
                            '    <i class="material-icons">playlist_add</i>' +
                            '</a>';
                    } else {
                        return '<a class="target-link wali-kelas-link" href="' + wali_kelas_url + '/' + row.id_kelas + '">' +
                            data.nm_wali_kelas +
                            '</a>';
                    }
                }
            },
            {
                data: 'status',
                data: 'status',
            },
            {
                data: 'bk_kelas',
                name: 'bk_kelas',
                render: function(data) {
                    if (data.bk_kelas == 0) {
                        return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                            bk_kelas_url + '/' + data.id + '">' +
                            '    <i class="material-icons">playlist_add</i>' +
                            '</a>';
                    } else {
                        return data.bk_kelas;
                    };
                }
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
                        '</a> ' +
                        '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\'' +
                        delete_url + '\', this)" data-id="' + data.id + '">' +
                        '    <i class="material-icons">delete_forever</i>' +
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
            cell.innerHTML = start + i + 1;
        });
    }).draw();
</script>