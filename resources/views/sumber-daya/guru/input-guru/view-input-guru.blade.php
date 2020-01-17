<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#guru/input-guru/add')}}"><i class="material-icons">note_add</i><span>Input Guru Baru</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>DATA GURU</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Guru</th>
                                        <th>NIP</th>
                                        <!-- <th>Jabatan</th> -->
                                        <th>Unit Kerja</th>
                                        <th>Status Aktif</th>
                                        <th>Jumlah Mengajar Smt Aktif</th>
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
    var modul_url       = 'guru';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'input-guru/datatables';
    var edit_url        = role_url + '#' + modul_url + '/' + 'input-guru/edit';
    var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-input-guru/delete';

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
            { data: 'nm_pengguna', name: 'pengguna.nm_pengguna' },
            { data: 'nip_guru', name: 'guru.nip_guru' },
            /*{ data: 'nm_jabatan_pegawai', name: 'nm_jabatan_pegawai' },*/
            { data: 'nm_unit_kerja', name: 'unit_kerja.nm_unit_kerja' },
            { data: 'nm_status_pengguna', name: 'status_pengguna.nm_status_pengguna' },
            { data: 'jml_mengajar_semester_aktif', name: 'jml_mengajar_semester_aktif', searchable: false, orderable: false},
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
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();
</script>