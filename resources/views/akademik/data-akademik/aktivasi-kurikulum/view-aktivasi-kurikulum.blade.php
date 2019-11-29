<div class="container-fluid">
    <div class="block-header">

    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>AKTIVASI KURIKULUM</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jurusan</th>
                                        <th>Semester Mulai</th>
                                        <th>Nama Kurikulum</th>
                                        <th>Tahun Kurikulum</th>
                                        <th>Nomor SK</th>
                                        <th>Berlaku Mulai</th>
                                        <th>Berlaku Sampai</th>
                                        <th>Status Aktif</th>
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
</div>
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var modul_url = 'data-akademik';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'aktivasi-kurikulum/datatables';
    var edit_url        = role_url + '#' + modul_url + '/' + 'aktivasi-kurikulum/aktivasi';

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
            { data: 'nm_jurusan', name: 'nm_jurusan' },
            { data: 'semester_mulai', name: 'semester_mulai'},
            { data: 'nm_kurikulum', name: 'nm_kurikulum' },
            { data: 'tahun_kurikulum', name: 'tahun_kurikulum' },
            { data: 'nomor_sk_kurikulum', name: 'nomor_sk_kurikulum' },
            { data: 'berlaku_mulai', name: 'berlaku_mulai'},
            { data: 'berlaku_sampai', name: 'berlaku_sampai'},
            { data: 'status_aktif', name: 'status_aktif'},
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ edit_url + '/' + data.id +'">'+
                    '    <i class="material-icons">subscriptions</i>'+
                    '</a>';
                }
            }
        ]
    });

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();
</script>