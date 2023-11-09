<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>Data Beasiswa</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kelas</th>
                                    <th>Jenis Beasiswa</th>
                                    <th>Keterangan Beasiswa</th>
                                    <th>Tahun Mulai</th>
                                    <th>Tahun Selesai</th>
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
    var modul_url = 'kesiswaan';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'beasiswa/datatables';

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
                data: 'nm_kelas',
                name: 'nm_kelas'
            },
            {
                data: 'jenis_beasiswa_siswa',
                name: 'jenis_beasiswa_siswa'
            },
            {
                data: 'keterangan_beasiswa_siswa',
                name: 'keterangan_beasiswa_siswa'
            },
            {
                data: 'tahun_mulai_beasiswa_siswa',
                name: 'tahun_mulai_beasiswa_siswa'
            },
            {
                data: 'tahun_selesai_beasiswa_siswa',
                name: 'tahun_selesai_beasiswa_siswa'
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
