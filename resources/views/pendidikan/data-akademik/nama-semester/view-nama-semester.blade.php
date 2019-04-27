<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#data-akademik/nama-semester/add')}}"><i class="material-icons">note_add</i><span>Tambah Nama Semester</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header bg-green">
                        <h2>DATA NAMA SEMESTER</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun Ajaran</th>
                                        <th>Nama Semester</th>
                                        <th>Tahun Akademik</th>
                                        <th>Kode Semeester</th>
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
    var modul_url       = 'data-akademik';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'nama-semester/datatables';
    var edit_url        = role_url + '#' + modul_url + '/' + 'nama-semester/edit';
    var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-nama-semester/delete';

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
            { data: 'tahun_ajaran', name: 'tahun_ajaran' },
            { data: 'nm_semester', name: 'nm_semester' },
            { data: 'thn_akademik_semester', name: 'thn_akademik_semester' },
            { data: 'kode_semester', name: 'kode_semester' },
            { data: 'status_aktif', name: 'status_aktif'},
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