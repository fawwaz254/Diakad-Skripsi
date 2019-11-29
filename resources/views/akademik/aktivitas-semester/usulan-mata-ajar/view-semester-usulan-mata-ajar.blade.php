<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#aktivitas-semester/usulan-mata-ajar')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#aktivitas-semester/usulan-mata-ajar/tambah-mata-ajar/'.$id)}}"><i class="material-icons">note_add</i><span>Tambah Usulan Mata Ajar</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>Usulan Mata Ajar</h2>
                        <br>
                        <h2 style="font-size: 18px">Semester : {{$semester->tahun_ajaran}} ({{$semester->nm_semester}})</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nama Mata Ajar</th>
                                        <th>Jenis Mapel</th>
                                        <th>Tingkat</th>
                                        <th>Kelas</th>
                                        <th>Jadwal Hari</th>
                                        <th>Jadwal Jam</th>
                                        <th>Pengampu</th>
                                        <th>Terisi</th>
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
    var id_semester= {!! json_encode($id) !!};

    var modul_url       = 'aktivitas-semester';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'usulan-mata-ajar/datatables/' + id_semester;
    var edit_url        = role_url + '#' + modul_url + '/' + 'usulan-mata-ajar/edit';
    var copy_url        = role_url + '#' + modul_url + '/' + 'usulan-mata-ajar/copy';
    var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-usulan-mata-ajar/delete';

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
            { data: 'kd_mata_pelajaran', name: 'mata_pelajaran.kd_mata_pelajaran' },
            { data: 'nm_mata_pelajaran', name: 'mata_pelajaran.nm_mata_pelajaran' },
            { data: 'nm_jenis_mata_pelajaran', name: 'jenis_mata_pelajaran.nm_jenis_mata_pelajaran' },
            { data: 'tingkat_semester', name: 'mata_pelajaran.tingkat_semester' },
            { data: 'nm_kelas', name: 'kelas.nm_kelas' },
            { data: 'jml_jadwal', name: 'jml_jadwal', searchable: false, orderable: false },
            { data: 'jml_jadwal_jam', name: 'jml_jadwal_jam', searchable: false, orderable: false },
            { data: 'jml_pengampu', name: 'jml_pengampu', searchable: false, orderable: false },
            { data: 'jml_siswa', name: 'jml_siswa', searchable: false, orderable: false },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ edit_url + '/' + data.id +'">'+
                    '    <i class="material-icons">edit</i>'+
                    '</a> '+
                    '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ copy_url + '/' + data.id +'">'+
                    '    <i class="material-icons">file_copy</i>'+
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
