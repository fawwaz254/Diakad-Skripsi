<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    <div class="header">
                        <h2>Data Laporan Wali Kelas</h2>
                    </div>
                    <div class="body">
                       <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Role</th>
                                        <th>Semester</th>
                                        <th>Bulan</th>
                                        <th>Status</th>
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

@include('scriptjs')

<script type="text/javascript">
    
    var modul_url       = 'laporan';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'wali-kelas/datatables';
    var detail_url        = role_url + '#' + modul_url + '/' + 'wali-kelas/detail';

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
            { data: 'role', name: 'role' },
            { data: 'semester', name: 'semester' },
            { data: 'bulan', name: 'bulan' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-success btn-circle waves-effect waves-circle waves-float" href="'+ detail_url + '/' + data.id +'">'+
                    '    <i class="material-icons">visibility</i>'+
                    '</a> ';
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