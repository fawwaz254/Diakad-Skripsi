<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#data-keuangan/detail-biaya/add')}}"><i class="material-icons">note_add</i><span>Tambah Detail Biaya</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>DATA DETAIL BIAYA</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Biaya Sekolah</th>
                                        <th>Jenis Biaya</th>
                                        <th>Nama Biaya</th>
                                        <th>Nama Biaya Internal</th>
                                        <th>Besar Biaya</th>
                                        <th>Validasi</th>
                                        <th>Keterangan</th>
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
    var modul_url       = 'data-keuangan';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'detail-biaya/datatables';
    var edit_url        = role_url + '#' + modul_url + '/' + 'detail-biaya/edit';
    var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-detail-biaya/delete';

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
            { data: 'biaya_sekolah', name: 'kelompok_biaya.nm_kelompok_biaya' },
            { data: 'jenis_biaya', name: 'jenis_detail_biaya.nm_jenis_detail_biaya' },
            { data: 'nm_biaya', name: 'biaya.nm_biaya' },
            { data: 'nm_biaya_internal', name: 'kelompok_biaya_internal.nm_kelompok_biaya_internal' },
            { data: 'besar_biaya', name: 'detail_biaya.besar_biaya' },
            { data: 'validasi_biaya', name: 'validasi_biaya', searchable: false, orderable: false },
            { data: 'keterangan_biaya', name: 'detail_biaya.keterangan_biaya' },
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