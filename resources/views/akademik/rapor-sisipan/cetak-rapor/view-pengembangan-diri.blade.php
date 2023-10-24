<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-grey waves-effect target-link"
                href="{{ url(Request::segment(1) . '#rapor-sisipan/cetak-rapor') }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a>

            <a class="btn bg-green "
                href="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/cetak-rapor/template-pengembangan-diri/template-excel-pengembangan-diri/' . $id_kelas) }}"
                target="_blank"><i class="material-icons">cloud_download</i><span>Template Excel</span></a>

            <a class="btn bg-green waves-effect target-link"
                href="{{ url(Request::segment(1) . '#rapor-sisipan/cetak-rapor/importExcel') }}"><i
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
                                    @foreach ($kelompok_pribadi_sisipan as $pribadi_sisipan)
                                        <th>{{ $pribadi_sisipan->nm_kelompok_pribadi_sisipan }}</th>
                                    @endforeach
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
    var modul_url = 'rapor-sisipan';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' +
        'cetak-rapor/datatables/view-pengembangan-diri/' +
        id_semester + '/' + id_kelas;
    // var pdf_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'cetak-rapor/printAkhir/' + id_semester;

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
                data: 'pribadi_sisipan',
                name: 'pribadi_sisipan',
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
                data: 'pribadi_sisipan',
                name: 'pribadi_sisipan',
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
                data: 'pribadi_sisipan',
                name: 'pribadi_sisipan',
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
            // {
            //     data: 'action',
            //     name: 'action',
            //     searchable: false,
            //     orderable: false,
            //     className: 'align-center',
            //     render: function(data) {
            //         if (data.jumlah != '0') {
            //             return '<a class=" btn btn-success btn-circle waves-effect waves-circle waves-float" href="' +
            //                 pdf_url + '/' + data.id_siswa + '"  target="_blank">' +
            //                 '    <i class="material-icons">picture_as_pdf</i>' +
            //                 '</a> ';
            //         } else {
            //             return '<a class=" btn bg-grey btn-circle waves-effect waves-circle waves-float" href=""  style=" pointer-events: none;">' +
            //                 '    <i class="material-icons">picture_as_pdf</i>' +
            //                 '</a> ';
            //         }

            //     }
            // },
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
