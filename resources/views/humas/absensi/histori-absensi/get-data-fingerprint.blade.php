<style>
    .dataTables_length,
    .dataTables_filter {
        display: none;
    }
</style>
<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-grey waves-effect target-link "
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3)) }}"><i
                    class="material-icons">arrow_back</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>List Data Terbaru</h2>
                    <br>
                    <p id="sn">SN Finger Berhasil di dapat :</p>
                    <h2 style="position: absolute;
                    top: 0;
                    right: 0;
                    margin: 20px;"
                        id="status">
                    </h2>

                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
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
    // var loops = 0;

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
        // loops++;
        $('#status').html('Proses : Get Data -> Sync Data -> Done');
        $.ajax({
            type: "POST",
            url: get_url,
            success: function(response) {
                $('#sn').html('SN Finger Berhasil di dapat : <br>' + response);
                syncData();
            }
        });
    }

    function syncData() {
        // loops++;
        $('#status').html('Proses : Sync Data -> Done');
        $.ajax({
            type: "POST",
            url: sync_url,
            success: function(response) {
                $('#status').html('Proses : Done');
                primary_table.ajax.reload(null, false);
                // getData();
            }
        });
    }

    $(document).ready(function() {
        getData();
    });
</script>
