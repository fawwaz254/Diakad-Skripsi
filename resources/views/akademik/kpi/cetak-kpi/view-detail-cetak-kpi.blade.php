<div class="container-fluid">
    <div class="block-header">
        <h2>
            <a class="btn bg-blue waves-effect target-link" href="{{ url(Request::segment(1) . '#kpi/cetak-kpi') }}"><i
                    class="material-icons">backspace</i><span>kembali</span></a>
            <a target="_blank" class="btn btn-success waves-effect" href="{{ route('kpi.printAll', ['role' => Request::segment(1), 'id_kelas' => $id_kelas]) }}">
                    <i class="material-icons">print</i><span>Cetak Semua</span></a>

        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>List Siswa</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kelas</th>
                                    <th>Nis</th>
                                    <th>Siswa</th>
                                    <th>Jumlah Point</th>
                                    <th>Terisi</th>
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
<br>

@include('scriptjs')
<script>
    var id_kelas = '{{ $id_kelas }}';
    var modul_url = 'kpi';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'cetak-kpi/detail/datatables/' + id_kelas;
    var print_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'cetak-kpi/print';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        ajax: {
            url: datatable_url,
            type: 'GET',
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'kelas.nm_kelas',
                name: 'kelas.nm_kelas'
            },
            {
                data: 'nis_siswa', // ← Tambah ini
                name: 'nis_siswa'
            },
            {
                data: 'pengguna.nm_pengguna',
                name: 'pengguna.nm_pengguna'
            },
            {
                data: 'jumlah_point',
                name: 'jumlah_point'
            },
            {
                data: 'jumlah_point_terisi',
                name: 'jumlah_point_terisi'
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                className: 'align-center',
                render: function(data) {
                    return '<a target="_blank" class="btn btn-success btn-circle waves-effect waves-circle waves-float text-center" href="' +
                        print_url + '/' + data.id_semester + '/' + data.id + '">' +
                        '    <i class="material-icons">print</i>' +
                        '</a>';
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
