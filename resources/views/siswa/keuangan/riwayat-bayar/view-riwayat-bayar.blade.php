<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header bg-blue">
                        <h2>RIWAYAT BAYAR SISWA</h2>
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
                                        <th>Besar Denda</th>
                                        <th>Besar Pembayaran</th>
                                        <th>Staff Keuangan</th>
                                        <th>Tanggal Bayar</th>
                                        <th>Semester Bayar</th>
                                        <th>Via Bank</th>
                                        <th>Nomor Ref Bank</th>
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
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'riwayat-bayar/datatables';

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
            { data: 'besar_pembayaran', name: 'besar_pembayaran'},
            { data: 'nm_pengguna', name: 'nm_pengguna'},
            { data: 'tgl_pembayaran', name: 'tgl_pembayaran'},
            { data: 'semester_bayar', name: 'semester_bayar'},
            { data: 'nm_bank', name: 'nm_bank'},
            { data: 'nomor_transaksi', name: 'nomor_transaksi'}
        ]
    });

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();
</script>