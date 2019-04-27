<div class="container-fluid">
    <div class="block-header">
        <h2>
            <a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#aktivitas-semester/monitoring-kelas')}}">
                <i class="material-icons">backspace</i><span>Kembali</span>
            </a>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header bg-light-5green">
                        <h2>Monitoring Kapasitas Kelas</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nama Mata Ajar</th>
                                        <th>Jam KBM</th>
                                        <th>Kelas</th>
                                        <th>Hari</th>
                                        <th>Jam</th>
                                        <th>Kapasitas</th>
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
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'monitoring-kelas/datatables/' + id_semester;
    var detail_url        = role_url + '#' + modul_url + '/' + 'monitoring-kelas/view-daftar-siswa';
    
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
            { data: 'kd_mata_pelajaran', name: 'kd_mata_pelajaran' },
            { data: 'nm_mata_pelajaran', name: 'nm_mata_pelajaran' },
            { data: 'kredit_semester', name: 'kredit_semester' },
            { data: 'nm_kelas', name: 'nm_kelas' },
            { data: 'nm_jadwal_hari', name: 'nm_jadwal_hari' },
            { data: 'nm_jadwal_jam', name: 'nm_jadwal_jam' },
            { data: 'kapasitas_ruangan', name: 'kapasitas_ruangan' },
            { data: 'jml_siswa', name: 'jml_siswa' },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ detail_url + '/' + data.id +'">'+
                    '    <i class="material-icons">remove_red_eye</i>';
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