<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header bg-blue">
                        <h2>TAGIHAN SISWA</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Biaya Sekolah</th>
                                        <th>Jalur</th>
                                        <th>Nama Biaya</th>
                                        <th>Jenis Biaya</th>
                                        <th>Besar Tagihan</th>
                                        <th>Denda Tagihan</th>
                                        <th>Keterangan</th>
                                        <th>Besar Pembayaran</th>
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

    var modul_url       = 'keuangan';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'tagihan/datatables';

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
            { data: 'biaya_sekolah', name: 'biaya_sekolah' },
            { data: 'nm_jalur', name: 'nm_jalur'},
            { data: 'nm_biaya', name: 'nm_biaya' },
            { data: 'jenis_biaya', name: 'jenis_biaya'},
            { data: 'besar_biaya', name: 'besar_biaya'},
            { data: 'denda_biaya', name: 'denda_biaya'},
            { data: 'keterangan', name: 'keterangan'},
            { data: 'besar_pembayaran', name: 'besar_pembayaran'}
        ]
    });

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * 10;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();
</script>