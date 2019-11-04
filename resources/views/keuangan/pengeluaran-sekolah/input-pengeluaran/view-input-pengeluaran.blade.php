<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#pengeluaran-sekolah/input-pengeluaran/add')}}"><i class="material-icons">note_add</i><span>Tambah Pengeluaran</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header bg-amber">
                        <h2>DATA PENGELUARAN</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Semester</th>
                                        <th>Nama Sub-Kategori</th>
                                        <th>Staff Input</th>
                                        <th>Tanggal Pengeluaran</th>
                                        <th>Besar Pengeluaran</th>
                                        <th>Upload File</th>
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
    var modul_url       = 'pengeluaran-sekolah';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'input-pengeluaran/datatables';
    var edit_url        = role_url + '#' + modul_url + '/' + 'input-pengeluaran/edit';
    var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-input-pengeluaran/delete';

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
            { data: 'semester', name: 'semester.tahun_ajaran' },
            { data: 'nm_pengeluaran_biaya_subkategori', name: 'pengeluaran_biaya_subkategori.nm_pengeluaran_biaya_subkategori' },
            { data: 'nm_pengguna', name: 'pengguna.nm_pengguna' },
            { data: 'tgl_pengeluaran_biaya', name: 'pengeluaran_biaya.tgl_pengeluaran_biaya' },
            { data: 'besar_pengeluaran_biaya', name: 'pengeluaran_biaya.besar_pengeluaran_biaya' },
            { data: 'is_upload_file', name: 'is_upload_file', searchable: false, orderable: false },
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