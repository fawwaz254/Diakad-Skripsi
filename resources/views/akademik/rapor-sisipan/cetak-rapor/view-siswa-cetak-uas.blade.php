<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>List Siswa Cetak Rapor Sisipan UAS</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama</th>
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
    var thn_akademik_semester = {!! json_encode($thn_akademik_semester) !!};
    var id_kelas = {!! json_encode($id_kelas) !!};
    var modul_url = 'rapor-sisipan';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'cetak-rapor/datatables/view-siswa-uas/' +
        thn_akademik_semester + '/' + id_kelas;
    var pdf_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'cetak-rapor/printAkhir/' + thn_akademik_semester;


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
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                className: 'align-center',
                render: function(data) {
                    if (data.jumlah != '0') {
                        return '<a class=" btn btn-success btn-circle waves-effect waves-circle waves-float" href="' +
                            pdf_url + '/' + data.id_siswa + '"  target="_blank">' +
                            '    <i class="material-icons">picture_as_pdf</i>' +
                            '</a> ';
                    } else {
                        return '<a class=" btn bg-grey btn-circle waves-effect waves-circle waves-float" href=""  style=" pointer-events: none;">' +
                            '    <i class="material-icons">picture_as_pdf</i>' +
                            '</a> ';
                    }

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
