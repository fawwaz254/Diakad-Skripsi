<div class="container-fluid">
    <div class="block-header">
        <h2>
            <a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#aktivitas-semester/monitoring-kelas/view-semester-monitoring-kelas/'.$data_semester->id_semester)}}">
                <i class="material-icons">backspace</i><span>Kembali</span>
            </a>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header bg-light-5green">
                        <h2>Daftar Siswa Kelas</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NISN</th>
                                        <th>NIS</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
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
@include('scriptjs')
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var id_kelas_mp= {!! json_encode($id) !!};

    var modul_url       = 'aktivitas-semester';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'monitoring-kelas/datatables-daftar-siswa/' + id_kelas_mp;

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
            { data: 'nisn_siswa', name: 'nisn_siswa' },
            { data: 'nis_siswa', name: 'nis_siswa' },
            { data: 'nm_pengguna', name: 'nm_pengguna' },
            { data: 'nm_kelas_mp', name: 'nm_kelas_mp' }
        ]
    });

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();
</script>