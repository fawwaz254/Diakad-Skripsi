<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#data-sumber-daya/jabatan-pegawai/add')}}"><i class="material-icons">note_add</i><span>Tambah Jabatan Pegawai</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header bg-pink">
                        <h2>DATA JABATAN PEGAWAI</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Jabatan Pegawai</th>
                                        <th>Deskripsi</th>
                                        <th>Tipe Jabatan Pegawai</th>
                                        <th>Kode Jabatan Pegawai</th>
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
    var modul_url       = 'data-sumber-daya';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'jabatan-pegawai/datatables';
    var edit_url        = role_url + '#' + modul_url + '/' + 'jabatan-pegawai/edit';
    var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-jabatan-pegawai/delete';

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
            { data: 'nm_jabatan_pegawai', name: 'nm_jabatan_pegawai' },
            { data: 'deskripsi_jabatan_pegawai', name: 'deskripsi_jabatan_pegawai' },
            { data: 'tipe_jabatan_pegawai', name: 'tipe_jabatan_pegawai' },
            { data: 'kode_jabatan_pegawai', name: 'kode_jabatan_pegawai' },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ edit_url + '/' + data.id +'">'+
                    '    <i class="material-icons">edit</i>'+
                    '</a> '+
                    '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\''+ delete_url +'\', this)" data-id="'+  data.id +'">'+
                    '    <i class="material-icons">delete_forever</i>'+
                    '</button>';
                }
            }
        ]
    });

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * 10;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();
</script>