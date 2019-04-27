<div class="container-fluid">
    <div class="block-header">
        <h2>
            <a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#aktivitas-semester/input-nilai/')}}">
                <i class="material-icons">backspace</i><span>Kembali</span>
            </a>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header bg-light-green">
                        <h2>Daftar Mata Pelajaran</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode - Nama Mata Pelajaran</th>
                                        <th>Kelas</th>
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
    var id_pengguna = {!! json_encode($id_pengguna) !!}
    var id_semester = {!! json_encode($id_semester) !!}

    var modul_url       = 'aktivitas-semester';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'input-nilai/datatables-mapel/' + id_pengguna + '/' + id_semester;
    var edit_url        = role_url + '#' + modul_url + '/' + 'input-nilai/view-kelas/'  + id_pengguna + '/' + id_semester;
    var input_url        = role_url + '#' + modul_url + '/' + 'input-nilai/nilai-mapel/'  + id_pengguna + '/' + id_semester;

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
            { data: 'mata_pelajaran', name: 'mata_pelajaran' },
            { data: 'nm_kelas', name: 'nm_kelas' },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-block bg-blue waves-effect" href="'+ edit_url + '/' + data.id +'">'+
                    '    Setting Komponen Nilai'+
                    '</a><br><br>'+'<a class="target-link btn btn-block bg-blue waves-effect" href="'+ input_url + '/' + data.id +'">'+
                    '    Input Nilai'+
                    '</a>';
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