<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#presensi/cetak-presensi-kbm')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header bg-light-5green">
                    <h2>Cetak Presensi KBM</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Hari</th>
                                    <th>Kelas</th>
                                    <th>Nama Mata Ajar</th>
                                    <th>Jam</th>
                                    <th>Status</th>
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
@include('scriptjs')
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var id_semester= {!! json_encode($id) !!};

    var modul_url       = 'presensi';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'cetak-presensi-kbm/datatables/' + id_semester;
    var print_url        = base_url + '/' + role_url + '/' + modul_url + '/' + 'cetak-presensi-kbm/print';
    
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
            { data: 'nm_jadwal_hari', name: 'nm_jadwal_hari' },
            { data: 'nm_kelas', name: 'nm_kelas' },
            { data: 'nm_mata_pelajaran', name: 'nm_mata_pelajaran' },
            { data: 'nm_jadwal_jam', name: 'nm_jadwal_jam' },
            { data: 'status_plotting', name: 'status_plotting' },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return `<a class="btn btn-info btn-circle waves-effect waves-circle waves-float" target="_blank" style="margin-right:10px" href="${print_url}/${data.id}"><i class="material-icons">picture_as_pdf</i> 
                    
                    
                    
                    <a class="btn btn-success btn-circle waves-effect waves-circle waves-float" target="_blank" href="${base_url}/${role_url}/${modul_url}/cetak-rekap-presensi-kbm/print/${data.id}"><i class="material-icons">print</i>`;
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