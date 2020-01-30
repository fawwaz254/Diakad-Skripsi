<style>
.dataTables_filter{
    display:none;
}
</style>
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
                        <div class="table-responsive" style="overflow-x: auto;">
                            <table class="table table-bordered table-striped table-hover dataTable display" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th class="search-filter">Nama Mata Ajar</th>
                                        <th class="search-filter">Kelas</th>
                                        <th class="search-filter">Pengampu</th>
                                        <th class="search-filter">Hari</th>
                                        <th class="search-filter">Ruangan</th>
                                        <th>Jam KBM</th>
                                        <th>Mulai</th>
                                        <th>Selesai</th>
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
    
    $('#primary_table thead th.search-filter').each( function () {
        var title = $(this).text();
        $(this).append( initDtInputSearch(title) );
    } );

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        // responsive: true,
        searchDelay: 250,
        // ordering: false,
        dom: 'Bfrtip',
        lengthMenu: dtLengButton,
        buttons: dtButtonConfig,
        ajax: {
            url: datatable_url,
            type: 'GET'
        },
        columns: [
            { data: 'index_table', defaultContent:'', searchable: false, orderable: false },
            { data: 'kelas_mp.mata_pelajaran.nm_mata_pelajaran' },
            { data: 'kelas_mp.kelas.nm_kelas' },
            { data: 'nm_pengampu', name: 'kelas_mp.pengampu_mp_utama.guru.pengguna.nm_pengguna' },
            { data: 'jadwal_hari.nm_jadwal_hari' },
            { data: 'ruangan.nm_ruangan' },
            { data: 'kelas_mp.mata_pelajaran.kredit_semester' },
            { data: 'jadwal_jam_mulai.nm_jadwal_jam', searchable: false, orderable: false },
            { data: 'jadwal_jam_selesai.nm_jadwal_jam', searchable: false, orderable: false },
            { data: 'ruangan.kapasitas_ruangan', searchable: false, orderable: false },
            { data: 'jml_siswa', name: 'jml_siswa', searchable: false, orderable: false },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ detail_url + '/' + data.id +'">'+
                    '    <i class="material-icons">remove_red_eye</i>';
                }
            }
        ],
        order: [[4, 'desc']]
    });

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
            primary_table.cell(cell).invalidate('dom');
        } );
    } ).draw();

    primary_table.columns().every( function () {
        var column = this;
 
        $( 'input', this.header() ).on( 'keyup change', function () {
            if(this.value.length <= 0){
                column.search( this.value ).draw();
            }else{
                if (  this.value.length > 3 && column.search() !== this.value ) {
                    column.search( this.value ).draw();
                }
            }
        });
    });
</script>