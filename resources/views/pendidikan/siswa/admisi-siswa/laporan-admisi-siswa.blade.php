<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#data-kesiswaan/admisi-siswa/generate')}}"><i class="material-icons">backspace</i><span>GENERATE ADMISI</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        LAPORAN ADMISI SISWA PER KELAS
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-view-laporan-admisi-siswa')}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Semester
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_semester">
                                    <option value="" disabled selected >-- Pilih Semester --</option>
                                    @foreach($data_semester as $data)
                                        @if($id_semester != null)
                                            @if($data->id_semester == $id_semester)
                                                @if($data->is_aktif_semester == 1)
                                                    <option value="{{$data->id_semester}}" selected >{{$data->tahun_ajaran}} {{$data->nm_semester}} (Aktif)</option>
                                                @else
                                                    <option value="{{$data->id_semester}}" selected >{{$data->tahun_ajaran}} {{$data->nm_semester}}</option>
                                                @endif
                                            @else
                                                @if($data->is_aktif_semester == 1)
                                                    <option value="{{$data->id_semester}}" >{{$data->tahun_ajaran}} {{$data->nm_semester}} (Aktif)</option>
                                                @else
                                                    <option value="{{$data->id_semester}}" >{{$data->tahun_ajaran}} {{$data->nm_semester}}</option>
                                                @endif
                                            @endif
                                        @else
                                            @if($data->is_aktif_semester == 1)
                                                <option value="{{$data->id_semester}}" selected >{{$data->tahun_ajaran}} {{$data->nm_semester}} (Aktif)</option>
                                            @else
                                                <option value="{{$data->id_semester}}">{{$data->tahun_ajaran}} {{$data->nm_semester}}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kelas
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_kelas">
                                    <option value="" disabled selected >-- Pilih Kelas --</option>
                                    @foreach($data_kelas as $data)
                                        @if($id_kelas != null)
                                            @if($data->id_kelas == $id_kelas)
                                                <option value="{{$data->id_kelas}}" selected >{{$data->nm_kelas}} ({{$data->nm_jurusan}})</option>
                                            @else
                                                <option value="{{$data->id_kelas}}">{{$data->nm_kelas}} ({{$data->nm_jurusan}})</option>
                                            @endif
                                        @else
                                            <option value="{{$data->id_kelas}}">{{$data->nm_kelas}} ({{$data->nm_jurusan}})</option>
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
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div>
                    </form>
                </div>

                @if($id_semester != null and $id_kelas != null)
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                            <thead>
                                <tr>
                                    <th>No. </th>
                                    <th>NIS</th>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>Status</th>
                                    <th>Jalur Masuk</th>
                                    <th>Action</th>
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
    var id_semester = {!! json_encode($id_semester) !!};
    var id_kelas    = {!! json_encode($id_kelas) !!};

    var modul_url       = 'data-kesiswaan';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'admisi-siswa/datatables/' + id_semester + '/' + id_kelas ;
    var admisi_url      = role_url + '#' + modul_url + '/' + 'admisi-siswa/view-detail';


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
            { data: 'nis_siswa', name: 'nis_siswa' },
            { data: 'nisn_siswa', name: 'nisn_siswa' },
            { data: 'nm_pengguna', name: 'nm_pengguna' },
            { data: 'nm_status_pengguna', name: 'nm_status_pengguna' },
            { data: 'nm_jalur' , name:'nm_jalur'},
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    if(data.admisi != null) {
                        return '-';
                    }
                    else {
                        return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ admisi_url + '/' + data.id +'">'+
                        '    <i class="material-icons">person_add</i>'+
                        '</a>';
                    }
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