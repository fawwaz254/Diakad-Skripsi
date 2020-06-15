<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                    SETTING
                    </h2>
                </div>
                @include('keuangan/sim/spp/partials/header-card-menu')
            </div>
            <style>
                table tr th{
                    text-align: center;
                }
            </style>
            <div class="card">
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display nowrap" id="primary_table">
                            <thead>
                                <tr>
                                    <th>No </th>
                                    <th>Kelas</th>
                                    <th>SPP JULI</th>
                                    <th>SPP</th>
                                    <th>Tingkat</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

var datatable_url   = base_url + '/' + role_url + '/sim/spp/setting/datatables';
var primary_table = $('#primary_table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: datatable_url,
        type: 'POST',
    },
    columns: [
        { data: null, searchable: false, orderable: false },
        { data: 'nm_kelas' },
        { data: 'nominal_spp_juli', searchable: false, orderable: false },
        { data: 'nominal_spp_non_juli', searchable: false, orderable: false },
        { data: 'tingkat' },
        { data: 'action', searchable: false, orderable: false },
    ]
});

primary_table.on( 'draw', function () {
    primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        var start = this.page.info().page * this.page.info().length;
        cell.innerHTML = start + i + 1;
    } );
} ).draw();
</script>