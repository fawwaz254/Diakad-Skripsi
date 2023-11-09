<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#data-akademik/kurikulum/add') }}"><i
                    class="material-icons">note_add</i><span>Tambah Kurikulum</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>DATA KURIKULUM</h2>
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
                                    <th>Semester Mulai</th>
                                    <th>Nama Kurikulum</th>
                                    <th>Tahun Kurikulum</th>
                                    <th>Nomor SK</th>
                                    <th>Keterangan</th>
                                    <th>Berlaku Mulai</th>
                                    <th>Berlaku Sampai</th>
                                    <th>Status Aktif</th>
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
    var modul_url = 'data-akademik';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'kurikulum/datatables';
    var edit_url = role_url + '#' + modul_url + '/' + 'kurikulum/edit';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-kurikulum/delete';

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
                data: 'semester_mulai',
                name: 'semester_mulai'
            },
            {
                data: 'nm_kurikulum',
                name: 'nm_kurikulum'
            },
            {
                data: 'tahun_kurikulum',
                name: 'tahun_kurikulum'
            },
            {
                data: 'nomor_sk_kurikulum',
                name: 'nomor_sk_kurikulum'
            },
            {
                data: 'keterangan_kurikulum',
                name: 'keterangan_kurikulum'
            },
            {
                data: 'berlaku_mulai',
                name: 'berlaku_mulai'
            },
            {
                data: 'berlaku_sampai',
                name: 'berlaku_sampai'
            },
            {
                data: 'status_aktif',
                name: 'status_aktif'
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
