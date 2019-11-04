<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header bg-blue">
                        <h2>JADWAL KBM SEMESTER {{$semester_aktif->tahun_ajaran}} {{strtoupper($semester_aktif->nm_semester)}}</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Guru Pengampu</th>
                                        <th>Hari</th>
                                        <th>Jam KBM</th>
                                        <th>Kelas</th>
                                        <th>Ruangan</th>
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

    var modul_url       = 'akademik';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'jadwal-kbm/datatables';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
responsive: true,
        ajax: {
            url: datatable_url,
            type: 'GET'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'mata_pelajaran', name: 'mata_pelajaran' },
            { data: 'nm_pengguna', name: 'nm_pengguna'},
            { data: 'nm_jadwal_hari', name: 'nm_jadwal_hari' },
            { data: 'jadwal_jam', name: 'jadwal_jam'},
            { data: 'nm_kelas', name: 'nm_kelas'},
            { data: 'nm_ruangan', name: 'nm_ruangan'}
        ]
    });

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();
</script>