<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#aktivitas-semester/hapus-plotting-mapel-siswa')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>Hapus Plotting Mapel Siswa</h2>
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
                                        <th>Jumlah Jadwal</th>
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
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'hapus-plotting-mapel-siswa/datatables/' + id_semester;
    var detail_url        = role_url + '#' + modul_url + '/' + 'hapus-plotting-mapel-siswa/view-detail-hapus-plotting-mapel-siswa';

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
            { data: 'mata_pelajaran.kd_mata_pelajaran' },
            { data: 'mata_pelajaran.nm_mata_pelajaran' },
            { data: 'mata_pelajaran.jenis_mata_pelajaran.nm_jenis_mata_pelajaran' },
            { data: 'mata_pelajaran.tingkat_semester' },
            { data: 'kelas.nm_kelas' },
            { data: 'jml_jadwal', searchable: false, orderable: false },
            { data: 'jml_siswa', searchable: false, orderable: false },
            { data: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ detail_url + '/' + data.id +'">'+
                    '    <i class="material-icons">remove_red_eye</i>'+
                    '</a> ';
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