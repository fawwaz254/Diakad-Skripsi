<div class="container-fluid">
    <div class="block-header">
        <h2>
            <a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#wali-kelas/input-kpi/import') }}"><i
                    class="material-icons">note_add</i><span>Import Nilai KPI Siswa</span></a>
            <a target="_blank" class="btn bg-green waves-effect "
                href="{{ url(Request::segment(1) . '/wali-kelas/input-kpi/download') }}"><i
                    class="material-icons">note_add</i><span>Download Template Nilai KPI Siswa</span></a>


            <a style="color: black; position: absolute; right: 30px;" target="_blank"
                class="btn bg-white waves-effect d-flex flex-row-reverse"
                href="{{ url(Request::segment(1) . '/wali-kelas/input-kpi/print') }}"><i
                    class="material-icons">note_add</i><span>Cetak PDF Nilai KPI Siswa</span></a>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>List Siswa KPI</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nis</th>
                                    <th>Nama</th>
                                    <th>Total Point KPI</th>
                                    <th>Point KPI Terisi</th>

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
    var modul_url = 'wali-kelas';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'input-kpi/datatables';
    var print_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'input-kpi/print';
    var id_semester = '{{ $semester_aktif->id_semester }}';


    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        order: [1, 'asc'],
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
                data: 'nis_siswa',
                name: 'nis_siswa'
            },
            {
                data: 'pengguna.nm_pengguna',
                name: 'pengguna.nm_pengguna',
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
                        print_url + '/' + id_semester + '/' + data.id + '">' +
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
