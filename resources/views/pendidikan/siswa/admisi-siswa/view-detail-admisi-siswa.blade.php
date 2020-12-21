<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#data-kesiswaan/admisi-siswa/generate')}}"><i class="material-icons">note_add</i><span>GENERATE ADMISI</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        ADMISI SISWA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-view-admisi-siswa')}}">
                            {{csrf_field()}}
                        <h2 class="card-inside-title">
                            NIS/NISN atau Nama Siswa
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nis_nama_siswa" aria-invalid="true" value="{{$nis_nama_siswa}}">
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
            <div class="card">
                <div class="body">
                    <div class="table-responsive">
                        <form id="form-validation1" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-admisi-siswa')}}">
                            {{csrf_field()}}
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                                <tr>
                                    <th colspan="2" style="text-align: center;">BIODATA SISWA</th>
                                </tr>
                                <tr>
                                    <td style="width: 50%">NIS</td>
                                    <td style="width: 50%">{{$siswa->nis_siswa}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 50%">NISN</td>
                                    <td style="width: 50%">{{$siswa->nisn_siswa}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 50%">Nama Siswa</td>
                                    <td style="width: 50%">{{$siswa->nm_pengguna}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 50%">Kelas</td>
                                    <td style="width: 50%">{{$siswa->nm_kelas}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 50%">Status Akademik Saat Ini</td>
                                    <td style="width: 50%">{{$siswa->nm_status_pengguna}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 50%">Status Akademik Baru</td>
                                    <td style="width: 50%">
                                        <select class="form-control show-tick" name="id_status_pengguna" id="id_status_pengguna">
                                            @if($admisi)
                                                <option value="" disabled >-- Pilih Status Akademik Baru --</option>
                                            @else
                                                <option value="" disabled selected >-- Pilih Status Akademik Baru --</option>
                                            @endif
                                            @foreach($status as $status)
                                                @if($admisi)
                                                    @if($status->id_status_pengguna == $admisi->id_status_pengguna)
                                                        <option value="{{$status->id_status_pengguna}}" selected >{{$status->nm_status_pengguna}}</option>
                                                    @else
                                                        <option value="{{$status->id_status_pengguna}}">{{$status->nm_status_pengguna}}</option>
                                                    @endif
                                                @else
                                                    <option value="{{$status->id_status_pengguna}}">{{$status->nm_status_pengguna}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50%">
                                        Tanggal Keluar<br>
                                        <small><i>*Hanya Untuk Status Akademik Keluar</i></small>
                                    </td>
                                    <td style="width: 50%">
                                        <input type="text" class="datepicker form-control" name="tgl_keluar" id="tgl_keluar" aria-invalid="true" @if(!empty($admisi->tgl_keluar)) value="{{date_format(date_create($admisi->tgl_keluar), "d F Y")}}" @endif>
                                        <input type="hidden" class="form-control" name="nis_nama_siswa" aria-invalid="true" value="{{$nis_nama_siswa}}">
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50%">Keterangan</td>
                                    <td style="width: 50%">
                                        <textarea class="form-control" style="width: 100%; height: 250px" name="keterangan_admisi" id="keterangan_admisi"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50%">Semester</td>
                                    <td style="width: 50%">
                                        <select class="form-control show-tick" name="id_semester" id="id_semester">
                                            <option value="" disabled >-- Pilih Semester --</option>
                                            @foreach($semester as $semester)
                                                @if($semester->is_aktif_semester == 1)
                                                    <option value="{{$semester->id_semester}}" selected >{{$semester->nm_semester}} {{$semester->tahun_ajaran}} (Aktif)</option>
                                                @else
                                                    <option value="{{$semester->id_semester}}">{{$semester->nm_semester}} {{$semester->tahun_ajaran}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: center;">
                                        <input type="hidden" class="form-control" name="id_siswa" id="id_siswa" value="{{$siswa->id_siswa}}" aria-invalid="true">
                                        <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>   
</div>
@include('scriptjs')
<script>
    $(function(){
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'DD MMMM YYYY',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: false
        });
    });
</script>

<script>    
    
    $('#form-validation1').validate({
        rules: {
            'checkbox': {
                required: true
            },
            'gender': {
                required: true
            }
        },
        highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error);
        },
        submitHandler: function(form) {
            $('button').attr('disabled', 'disabled');
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    if(response.status == 200){
                        vex.dialog.alert(response.message);
                    }else if(response.status == 201){
                        vex.dialog.alert(response.message);
                        window.location.href = response.link;
                    }else if(response.status == 202){
                        vex.dialog.alert(response.message);
                        loadURI(response.path);
                    }else if(response.status == 203){
                        vex.dialog.alert(response.message);
                        primary_table.ajax.reload(null, false);
                    }else if(response.status == 204){
                        loadURI(response.path);
                    }else if(response.status == 300){
                        vex.dialog.alert(response.message);
                    }
                },
                complete: function() {
                    $('button').removeAttr('disabled', 'disabled');
                }
            });
        }
    });
</script>