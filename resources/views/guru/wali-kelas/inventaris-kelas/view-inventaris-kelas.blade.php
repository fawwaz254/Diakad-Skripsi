<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header bg-orange">
                        <h2>DATA INVENTARIS KELAS {{$wali_kelas->nm_kelas}}</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Ruangan</th>
                                        <th>Nama Inventaris</th>
                                        <th>Jumlah Inventaris</th>
                                        <th>Kondisi Baik</th>
                                        <th>Kondisi Rusak</th>
                                        <th>Spesifikasi Inventaris</th>
                                        <th>Keterangan</th>
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

    var modul_url       = 'wali-kelas';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'inventaris-kelas/datatables';

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
            { data: 'nm_ruangan', name: 'nm_ruangan' },
            { data: 'nm_inventaris_ruangan', name: 'nm_inventaris_ruangan' },
            { data: 'jumlah_inventaris_ruangan', name: 'jumlah_inventaris_ruangan'},
            { data: 'jumlah_kondisi_baik', name: 'jumlah_kondisi_baik'},
            { data: 'jumlah_kondisi_rusak', name: 'jumlah_kondisi_rusak'},
            { data: 'spesifikasi_inventaris_ruangan', name: 'spesifikasi_inventaris_ruangan'},
            { data: 'keterangan_inventaris_ruangan', name: 'keterangan_inventaris_ruangan'}
        ]
    });

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * 10;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();
</script>