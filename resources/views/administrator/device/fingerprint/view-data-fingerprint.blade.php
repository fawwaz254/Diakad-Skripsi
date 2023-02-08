<style>
    .dataTables_length,
    .dataTables_filter {
        display: none;
    }
</style>
<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{ url(Request::segment(1)) }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a>
    </div>

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>DATA ALAT FINGERPRINT</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama FP</th>
                                    <th>SN</th>
                                    <th>Status</th>
                                    <th>WAN</th>
                                    <th>LAN</th>
                                    <th>PORT</th>
                                    <th>Last Updated</th>
                                    <th>Last Data</th>
                                    <th>Last Clear Log</th>
                                    <th></th>
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
    var modul_url = '{{ Request::segment(2) }}';
    var menu_url = '{{ Request::segment(3) }}';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + menu_url + '/datatables';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        paging: false,
        ajax: {
            url: datatable_url,
            type: 'GET'
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            }, {
                data: 'nm_fp_device',
                searchable: false,
                orderable: false
            }, {
                data: 'sn',
                searchable: false,
                orderable: false
            }, {
                data: 'status',
                searchable: false,
                orderable: false
            }, {
                data: 'ip_address_wan',
                searchable: false,
                orderable: false
            }, {
                data: 'ip_address_lan',
                searchable: false,
                orderable: false
            }, {
                data: 'port',
                searchable: false,
                orderable: false
            }, {
                data: 'updated_at',
                searchable: false,
                orderable: false
            },
            {
                data: 'last_data',
                searchable: false,
                orderable: false
            },
            {
                data: 'clear_log',
                searchable: false,
                orderable: false
            }, {
                data: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<a class="btn btn-success" target="_blank" href="' + base_url +
                        '/iclock/manual-get-data-realtime?SN=' + data.sn + '">' +
                        '    <i class="material-icons">system_update_alt</i><span>Tarik Data</span></a>' +
                        '</a> ' +
                        '<a class="btn btn-success" target="_blank" href="' + base_url +
                        '/iclock/manual-get-data?sn=' + data.sn + '">' +
                        '    <i class="material-icons">system_update_alt</i><span>Sync</span></a>' +
                        '</a> ' +
                        '<a class="btn btn-danger" target="_blank" href="' + base_url +
                        '/iclock/clear-log-data?SN=' + data.sn + '">' +
                        '    <i class="material-icons">system_update_alt</i><span>Clear Log Dat</span></a>' +
                        '</a> ';
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
