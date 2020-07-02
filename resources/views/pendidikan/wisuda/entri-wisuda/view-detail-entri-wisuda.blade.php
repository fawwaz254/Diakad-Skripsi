<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#wisuda/entri-wisuda')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>ENTRI DATA WISUDA @if(is_null($data_periode_wisuda))  @else {{$data_periode_wisuda->nm_periode_wisuda}} SEMESTER {{$data_periode_wisuda->tahun_ajaran}} {{$data_periode_wisuda->nm_semester}} @endif</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Periode Wisuda</th>
                                        <th>Semester</th>
                                        <th>NIS</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th>Tgl Pengajuan Wisuda</th>
                                        <th>Biodata</th>
                                        <th>Status Lab</th>
                                        <th>Status Perpus</th>
                                        <th>Status Ijasah</th>
                                        <th>Nomor SK Kelulusan</th>
                                        <th>Tgl SK Kelulusan</th>
                                        <th>Nomor Ijasah</th>
                                        <th>Tgl Kelulusan</th>
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

    var id_periode_wisuda = {!! json_encode($id_periode_wisuda) !!};
    var id_kelas = {!! json_encode($id_kelas) !!};

    var modul_url       = 'wisuda';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'entri-wisuda/datatables/' + id_periode_wisuda + '/' + id_kelas;
    var input_url        = role_url + '#' + modul_url + '/' + 'entri-wisuda/input';


    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        lengthMenu: [[50, 100, -1], [50, 100, "All"]],
        ajax: {
            url: datatable_url,
            type: 'GET'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'nm_periode_wisuda', name: 'nm_periode_wisuda' },
            { data: 'semester', name: 'semester' },
            { data: 'nis_siswa', name: 'nis_siswa'},
            { data: 'nm_pengguna', name: 'nm_pengguna'},
            { data: 'nm_kelas', name: 'nm_kelas'},
            { data: 'tgl_pengajuan_wisuda', name: 'tgl_pengajuan_wisuda'},
            { data: 'status_biodata', name: 'status_biodata'},
            { data: 'status_lab', name: 'status_lab'},
            { data: 'status_perpus', name: 'status_perpus'},
            { data: 'status_ijasah', name: 'status_ijasah'},
            { data: 'nomor_sk_kelulusan', name: 'nomor_sk_kelulusan'},
            { data: 'tgl_sk_kelulusan', name: 'tgl_sk_kelulusan'},
            { data: 'nomor_ijasah', name: 'nomor_ijasah'},
            { data: 'tgl_kelulusan', name: 'tgl_kelulusan'},
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    if(data.id != null) {
                        if(data.status_wisuda == 2) {
                            return '<a>LULUS</a>';
                        }
                        else if(data.status_wisuda == 1) {
                            return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ input_url + '/' + data.id + '/' + id_periode_wisuda + '/' + id_kelas +'">'+
                            '    <i class="material-icons">input</i>'+
                            '</a>';
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