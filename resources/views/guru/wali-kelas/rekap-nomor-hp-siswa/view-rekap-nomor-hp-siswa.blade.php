<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>DATA NOMOR HP SISWA KELAS : {{ $wali_kelas->nm_kelas }}</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Nomor HP Wali</th>
                                    <th>Nama Wali</th>
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
    var modul_url = 'wali-kelas';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'rekap-nomor-hp/datatables';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        "aLengthMenu": [100],
        responsive: true,
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
                data: 'nm_pengguna',
                name: 'nm_pengguna'
            },
            {
                data: 'siswa.wali_murid.nomor_hp_wali_murid',
                name: 'siswa.wali_murid.nomor_hp_wali_murid',
                render: function(data) {
                    if (data) {
                        return data;
                    } else {
                        return ''
                    }
                }
            },
            {
                data: 'nm_wali_murid'
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
