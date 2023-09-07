<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        TUNGGAKAN SUDAH DIHAPUS
                    </h2>
                </div>
                @include('keuangan/sim/spp/partials/header-card-menu')
            </div>
            <div class="card">
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No </th>
                                    <th>Nis</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Bulan</th>
                                    <th>Kelompok Biaya</th>
                                    <th>Semester</th>
                                    <th>Besar Biaya</th>
                                    <th>Waktu Penghapusan</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


@include('scriptjs')
<script>
    var datatable_url = base_url + '/' + role_url + '/sim/spp/tunggakan-sudah-dihapus/datatables';
    var primary_table = $('#primary_table').DataTable({
        processing: true,
        // serverSide: true,
        aLengthMenu: [
            [25, 50, 100, 200, -1],
            [25, 50, 100, 200, "All"]
        ],
        iDisplayLength: -1,
        responsive: true,
        ajax: {
            url: datatable_url,
            type: 'POST',
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'nis_siswa',
                name: 'nis_siswa',
            },
            {
                data: 'nm_pengguna',
                name: 'nm_pengguna'
            },
            {
                data: 'nm_kelas',
                name: 'nm_kelas'
            },
            {
                data: 'thn_masuk_siswa',
                name: 'thn_masuk_siswa'
            },
            {
                data: 'total_biaya',
                name: 'total_biaya'
            },
            {
                data: 'tunggakan.jumlah_tunggakan',
                name: 'tunggakan.jumlah_tunggakan'
            },
            {
                data: 'tunggakan.selisih',
                name: 'tunggakan.selisih'
            },
            {
                data: 'total_tagihan',
                name: 'total_tagihan'
            },
            {
                data: 'tunggakan',
                searchable: false,
                orderable: false,
                render: function(data) {
                    if (data.selisih == '-') {
                        return '<button class="btn btn-info btn-circle waves-effect waves-circle waves-float" onclick="detailAction(this)"  data-id="' +
                            data.action + '"  data-status="-" >' +
                            '    <i class="material-icons">pageview</i>' +
                            '</button>';
                    } else {
                        return '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="detailAction(this)"  data-id="' +
                            data.action + '" data-status="' +
                            data.selisih + '" >' +
                            '    <i class="material-icons">pageview</i>' +
                            '</button>';
                    }
                }
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
