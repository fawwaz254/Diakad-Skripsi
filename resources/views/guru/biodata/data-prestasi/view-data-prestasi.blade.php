<div class="container-fluid">
    <div class="block-header">
        <h2>
            <a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#'.Request::segment(2).'/data-prestasi/add')}}"><i class="material-icons">note_add</i><span>Tambah Prestasi</span></a>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>Data Prestasi</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Nama Prestasi</th>
                                        <th>Tingkat Prestasi</th>
                                        <th>Jenis Prestasi</th>
                                        <th>Jenis Lomba</th>
                                        <th>Peringkat</th>
                                        <th>Link Sertifikat</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                        <th>Lokasi</th>
                                        <th>Penyelenggara</th>
                                        <th>Tanggal</th>
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
    var role_url        = '{{Request::segment(1)}}';
    var modul_url       = '{{Request::segment(2)}}';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'data-prestasi/datatables';
    var edit_url        = role_url + '#' + modul_url + '/' + 'data-prestasi/edit';
    var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + 'data-prestasi/action/delete';

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
            { data: 'nm_pengguna', name: 'nm_pengguna' },
            { data: 'nm_prestasi', name: 'nm_prestasi' },
            { data: 'nm_tingkat_prestasi_siswa', name: 'nm_tingkat_prestasi_siswa' },
            { data: 'jenis_prestasi', name: 'jenis_prestasi' },
            { data: 'jenis_lomba', name: 'jenis_lomba' },
            { data: 'peringkat', name: 'peringkat' },
            { data: 'action', name: 'link_sertifikat', searchable: false, orderable: false,
                render:function(data){
                    return '<a class="btn btn-success btn-circle waves-effect waves-circle waves-float text-center" target="_blank" href="'+ data.link_sertifikat +'">'+
                    '    <i class="material-icons">link</i>'+
                    '</a>';
                }
            },
            { data: 'keterangan_status', name: 'keterangan_status', searchable: false, orderable: false,
                render:function(data){
                    return `<span class="badge bg-`+data.color+`">`+data.status+`</span>`
                }
            },
            { data: 'keterangan', name: 'keterangan' },
            { data: 'lokasi', name: 'lokasi' },
            { data: 'penyelenggara', name: 'penyelenggara' },
            { data: 'tanggal', name: 'tanggal' },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    if(role_url=='humas'){
                         return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float text-center" href="'+ edit_url + '/' + data.id +'">'+
                            '    <i class="material-icons">edit</i>'+
                            '</a> '+
                            '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\''+ delete_url +'\', this)" data-id="'+  data.id +'">'+
                            '    <i class="material-icons">delete_forever</i>'+
                            '</button> ';
                    }
                    else{
                        if(data.status==0){
                            return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float text-center" href="'+ edit_url + '/' + data.id +'">'+
                            '    <i class="material-icons">edit</i>'+
                            '</a> '+
                            '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\''+ delete_url +'\', this)" data-id="'+  data.id +'">'+
                            '    <i class="material-icons">delete_forever</i>'+
                            '</button> ';
                        }
                        else{
                            return '-';
                        }
                    }
                    
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
