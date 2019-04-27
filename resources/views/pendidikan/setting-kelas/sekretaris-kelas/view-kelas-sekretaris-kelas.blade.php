<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#setting-kelas/kelas')}}"><i class="material-icons">backspace</i><span>Kembali View Kelas</span></a></h2>
    </div>
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#setting-kelas/sekretaris-kelas')}}"><i class="material-icons">backspace</i><span>Kembali Pilih Kelas</span></a></h2>
    </div>
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#setting-kelas/sekretaris-kelas/add/'.$data_kelas->id_kelas)}}"><i class="material-icons">note_add</i><span>Tambah Sekretaris Kelas</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header bg-green">
                        <h2>DATA SEKRETARIS KELAS {{$data_kelas->nm_kelas}}</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Kelas</th>
                                        <th>Semester</th>
                                        <th>Nama Sekretaris</th>
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
    var id_kelas = {!! json_encode($data_kelas->id_kelas) !!};

    var modul_url       = 'setting-kelas';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'sekretaris-kelas/datatables/' + id_kelas;
    var edit_url        = role_url + '#' + modul_url + '/' + 'sekretaris-kelas/edit';
    var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-sekretaris-kelas/delete';

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
            { data: 'nm_kelas', name: 'nm_kelas' },
            { data: 'semester', name: 'semester'},
            { data: 'nm_sekretaris', name: 'nm_sekretaris' },
            { data: 'status_aktif', name: 'status_aktif'},
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ edit_url + '/' + data.id_kelas + '/' + data.id_semester + '/' + data.id +'">'+
                    '    <i class="material-icons">edit</i>'+
                    '</a>'+
                    '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\''+ delete_url + '\', this)" data-id="'+  data.id +'">'+
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