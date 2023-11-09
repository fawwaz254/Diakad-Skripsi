<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>RIWAYAT BAYAR SISWA</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Semester</th>
                                    <th>Nama</th>
                                    <th>-</th>
                                    <th>Tagihan</th>
                                    <th>Denda</th>
                                    <th>Pembayaran</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Semester Bayar</th>
                                    <th>Staff Keuangan</th>
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
    var modul_url = 'keuangan';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'riwayat-bayar/datatables';

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
                data: 'semester',
                name: 'semester',
                searchable: false,
                orderable: false
            },
            {
                data: 'nm_biaya',
                name: 'nm_biaya'
            },
            {
                data: 'jenis_biaya',
                name: 'jenis_biaya'
            },
            {
                data: 'besar_biaya',
                name: 'besar_biaya'
            },
            {
                data: 'denda_biaya',
                name: 'denda_biaya'
            },
            {
                data: 'besar_pembayaran',
                name: 'besar_pembayaran'
            },
            {
                data: 'tgl_pembayaran',
                name: 'tgl_pembayaran'
            },
            {
                data: 'semester_bayar',
                name: 'semester_bayar'
            },
            {
                data: 'nm_pengguna',
                name: 'nm_pengguna'
            },
            {
                data: 'nm_bank',
                name: 'nm_bank'
            },
            {
                data: 'nomor_transaksi',
                name: 'nomor_transaksi'
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
