<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#sarpras/komplain-sarpras')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#sarpras/komplain-sarpras/ruangan-sarpras/add/'.$data_ruangan->id_ruangan)}}"><i class="material-icons">note_add</i><span>Tambah Komplain Sarpras</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header bg-blue">
                        <h2>KOMPLAIN SARPRAS RUANGAN ({{$data_ruangan->nm_ruangan}} - {{$data_ruangan->nm_jenis_ruangan}})</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Ruangan</th>
                                        <th>Nama Inventaris</th>
                                        <th>User Komplain</th>
                                        <th>Keterangan Komplain</th>
                                        <th>Status Urgent</th>
                                        <th>Status Perbaikan</th>
                                        <th>User Perbaikan</th>
                                        <th>Keterangan Perbaikan</th>
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
    var id_ruangan = {!! json_encode($data_ruangan->id_ruangan) !!};

    var modul_url       = 'sarpras';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'komplain-sarpras/ruangan-sarpras/datatables/' + id_ruangan;
    var edit_url        = role_url + '#' + modul_url + '/' + 'komplain-sarpras/ruangan-sarpras/edit/' + id_ruangan;
    var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-komplain-sarpras/delete-ruangan';

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
            { data: 'nm_ruangan', name: 'nm_ruangan' },
            { data: 'nm_inventaris_ruangan', name: 'nm_inventaris_ruangan' },
            { data: 'user_komplain', name: 'user_komplain'},
            { data: 'keterangan_komplain', name: 'keterangan_komplain'},
            { data: 'is_urgent', name: 'is_urgent'},
            { data: 'is_sudah_perbaikan', name: 'is_sudah_perbaikan'},
            { data: 'nm_pengguna_guru_sarpras', name: 'nm_pengguna_guru_sarpras'},
            { data: 'keterangan_perbaikan', name: 'keterangan_perbaikan'},
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    if(data.user_perbaikan == null) {
                        return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ edit_url + '/' + data.id +'">'+
                        '    <i class="material-icons">edit</i>'+
                        '</a>'+
                        '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\''+ delete_url +'\', this)" data-id="'+  data.id +'">'+
                        '    <i class="material-icons">delete_forever</i>'+
                        '</button>';
                    }
                    else {
                        return '<a>Sudah Perbaikan</a>';
                    }
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