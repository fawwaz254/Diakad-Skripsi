<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>Data Approved Prestasi Siswa</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Prestasi</th>
                                        <th>Kegiatan</th>
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
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var modul_url       = '{{Request::segment(2)}}';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'approve-prestasi-siswa/datatables';
    var detail_url        = role_url + '#' + modul_url + '/' + 'approve-prestasi-siswa';

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
            { data: 'nm_c_siswa', name: 'nm_c_siswa' },
            { data: 'prestasi', name: 'prestasi', searchable: false, orderable: false,
                render:function(data){
                    return `<span class="badge bg-pink">`+data.prestasi_siswa_not_approved+` belum diapprove</span> <span class="badge bg-teal">`+data.prestasi_siswa_approved+` sudah diapprove</span>`
                }
            },
            { data: 'kegiatan', name: 'kegiatan', searchable: false, orderable: false,
                 render:function(data){
                    return `<span class="badge bg-pink">`+data.kegiatan_siswa_not_approved+` belum diapprove</span> <span class="badge bg-teal">`+data.kegiatan_siswa_approved+` sudah diapprove</span>`
                }
            },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float text-center" href="'+ detail_url + '/' + data.id +'">'+
                    '    <i class="material-icons">edit</i>'+
                    '</a> '}
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
