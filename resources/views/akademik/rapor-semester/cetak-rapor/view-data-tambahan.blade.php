<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-grey waves-effect target-link"
                href="{{ url(Request::segment(1) . '#rapor-semester/cetak-rapor') }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a>

            <a class="btn bg-green "
                href="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/cetak-rapor/view-data-tambahan/template-excel-data-tambahan/' . $id_kelas) }}"
                target="_blank"><i class="material-icons">cloud_download</i><span>Template Excel</span></a>

            <a class="btn bg-green waves-effect target-link"
                href="{{ url(Request::segment(1) . '#rapor-semester/cetak-rapor/view-data-tambahan/importExcel') }}"><i
                    class="material-icons">cloud_upload</i><span>Import Ekcel</span></a>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>View Nilai Pengembangan Diri</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama</th>
                                    @foreach ($kelompok_tambahan_rapor as $k)
                                        <th>{{ $k->nm_kelompok_tambahan_rapor }}</th>
                                    @endforeach
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

<script type="text/javascript">
    var id_semester = '{{ $id_semester }}';
    var id_kelas = '{{ $id_kelas }}';
    var modul_url = 'rapor-semester';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' +
        'cetak-rapor/view-data-tambahan/datatables/' +
        id_semester + '/' + id_kelas;
    // var pdf_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'cetak-rapor/printAkhir/' + id_semester;
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' +
        'cetak-rapor/action-pengembangan-diri/delete';

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
                orderable: false,
                className: 'align-center'
            },
            {
                data: 'nis_siswa',
                name: 'nis_siswa',
                className: 'align-center'
            },
            {
                data: 'pengguna.nm_pengguna',
                name: 'pengguna.nm_pengguna',
                className: 'align-center'
            },
            {
                data: 'tambahan_rapor',
                name: 'tambahan_rapor',
                render: function(data) {
                    let html = '';
                    if (data.n1 && data.n1.length != 0) {
                        data.n1.forEach(element => {
                            html += '- ' +
                                element + ` <br>`;
                        });
                    }
                    return html;

                }
            },
            {
                data: 'tambahan_rapor',
                name: 'tambahan_rapor',
                render: function(data) {
                    let html = '';
                    if (data.n2 && data.n2.length != 0) {
                        data.n2.forEach(element => {
                            html += '- ' +
                                element + ` <br>`;
                        });
                    }
                    return html;

                }
            },
            {
                data: 'tambahan_rapor',
                name: 'tambahan_rapor',
                render: function(data) {
                    let html = '';
                    if (data.n3 && data.n3.length != 0) {
                        data.n3.forEach(element => {
                            html += '- ' +
                                element + ` <br>`;
                        });
                    }
                    return html;

                }
            },
            {
                data: 'tambahan_rapor',
                name: 'tambahan_rapor',
                render: function(data) {
                    let html = '';
                    if (data.n4 && data.n4.length != 0) {
                        data.n4.forEach(element => {
                            html += '- ' +
                                element + ` <br>`;
                        });
                    }
                    return html;

                }
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                className: 'align-center',
                render: function(data) {
                    return '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\'' +
                        delete_url + '\', this)" data-id="' + data.id + '">' +
                        '    <i class="material-icons">delete_forever</i>' +
                        '</button>';

                }
            },
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
