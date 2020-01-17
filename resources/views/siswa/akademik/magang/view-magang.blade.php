<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>MAGANG SISWA</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Semester</th>
                                        <th>Nama - Periode Magang</th>
                                        <th>Instansi Magang</th>
                                        <th>Alamat Magang</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Selesai</th>
                                        <th>Nilai</th>
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
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'magang/datatables';

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
            { data: 'semester', name: 'semester' },
            { data: 'nm_magang', name: 'nm_magang'},
            { data: 'nm_rekanan_magang', name: 'nm_rekanan_magang' },
            { data: 'alamat_rekanan_magang', name: 'alamat_rekanan_magang'},
            { data: 'tgl_magang_mulai', name: 'tgl_magang_mulai'},
            { data: 'tgl_magang_selesai', name: 'tgl_magang_selesai'},
            { data: 'nilai', name: 'nilai'}
        ]
    });

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();
</script>