<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>
                        AKUN TENDIK
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-view-tendik')}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Filter Role
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_role">
                                    <option value="" disabled selected >-- Pilih Role --</option>
                                    @foreach($data_role as $data)
                                        @if($id_role != null)
                                            @if($data->id_role == $id_role)
                                                <option value="{{$data->id_role}}" selected >{{$data->nm_role}} ({{$data->total_role}})</option>
                                            @else
                                                <option value="{{$data->id_role}}">{{$data->nm_role}} ({{$data->total_role}})</option>
                                            @endif
                                        @else
                                            <option value="{{$data->id_role}}">{{$data->nm_role}} ({{$data->total_role}})</option>
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

                @if($id_role != null)
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                            <thead>
                                <tr>
                                    <th>No. </th>
                                    <th>NIP</th>
                                    <th>Username</th>
                                    <th>Nama</th>
                                    <th>Role</th>
                                    <th>Unit Kerja</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var id_role = {!! json_encode($id_role) !!};

    var modul_url       = 'pengelolaan-akun';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'tendik/datatables/' + id_role ;


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
            { data: 'nip_staff', name: 'nip_staff' },
            { data: 'username', name: 'username' },
            { data: 'nm_pengguna', name: 'nm_pengguna' },
            { data: 'nm_role', name: 'nm_role' },
            { data: 'nm_unit_kerja' , name:'nm_unit_kerja'}
        ]
    });

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * 10;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();
</script>