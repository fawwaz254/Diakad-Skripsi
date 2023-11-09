<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>Data Prestasi Siswa</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Prestasi</th>
                                    <th>Tingkat Prestasi</th>
                                    <th>Jenis Prestasi</th>
                                    <th>Peringkat</th>
                                    <th>Semester</th>
                                    <th>Kelas</th>
                                    <th>Lokasi</th>
                                    <th>Penyelenggara</th>
                                    <th>Tanggal</th>
                                    <th>Ekstrakurikuler</th>
                                    <th>Guru Pendamping</th>
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
    var modul_url = '{{ Request::segment(2) }}';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'prestasi/datatables';

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
                data: 'nm_prestasi_siswa',
                name: 'nm_prestasi_siswa'
            },
            {
                data: 'nm_tingkat_prestasi_siswa',
                name: 'nm_tingkat_prestasi_siswa'
            },
            {
                data: 'jenis_prestasi',
                name: 'jenis_prestasi'
            },
            {
                data: 'peringkat_prestasi_siswa',
                name: 'peringkat_prestasi_siswa'
            },
            {
                data: 'semester',
                name: 'semester'
            },
            {
                data: 'nm_kelas',
                name: 'nm_kelas'
            },
            {
                data: 'lokasi_prestasi_siswa',
                name: 'lokasi_prestasi_siswa'
            },
            {
                data: 'penyelenggara_prestasi_siswa',
                name: 'penyelenggara_prestasi_siswa'
            },
            {
                data: 'tgl_prestasi_siswa',
                name: 'tgl_prestasi_siswa'
            },
            {
                data: 'nm_ekskul',
                name: 'nm_ekskul'
            },
            {
                data: 'nm_guru_pendamping',
                name: 'nm_guru_pendamping'
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
