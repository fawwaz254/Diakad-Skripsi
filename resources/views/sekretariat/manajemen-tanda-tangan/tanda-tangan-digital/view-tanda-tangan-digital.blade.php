<div class="container-fluid">
    <div class="block-header">
        <h2>
            <a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#manajemen-tanda-tangan/tanda-tangan-digital/add') }}"><i
                    class="material-icons">note_add</i><span>Tambah Dokumen</span></a>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>Data Dokumen Tanda Tangan Digital</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Perihal Dokumen</th>
                                    <th>Dokumen</th>
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
@include('scriptjs')
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var modul_url = 'manajemen-tanda-tangan';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'tanda-tangan-digital/datatables';
    var edit_url = role_url + '#' + modul_url + '/' + 'tanda-tangan-digital/edit';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' +
        'tanda-tangan-digital/action-tanda-tangan-digital/delete';
    var preview_url = base_url + '/' + role_url + '/' + modul_url + '/' +
        'tanda-tangan-digital/preview';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
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
                data: 'perihal_dokumen',
                name: 'perihal_dokumen'
            },
            {
                data: 'action',
                name: 'action',
                render: function(data) {
                    return `<a href="${preview_url}/${data.id}" target="_blank" rel="noopener noreferrer">Lihat Dokumen</a>`
                }
            },
            {
                data: 'is_approve',
                name: 'is_approve',
                render: function(data) {
                    // return `<a href="${data.id}" target="_blank" rel="noopener noreferrer">Lihat Dokumen</a>`
                    if (data) {
                        return `<p style="color:green">Sudah di approve</p>`
                    }
                    return `<p style="color:red">Belum di approve</p>`
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
