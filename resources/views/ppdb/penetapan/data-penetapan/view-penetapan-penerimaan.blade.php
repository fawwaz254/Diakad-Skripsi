<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#penetapan/data-penetapan')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#penetapan/data-penetapan/add-penetapan-penerimaan/'.$data_penetapan_penerimaan->id_penetapan)}}"><i class="material-icons">note_add</i><span>Tambah Penetapan Penerimaan</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header bg-lime">
                        <h2>DATA {{$data_penetapan_penerimaan->nm_penetapan}}  </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun</th>                       
                                        <th>Penerimaan</th>
                                        <th>Gelombang</th>                    
                                        <th>Semester</th>
                                        <th>Status </th>                    
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

    var modul_url       = 'penetapan';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'data-penetapan/datatables-penetapan-penerimaan/{{$data_penetapan_penerimaan->id_penetapan}}';
    var edit_url        = role_url + '#' + modul_url + '/' + 'data-penetapan/edit-penetapan-penerimaan';
    var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-penetapan-penerimaan/delete';

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
            { data: 'tahun_penerimaan', name: 'tahun_penerimaan'},
            { data: 'nm_penerimaan', name: 'nm_penerimaan'},
            { data: 'gelombang_penerimaan', name: 'gelombang_penerimaan'},
            { data: 'nm_semester_penerimaan', name: 'nm_semester_penerimaan'},
            { data: 'is_aktif', name: 'is_aktif'},
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="" href="">'+
                    '    <i class="material-icons"></i>'+
                    '</a> '
                    +
                    '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\''+ delete_url +'\', this)" data-id="'+  data.id +'">'+
                    '    <i class="material-icons">delete_forever</i>'+
                    '</button>'
                    ;
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