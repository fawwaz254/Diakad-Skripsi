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
                                    Jurusan
                                </h2>
                                <select class="form-control show-tick" name="id_jurusan" id="jurusan"> 
                                    @if($id_jurusan == "0")
                                        <option value="0" selected>-- Semua --</option>
                                        @foreach($jurusan as $jurusan)
                                            <option value="{{$jurusan->id_jurusan}}">{{$jurusan->nm_jurusan}}</option>
                                        @endforeach
                                    @else
                                        <option value="0">-- Semua --</option>
                                        @foreach($jurusan as $jurusan)
                                            @if($jurusan->id_jurusan == $id_jurusan)
                                                <option value="{{$jurusan->id_jurusan}}" selected >{{$jurusan->nm_jurusan}}</option>
                                            @else
                                                <option value="{{$jurusan->id_jurusan}}">{{$jurusan->nm_jurusan}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Kelas
                                </h2>
                                <select class="form-control show-tick" name="id_kelas" id="kelas">
                                    @if($id_kelas == "0")
                                        <option value="0">-- Semua --</option>
                                        @foreach($kelas as $kelas)
                                            <option value="{{$kelas->id_kelas}}">{{$kelas->nm_kelas}}</option>
                                        @endforeach
                                    @else
                                        <option value="0">-- Semua --</option>
                                        @foreach($kelas as $kelas)
                                            @if($kelas->id_kelas == $id_kelas)
                                                <option value="{{$kelas->id_kelas}}" selected >{{$kelas->nm_kelas}}</option>
                                            @else
                                                <option value="{{$kelas->id_kelas}}">{{$kelas->nm_kelas}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Tahun Masuk
                                </h2>
                                <select class="form-control show-tick" name="thn_masuk_siswa" id="thn_masuk">
                                        @if($thn_masuk_siswa == "0")
                                            <option value="0" selected>-- Semua --</option>
                                            @foreach($thn_masuk_siswa_list as $tahun)
                                                <option value="{{$tahun->thn_masuk_siswa}}">{{$tahun->thn_masuk_siswa}}</option>
                                            @endforeach
                                        @else
                                            <option value="0">-- Semua --</option>
                                            @foreach($thn_masuk_siswa_list as $tahun)
                                                @if($thn_masuk_siswa == $tahun->thn_masuk_siswa)
                                                    <option value="{{$tahun->thn_masuk_siswa}}" selected>{{$tahun->thn_masuk_siswa}}</option>
                                                @else
                                                    <option value="{{$tahun->thn_masuk_siswa}}">{{$tahun->thn_masuk_siswa}}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Jalur
                                </h2>
                                <select class="form-control show-tick" name="id_jalur" id="jalur">
                                    @if($id_jalur == "0")
                                        <option value="0" selected>-- Semua --</option>
                                        @foreach($jalur as $jalur)
                                            <option value="{{$jalur->id_jalur}}">{{$jalur->nm_jalur}}</option>
                                        @endforeach
                                    @else
                                        <option value="0">-- Semua --</option>
                                        @foreach($jalur as $jalur)
                                            @if($jalur->id_jalur == $id_jalur)
                                                <option value="{{$jalur->id_jalur}}" selected >{{$jalur->nm_jalur}}</option>
                                            @else
                                                <option value="{{$jalur->id_jalur}}">{{$jalur->nm_jalur}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Status Siswa
                                </h2>
                                <select class="form-control show-tick" name="id_status_pengguna" id="status_siswa">
                                    @if($id_status_pengguna == "0")
                                        <option value="0">-- Semua --</option>
                                         @foreach($status_pengguna as $status_siswa)
                                            <option value="{{$status_siswa->id_status_pengguna}}">{{$status_siswa->nm_status_pengguna}}</option>
                                        @endforeach
                                    @else
                                        <option value="0">-- Semua --</option>
                                         @foreach($status_pengguna as $status_siswa)
                                            @if($status_siswa->id_status_pengguna == $id_status_pengguna)
                                                <option value="{{$status_siswa->id_status_pengguna}}" selected >{{$status_siswa->nm_status_pengguna}}</option>
                                            @else
                                                <option value="{{$status_siswa->id_status_pengguna}}">{{$status_siswa->nm_status_pengguna}}</option>
                                            @endif
                                        @endforeach
                                    @endif
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
                                <a class="btn btn-block bg-btn-submit waves-effect target-link" href="{{url(Request::segment(1).'#siswa/update-foto/batch')}}"><i class="material-icons">cloud_upload</i><span>Upload Batch Foto</span></a>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                            <thead>
                                <tr>
                                    <th>No. </th>
                                    <th>Foto</th>
                                    <th>NIS</th>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>Tahun Masuk</th>
                                    <th>Jurusan</th>
                                    <th>Kelas</th>
                                    <th>Status</th>
                                    <th>Jalur Masuk</th>
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

    var id_jurusan= {!! json_encode($id_jurusan) !!};
    var id_kelas = {!! json_encode($id_kelas) !!};
    var thn_masuk_siswa = {!! json_encode($thn_masuk_siswa) !!};
    var id_status_pengguna = {!! json_encode($id_status_pengguna) !!};
    var id_jalur = {!! json_encode($id_jalur) !!};

    var modul_url           = 'siswa';
    var edit_url          = role_url + '#' + modul_url + '/' + 'update-foto/upload';
    var datatable_url       = base_url + '/' + role_url + '/' + modul_url + '/' + 'update-foto/datatables/' + id_jurusan + '/' + id_kelas +'/' + thn_masuk_siswa+'/' + id_jalur+'/' + id_status_pengguna;

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
            { data: 'nis_siswa', name: 'nis_siswa' },
            { data: 'nisn_siswa', name: 'nisn_siswa' },
            { data: 'nm_pengguna', name: 'nm_pengguna' },
            { data: 'thn_masuk_siswa', name: 'thn_masuk_siswa' },
            { data: 'nm_jurusan', name: 'nm_jurusan' },
            { data: 'nm_kelas', name: 'nm_kelas' },
            { data: 'nm_status_pengguna', name: 'nm_status_pengguna' },
            { data: 'nm_jalur', name: 'nm_jalur' },
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
<script type="text/javascript">
    $(document).ready(function() {
        $('select').select();
    });

    var modul_url       = 'siswa';

    $('#jurusan').on('change', function(e){
    var id_jurusan = e.target.value;
        $.get(base_url + '/' + role_url + '/' + modul_url + '/' + 'data-siswa/get-kelas/' + id_jurusan,function(data) {
            console.log(data);
            $('#kelas').empty();


            $('#kelas').append($("<option>")
                .attr("value", 0)
                .text("-- Semua --")
            );
            $.each(data, function(index, kelasObj){
                $('#kelas').append($("<option>")
                    .attr("value", kelasObj.id_kelas)
                    .text(kelasObj.nm_kelas)
                );
            })

            $('select').select();
        });
    });
</script>