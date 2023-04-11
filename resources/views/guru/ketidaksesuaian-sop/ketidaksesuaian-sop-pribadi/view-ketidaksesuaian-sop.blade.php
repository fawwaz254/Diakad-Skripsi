<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>DATA KETIDAKSESUAIAN SOP</h2>
                </div>
                <div class="body">
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table class="table table-bordered table-striped table-hover dataTable display"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Catatan Pelanggaran</th>
                                    <th>Tanggal Pelanggaran</th>

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
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var modul_url = 'ketidaksesuaian-sop';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' +
        'ketidaksesuaian-sop-pribadi/datatables';
    var edit_url = role_url + '#' + modul_url + '/' + 'input-pelanggaran/edit';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-input-pelanggaran/delete';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        dom: 'Bfrtip',
        lengthMenu: dtLengButton,
        buttons: dtButtonConfig,
        ajax: {
            url: datatable_url,
            type: 'GET'
        },
        columns: [{
                data: 'index_table',
                defaultContent: '',
                searchable: false,
                orderable: false
            },
            {
                data: 'catatan_pelanggaran',
                name: 'catatan_pelanggaran'
            },
            {
                data: 'tgl_pelanggaran',
                name: 'tgl_pelanggaran',
                searchable: false,
                orderable: false,
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
            primary_table.cell(cell).invalidate('dom');
        });
    }).draw();
</script>
