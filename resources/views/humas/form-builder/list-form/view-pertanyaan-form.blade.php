<div class="container-fluid">
    <div class="block-header">
        <h2>
            <a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3)) }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a>
            <a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/list-form/pertanyaan/add/1/' . $id_form) }}"><i
                    class="material-icons">note_add</i><span>Tambah Pertanyaan Text</span></a>
            <a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/list-form/pertanyaan/add/2/' . $id_form) }}"><i
                    class="material-icons">note_add</i><span>Tambah Pertanyaan Foto</span></a>
            <a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/list-form/pertanyaan/add/3/' . $id_form) }}"><i
                    class="material-icons">note_add</i><span>Tambah Pertanyaan Satu Opsi</span></a>
            <a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/list-form/pertanyaan/add/4/' . $id_form) }}"><i
                    class="material-icons">note_add</i><span>Tambah Pertanyaan Banyak Opsi</span></a>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>Data Pertanyaan Form</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Pertanyaan</th>
                                    <th>Jenis Pertanyaan</th>
                                    <th>Urutan</th>
                                    <th>Option</th>
                                    <th>Opsi Lainnya</th>
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
    var id_form = '{{ $id_form }}';
    var modul_url = '{{ Request::segment(2) }}';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'list-form/pertanyaan/datatables/' +
        id_form;
    var edit_url = role_url + '#' + modul_url + '/' + 'list-form/pertanyaan/edit';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' +
        'list-form/pertanyaan/action-pertanyaan-form/delete';

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
                data: 'nm_pertanyaan_form',
                name: 'nm_pertanyaan_form'
            },
            {
                data: 'jenis_pertanyaan',
                name: 'jenis_pertanyaan'
            },
            {
                data: 'urutan',
                name: 'urutan'
            },
            {
                data: 'options',
                name: 'options',
                render: function(data_opsi) {
                    let html = '';
                    data_opsi.forEach(element => {
                        html += '- ' +
                            element + ` <br>`;
                    });
                    return html;
                }
            },
            {
                data: 'others',
                name: 'others',
                render: function(data_others) {
                    if (data_others == 1) {
                        return 'Ya';
                    } else {
                        return 'Tidak';
                    }
                }
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return `
                        <a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float text-center" href="${edit_url}/${data.jenis_pertanyaan}/${data.id_form}/${data.id_pertanyaan_form}">
                            <i class="material-icons">edit</i>
                        </a>
                        <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction('${delete_url}', this)" data-id="${data.id_pertanyaan_form}">
                            <i class="material-icons">delete_forever</i>
                        </button>
                    `;
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
