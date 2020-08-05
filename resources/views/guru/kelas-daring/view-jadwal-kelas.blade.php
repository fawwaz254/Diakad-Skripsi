<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#'.Request::segment(2).'/jadwal-kelas/add')}}"><i class="material-icons">note_add</i><span>Tambah Kelas Daring</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>JADWAL KELAS DARING {{$semester_aktif->tahun_ajaran}} {{strtoupper($semester_aktif->nm_semester)}}</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive" style="overflow-x: auto;">
                            <table class="table table-bordered table-striped table-hover dataTable display" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Kelas Daring</th>
                                        <th>Kelas</th>
                                        <th>Jadwal</th>
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

    var modul_url       = 'kelas-daring';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'jadwal-kelas/datatables';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        dom: 'Bfrtip',
        lengthMenu: dtLengButton,
        buttons: dtButtonConfig,
        ajax: {
            url: datatable_url,
            type: 'POST'
        },
        columns: [
            { data: 'index_table', defaultContent:'', searchable: false, orderable: false },
            { data: 'nm_kelas_mp_grup' },
            { data: 'kelas', searchable: false, orderable: false },
            { data: 'jadwal', searchable: false, orderable: false },
            { data: 'action', searchable: false, orderable: false }
        ]
    });

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
            primary_table.cell(cell).invalidate('dom');
        } );
    } ).draw();
</script>