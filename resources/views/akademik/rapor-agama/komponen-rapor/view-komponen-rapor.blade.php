<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>Komponen Rapor</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Komponen</th>
                                    {{-- <th>Jenis Keterangan</th> --}}
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var modul_url = 'rapor-agama';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'komponen-rapor/datatables';

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
                orderable: false,
                className: 'align-center'
            },
            {
                data: 'nm_komponen_jenis_rapor',
                name: 'nm_komponen_jenis_rapor',
                className: 'align-center'
            },
            // {
            //     data: 'jenis_rapor.nm_jenis_rapor',
            //     name: 'jenis_rapor.nm_jenis_rapor',
            //     className: 'align-center'
            // },
            // {
            //     data: 'komponen_jenis_rapor',
            //     name: 'komponen_jenis_rapor',
            //     render: function(data) {
            //         let html = '';
            //         data.forEach(element => {
            //             html += '- ' +
            //                 element + ` <br>`;
            //         });
            //         return html;

            //     }
            // },
            // {
            //     data: 'jenis_keterangan',
            //     name: 'jenis_keterangan',
            //     className: 'align-center'
            // },


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
