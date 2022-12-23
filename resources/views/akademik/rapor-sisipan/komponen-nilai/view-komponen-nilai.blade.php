<div class="container-fluid">
    {{-- <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#rapor-sisipan/daftar-nilai-sts/add') }}"><i
                    class="material-icons">add</i><span>Tambah Nilai</span></a></h2>
    </div> --}}
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>Daftar Komponen Nilai Rapor Sisipan</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Komponen Nilai</th>
                                    <th>Urutan</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    {{-- <th>Action</th> --}}
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
    var modul_url = 'rapor-sisipan';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'komponen-nilai/datatables';
    var edit_url = role_url + '#' + modul_url + '/' + 'komponen-nilai/edit';
    // var nilai_url = role_url + '#' + modul_url + '/' + 'daftar-nilai-sts/nilai';
    // var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'daftar-nilai-sts/action-daftar-nilai-sts/delete';
    // var print_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'daftar-nilai-sts/print';
    // var pdf_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'daftar-nilai-sts/pdf';

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
                data: 'nm_nilai',
                name: 'nm_nilai',
                className: 'align-center'
            },
            {
                data: 'urutan',
                name: 'urutan',
                className: 'align-center'
            },
            {
                data: 'type',
                name: 'type',
                className: 'align-center'
            },
            {
                data: 'status',
                name: 'status',
                className: 'align-center'
            }
            // {
            //     data: 'action',
            //     name: 'action',
            //     searchable: false,
            //     orderable: false,
            //     className: 'align-center',
            //     render: function(data) {
            //         return '<a class=" btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
            //             edit_url + '/' + data.id + '" >' +
            //             '    <i class="material-icons">edit</i>' +
            //             '</a> ';
            //     }
            // }
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
