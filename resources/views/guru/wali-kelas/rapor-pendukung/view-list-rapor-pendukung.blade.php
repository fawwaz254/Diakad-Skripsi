<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>List Rapor Pendukung</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Rapor Pendukung</th>
                                    <th>Komponen</th>
                                    <th>Predikat Nilai</th>
                                    <th>Cetak</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br>

@include('scriptjs')
<script>
    var modul_url = 'wali-kelas';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'rapor-pendukung/datatables';
    var print_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'rapor-pendukung/print';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        order: [1, 'asc'],
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
                data: 'nm_rapor',
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                className: 'align-center',
                render: function(data) {
                    return `
                    ${data.total_komponen} Komponen
                    <a class="btn btn-success btn-circle waves-effect waves-circle waves-float text-center" href="${base_url}/${role_url}#${modul_url}/rapor-pendukung/komponen/${data.id}">
                        <i class="material-icons">settings</i>
                    </a>`;
                }
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                className: 'align-center',
                render: function(data) {
                    return `
                    <a class="btn btn-success btn-circle waves-effect waves-circle waves-float text-center" href="${base_url}/${role_url}#${modul_url}/rapor-pendukung/predikat/${data.id}">
                        <i class="material-icons">add</i>
                    </a>`;
                }
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                className: 'align-center',
                render: function(data) {
                    return `
                    <a class="btn btn-success btn-circle waves-effect waves-circle waves-float text-center" href="${base_url}/${role_url}/${modul_url}/rapor-pendukung/cetak/${data.id}" target="_blank">
                        <i class="material-icons">picture_as_pdf</i>
                    </a>`;
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

    $(document).ready(function() {
        var pathname = window.location.pathname;
        var segments = pathname.split('/');
        var role = segments[1];

        if (role == 'guru') {
            primary_table.column(2).visible(false);
        } else if (role == 'akademik') {
            primary_table.column(3).visible(false);
            primary_table.column(4).visible(false);
        }
    });
</script>
