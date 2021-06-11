<div class="container-fluid">
    <div class="block-header">
        <!-- <h2><a class="btn bg-blue waves-effect target-link m-b-15" href="{{url(Request::segment(1).'#data-keuangan/biaya-sekolah/detail-biaya/')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2> -->
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#data-keuangan/biaya-sekolah/detail-biaya/detail-biaya-internal/add/'.Request::segment(6))}}"><i class="material-icons">note_add</i><span>Tambah Detail Biaya Internal</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    <div class="header">
                        <h2>DATA DETAIL BIAYA INTERNAL</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Biaya Internal</th>
                                        <th>Nama Detail Biaya Internal</th>
                                        <th>Besar Biaya</th>
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

<input type="hidden" id="id_d" value="{{Request::segment(6)}}">

<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var id_detail_biaya = $('#id_d').val();
    var modul_url       = 'data-keuangan';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'biaya-sekolah/detail-biaya/detail-biaya-internal/datatables/'+id_detail_biaya;
    var edit_url        = role_url + '#' + modul_url + '/' + 'biaya-sekolah/detail-biaya/detail-biaya-internal/edit';
    var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + 'biaya-sekolah/detail-biaya/detail-biaya-internal/action-detail-biaya-internal/'+id_detail_biaya+'/delete';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url,
            type: 'POST'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'nm_biaya_internal', name: 'kelompok_biaya_internal.nm_kelompok_biaya_internal' },
            { data: 'nm_detail_biaya_internal', name: 'detail_biaya_internal.nm_detail_biaya_internal' },
            { data: 'besar_biaya', name: 'detail_biaya_internal.besar_biaya' },
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