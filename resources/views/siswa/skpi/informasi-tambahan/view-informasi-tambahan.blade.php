<div class="container-fluid">
    <div class="block-header">
        <h2>
            <a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#skpi/informasi_tambahan/add')}}"><i class="material-icons">note_add</i><span>Tambah Informasi Tambahan</span></a>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>Data Informasi Tambahan</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Informasi Tambahan</th>
                                        <th>Nama</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Keterangan</th> 
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
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'informasi_tambahan/datatables';
    var edit_url        = role_url + '#' + modul_url + '/' + 'informasi_tambahan/edit';
    var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + 'data-prestasi-siswa/action/delete';

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
            { data: 'jenis_informasi_tambahan', name: 'jenis_informasi_tambahan' },
            { data: 'nm_informasi_tambahan', name: 'nm_informasi_tambahan' },
            { data: 'nm_informasi_tambahan_eng', name: 'nm_informasi_tambahan_eng' },
            { data: 'keterangan_status', name: 'keterangan_status', searchable: false, orderable: false,
                render:function(data){
                    return `<span class="badge bg-`+data.color+`">`+data.status+`</span>`
                }
            },
            { data: 'keterangan', name: 'keterangan' },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
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
        ],
        columnDefs: [
            { className: 'text-center', targets: [5] },
        ]
    });

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();
</script>
