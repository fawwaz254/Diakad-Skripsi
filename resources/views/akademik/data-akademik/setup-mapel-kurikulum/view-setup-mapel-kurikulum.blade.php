<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-light-green">
                    <h2>
                        Setup Mapel Kurikulum
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-cari-kurikulum')}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Kurikulum
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_kurikulum">
                                    @foreach($kurikulum as $data)
                                        @if($data->id_kurikulum == $id_kurikulum)
                                            <option value="{{$data->id_kurikulum}}" selected>{{$data->nm_kurikulum}}</option>
                                        @else
                                            <option value="{{$data->id_kurikulum}}">{{$data->nm_kurikulum}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
 @if($id_kurikulum != null)
<div class="container-fluid">
    <div class="row-clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body">
                    <div class="block-header">
                        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#data-akademik/setup-mp-kurikulum/add/'.$id_kurikulum)}}"><i class="material-icons">note_add</i><span>Tambah Mata Pelajaran</span></a></h2>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                        {{csrf_field()}}
                            <thead>
                                <tr>
                                    <th>No. </th>
                                    <th>Jenis Mata Pelajaran</th>
                                    <th>Mata Pelajaran</th>
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
@endif
@include('scriptjs')
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var id_kurikulum = {!! json_encode($id_kurikulum) !!};

    var modul_url       = 'data-akademik';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'setup-mp-kurikulum/datatables/' + id_kurikulum ;
    // var detail_url        = role_url + '#' + modul_url + '/' + 'cari-siswa/view-detail-siswa';
    var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-setup-mp-kurikulum/delete';


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
            { data: 'jenis_mata_pelajaran', name: 'jenis_mata_pelajaran' },
            { data: 'mata_pelajaran', name: 'mata_pelajaran' },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\''+ delete_url +'\', this)" data-id="'+  data.id +'">'+
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
