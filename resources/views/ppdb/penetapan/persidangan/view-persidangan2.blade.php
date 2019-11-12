<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/'.Request::segment(3).'/post-view-persidangan')}}">
                    {{csrf_field()}}
                    <div class="header bg-lime">
                        <h2>JADWAL PERSIDANGAN</h2>
                    </div>
                    <h2 class="card-inside-title">
                            Tahun <small><b>* Tahun Penetapan</b></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="tahun_penetapan">
                                @foreach($data_tahun_penetapan as $data)
                                    <option value="{{$data->tgl_penetapan}}">{{$data->tgl_penetapan}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>  

                    <button type="submit" class="btn btn-primary waves-effect" ><i class="material-icons"></i><span>View</span></button>
                </form>                     
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
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'data-penetapan/datatables';
    var edit_url        = role_url + '#' + modul_url + '/' + 'persidangan/edit';
    var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-penetapan/delete';

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
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ edit_url + '/' + data.id +'">'+
                    '    <i class="material-icons">lihat</i>'+
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
            var start = this.page.info().page * 10;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();
</script>