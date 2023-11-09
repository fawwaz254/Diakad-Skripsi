<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3)) }}">
                <i class="material-icons">backspace</i><span>Kembali</span></a></h2>
        <br>
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3) . '/' . Request::segment(4) . '/' . $item->id_kegiatan_harian . '/add') }}">
                <i class="material-icons">note_add</i><span>Tambah Kategori</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>DATA KATEGORI PERTANYAAN {{ $item->nm_kegiatan_harian }}</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kategori Pertanyaan</th>
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
<script>
    var modul_url = '{{ Request::segment(2) }}';
    var menu_url = '{{ Request::segment(3) }}';
    var page_1_url = '{{ Request::segment(4) }}';
    var id_1 = '{{ $item->id_kegiatan_harian }}';

    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + menu_url + '/' + page_1_url + '/' + id_1 +
        '/datatables';
    var edit_url = role_url + '#' + modul_url + '/' + menu_url + '/' + page_1_url + '/' + id_1 + '/edit';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + menu_url + '/' + page_1_url + '/action/delete';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
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
                data: 'nm_kegiatan_harian_kategori'
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
