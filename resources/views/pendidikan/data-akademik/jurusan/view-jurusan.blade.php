<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#data-akademik/jurusan/add') }}"><i
                    class="material-icons">note_add</i><span>Tambah Jurusan</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>DATA JURUSAN</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Jurusan</th>
                                    <th>Kode Jurusan</th>
                                    <th>Bidang Keahlian</th>
                                    <th>Program Keahlian</th>
                                    <th>Kompetensi/Konsentrasi Keahlian</th>
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
    {{-- // var modul_url = location.hash.replace('#','').split('/')[0]; --}}
    var modul_url = 'data-akademik';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'jurusan/datatables';
    var edit_url = role_url + '#' + modul_url + '/' + 'jurusan/edit';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-jurusan/delete';

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
                data: 'kode_jurusan',
                name: 'kode_jurusan'
            },
            {
                data: 'bidang_keahlian',
                name: 'bidang_keahlian'
            },
            {
                data: 'program_keahlian',
                name: 'program_keahlian'
            },
            {
                data: 'kompetensi_keahlian',
                name: 'kompetensi_keahlian'
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
