<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#'.Request::segment(2).'/mata-pelajaran/add') }}"><i
                    class="material-icons">note_add</i><span>Tambah Mata Pelajaran</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>DATA MATA PELAJARAN</h2>
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
                                    <th>Kode Mapel</th>
                                    <th>Nama Mapel</th>
                                    <th>Jenis Mapel</th>
                                    <th>RPP</th>
                                    <th>Status</th>
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
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var modul_url = '{{Request::segment(2)}}';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'mata-pelajaran/datatables';
    var edit_url = role_url + '#' + modul_url + '/' + 'mata-pelajaran/edit';
    var rpp_url = role_url + '#' + modul_url + '/' + 'mata-pelajaran/rpp';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-mata-pelajaran/delete';

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
                name: 'jurusan.nm_jurusan'
            },
            {
                data: 'kd_mata_pelajaran',
                name: 'mata_pelajaran.kd_mata_pelajaran'
            },
            {
                data: 'nm_mata_pelajaran',
                name: 'mata_pelajaran.nm_mata_pelajaran'
            },
            {
                data: 'nm_jenis_mata_pelajaran',
                name: 'jenis_mata_pelajaran.nm_jenis_mata_pelajaran'
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                        rpp_url + '?id=' + data.id + '">' +
                        '    <i class="material-icons">file_upload</i>' +
                        '</a> ';
                }
            },
            {
                data: 'status',
                name: 'status'
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
