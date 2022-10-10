 <div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>Data Siswa</h2> 
                    </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-view-update-foto')}}">
                            {{csrf_field()}}
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Unit Kerja
                                </h2>
                                <select class="form-control show-tick" name="unit_kerja" id="unit_kerja"> 
                                    @if($unit_kerja == "0")
                                        <option value="0" selected>-- Semua --</option>
                                        @foreach($list_unit_kerja as $u)
                                            <option value="{{$u->id_unit_kerja}}">{{$u->nm_unit_kerja}}</option>
                                        @endforeach
                                    @else
                                        <option value="0">-- Semua --</option>
                                        @foreach($list_unit_kerja as $u)
                                            @if($u->id_unit_kerja == $unit_kerja)
                                                <option value="{{$u->id_unit_kerja}}" selected >{{$u->nm_unit_kerja}}</option>
                                            @else
                                                <option value="{{$u->id_unit_kerjaa}}">{{$u->nm_unit_kerja}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                {{-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> --}}

                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Role
                                </h2>
                                <select class="form-control show-tick" name="status_join_table" id="status_join_table">
                                        <option value="0" @if($status_join_table == 0) selected @endif>-- Semua --</option>
                                        <option value="1" @if($status_join_table == 1) selected @endif>Tendik</option>
                                        <option value="2" @if($status_join_table == 2) selected @endif>Guru</option>
                                  
                                </select>
                            </div>
                        </div>
                       
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Tampilkan</span></button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <a class="btn btn-block bg-btn-submit waves-effect target-link" href="{{url(Request::segment(1).'#data-sumber-daya/update-foto/batch')}}"><i class="material-icons">cloud_upload</i><span>Upload Batch Foto</span></a>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                            <thead>
                                <tr>
                                    <th>No. </th>
                                    <th>Foto</th>
                                    <th>NIS</th>
                                    {{-- <th>NISN</th> --}}
                                    <th>Nama</th>
                                    {{-- <th>Tahun Masuk</th> --}}
                                    {{-- <th>Jurusan</th> --}}
                                    {{-- <th>Unit Kerja</th> --}}
                                    {{-- <th>Status</th> --}}
                                    {{-- <th>Role</th> --}}
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
@include('scriptjs')
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];

    var unit_kerja= {!! json_encode($unit_kerja) !!};
    var status_join_table = {!! json_encode($status_join_table) !!};


    var modul_url           = 'data-sumber-daya';
    var edit_url          = role_url + '#' + modul_url + '/' + 'update-foto/upload';
    var datatable_url       = base_url + '/' + role_url + '/' + modul_url + '/' + 'update-foto/datatables/' + unit_kerja + '/' + status_join_table;

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
            { data: 'path_foto_pengguna', searchable: false, orderable: false, 
                render: function(data){
                    return '<img width="75" src='+data+'>';
                }
            },
            { data: 'username', name: 'username' },
            // { data: 'nisn_siswa', name: 'nisn_siswa' },
            { data: 'nm_pengguna', name: 'nm_pengguna' },
            // { data: 'thn_masuk_siswa', name: 'thn_masuk_siswa' },
            // { data: 'nm_jurusan', name: 'nm_jurusan' },
            // { data: 'nm_kelas', name: 'nm_kelas' },
            // { data: 'nm_status_pengguna', name: 'nm_status_pengguna' },
            // { data: 'nm_jalur', name: 'nm_jalur' },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ edit_url + '/' + data.id +'">'+
                    '    <i class="material-icons">cloud_upload</i>'+
                    '</a> ';
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
