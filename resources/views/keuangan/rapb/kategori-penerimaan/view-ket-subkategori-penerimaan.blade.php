<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link "
                href="{{ url(Request::segment(1) . '#rapb/kategori-penerimaan/sub/' . $data_subkategori_rapb->id_kategori_rapb) }}"><i
                    class="material-icons">backspace</i><span>Kembali Ke Sub-Kategori</span></a></h2>
    </div>
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#rapb/kategori-penerimaan/sub/ket/add/' . $data_subkategori_rapb->id_kategori_rapb . '/' . $data_subkategori_rapb->id_subkategori_rapb) }}"><i
                    class="material-icons">note_add</i><span>Tambah Keterangan Sub-Kategori Penerimaan</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>
                        DATA KETERANGAN SUB-KATEGORI PENERIMAAN <br>
                        Kategori : {{ $data_subkategori_rapb->kode_kategori_rapb }} -
                        {{ $data_subkategori_rapb->nm_kategori_rapb }} <br>
                        Sub-Kategori : {{ $data_subkategori_rapb->kode_subkategori_rapb }} -
                        {{ $data_subkategori_rapb->nm_subkategori_rapb }}
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama Keterangan Sub-Kategori</th>
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
    var modul_url = 'rapb';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'kategori-penerimaan/sub/ket/datatables/' +
        '{{ $data_subkategori_rapb->id_kategori_rapb }}' + '/' + '{{ $data_subkategori_rapb->id_subkategori_rapb }}';
    var edit_url = role_url + '#' + modul_url + '/' + 'kategori-penerimaan/sub/ket/edit/' +
        '{{ $data_subkategori_rapb->id_kategori_rapb }}' + '/' + '{{ $data_subkategori_rapb->id_subkategori_rapb }}';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' +
        'action-kategori-penerimaan/delete-ket-subkategori';

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
                data: 'kode_ket_subkategori_rapb',
                name: 'kode_ket_subkategori_rapb'
            },
            {
                data: 'nm_ket_subkategori_rapb',
                name: 'nm_ket_subkategori_rapb'
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
