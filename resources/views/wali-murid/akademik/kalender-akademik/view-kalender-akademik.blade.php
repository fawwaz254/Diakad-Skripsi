<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>KALENDER AKADEMIK SEMESTER {{ $semester_aktif->tahun_ajaran }}
                        {{ strtoupper($semester_aktif->nm_semester) }}</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kegiatan</th>
                                    <th>Deskripsi Kegiatan</th>
                                    <th>Semester</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
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
    var modul_url = 'akademik';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'kalender-akademik/datatables';

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
                data: 'nm_kegiatan',
                name: 'nm_kegiatan'
            },
            {
                data: 'deskripsi_kegiatan',
                name: 'deskripsi_kegiatan'
            },
            {
                data: 'semester',
                name: 'semester'
            },
            {
                data: 'tgl_mulai',
                name: 'tgl_mulai'
            },
            {
                data: 'tgl_selesai',
                name: 'tgl_selesai'
            }
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
