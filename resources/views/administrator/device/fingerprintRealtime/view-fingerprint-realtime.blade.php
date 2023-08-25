<style>
    .dataTables_length,
    .dataTables_filter {
        display: none;
    }
</style>
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>DATA Presensi Fingerprint Realtime</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIS/NIP</th>
                                    <th>Nama Siswa</th>
                                    <th>Foto</th>
                                    <th>Check-In</th>
                                    <th>Check-Out</th>
                                    <th>Status</th>
                                    <th>Updated Time</th>
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
    var get_url = base_url + '/' + role_url + '/' + modul_url + '/' + menu_url + '/getData';
    var sync_url = base_url + '/' + role_url + '/' + modul_url + '/' + menu_url + '/syncData';


    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url,
            type: 'GET'
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            }, {
                data: 'pengguna.username',
                searchable: false,
                orderable: false
            },
            {
                data: 'pengguna.nm_pengguna',
                searchable: false,
                orderable: false
            },
            {
                data: 'pengguna.path_foto_pengguna',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<img width="75" src=' + data + '>';
                }
            }

            , {
                data: 'check_in',
                searchable: false,
                orderable: false
            },
            {
                data: 'check_out',
                searchable: false,
                orderable: false
            },
            {
                data: 'status',
                searchable: false,
                orderable: false
            },
            {
                data: 'updated_at',
                searchable: false,
                orderable: false
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

    function getData() {
        console.log("get data");
        $.ajax({
            type: "POST",
            url: get_url,
            success: function(response) {
                console.log(response);
            }
        });
    }

    function syncData() {
        console.log("sync data");
        $.ajax({
            type: "POST",
            url: sync_url,
            success: function(response) {
                primary_table.ajax.reload(null, false);
                // if (response) {
                //     primary_table.ajax.reload(null, false);
                //     console.log("Ada data baru");
                // } else {
                //     primary_table.ajax.reload(null, false);
                //     console.log("Tidak ada data baru");
                // }
            }
        });
    }

    setInterval(getData, 1 * 60 * 1000);
    setInterval(syncData, 1 * 60 * 1000);
</script>
