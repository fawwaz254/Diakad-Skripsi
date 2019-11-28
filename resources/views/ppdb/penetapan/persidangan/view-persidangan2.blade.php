<div class="container-fluid">
    <!-- <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#penetapan/data-penetapan/add/')}}"><i class="material-icons">note_add</i><span>Tambah Penetapan</span></a></h2>
    </div> -->
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#penetapan/persidangan')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}                   
                    <div class="header bg-lime">
                        <h2>JADWAL PERSIDANGAN TAHUN {{$tahun}}</h2>

                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Persidangan Penetapan</th>
                                        <th>Sidang ke</th>                    
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
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'persidangan/datatables/{{$tahun}}';
    var edit_url        = role_url + '#' + modul_url + '/' + 'persidangan/view-persidangan-gelombang';
    var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-persidangan/delete';

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
            { data: 'nm_penetapan', name: 'nm_penetapan' },
            { data: 'periode', name: 'periode'},
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn " href="'+ edit_url + '/' + data.id +'">'+
                    '    <th>lihat</th>'+
                    '';
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