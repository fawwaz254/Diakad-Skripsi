<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-green">
                    <h2>
                        INSERT/UPDATE SISWA
                    </h2>
                </div>
                <div class="body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#insert-siswa" data-toggle="tab" aria-expanded="true">
                                <i class="material-icons">create</i> INSERT SISWA
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#update-siswa" data-toggle="tab">
                                <i class="material-icons">edit</i> UPDATE SISWA
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="insert-siswa">
                            <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-insert-update-siswa/insert/0')}}">
                                    {{csrf_field()}}
                                <h2 class="card-inside-title">
                                    NIS Siswa
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nis_siswa" required="" aria-required="true" aria-invalid="true">
                                    </div>
                                </div>
                                <h2 class="card-inside-title">
                                    NISN Siswa
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nisn_siswa" required="" aria-required="true" aria-invalid="true">
                                    </div>
                                </div>
                                <h2 class="card-inside-title">
                                    Nama Lengkap Siswa
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_pengguna" required="" aria-required="true" aria-invalid="true">
                                    </div>
                                </div>
                                <h2 class="card-inside-title">
                                    Jenis Kelamin
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <select class="form-control show-tick" name="jenis_kelamin" id="jenis_kelamin">
                                            <option value="1">Laki-Laki</option>
                                            <option value="2">Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                                <h2 class="card-inside-title">
                                    Status Siswa
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <select class="form-control show-tick" name="id_status_pengguna" id="status_siswa">
                                              <option value="0">-- Pilih Status Siswa --</option>
                                                @foreach($status_pengguna as $status_siswa)
                                                    <option value="{{$status_siswa->id_status_pengguna}}">{{$status_siswa->nm_status_pengguna}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                                <h2 class="card-inside-title">
                                    Kelas
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <select class="form-control show-tick" name="id_kelas" id="id_kelas">
                                              <option value="0">-- Pilih Kelas --</option>
                                                @foreach($kelas as $kelas)
                                                    <option value="{{$kelas->id_kelas}}">{{$kelas->nm_kelas}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                                <h2 class="card-inside-title">
                                    Tahun Masuk
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <select class="form-control show-tick" name="thn_masuk_siswa" id="thn_masuk">
                                              <option value="0">-- Pilih Tahun Masuk --</option>
                                                @foreach($thn_masuk_siswa as $tahun)
                                                    <option value="{{$tahun->thn_masuk_siswa}}">{{$tahun->thn_masuk_siswa}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                                <h2 class="card-inside-title">
                                    Semester
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <select class="form-control show-tick" name="id_semester" id="id_semester">
                                              <option value="0">-- Pilih Semester --</option>
                                                @foreach($semester as $semester)
                                                    <option value="{{$semester->id_semester}}">{{$semester->nm_semester}} {{$semester->tahun_ajaran}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                                <h2 class="card-inside-title">
                                    Jalur Masuk
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <select class="form-control show-tick" name="id_jalur" id="jalur">
                                            <option value="0">-- Pilih Jalur --</option>
                                            @foreach($jalur as $jalur)
                                                <option value="{{$jalur->id_jalur}}">{{$jalur->nm_jalur}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="update-siswa">
                            <form id="form-validation1" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-view-update-siswa')}}">
                            {{csrf_field()}}
                                <h2 class="card-inside-title">
                                    NIS atau NISN Siswa
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nis_nama_siswa" aria-invalid="true">
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <button class="btn btn-block bg-red waves-effect" type="submit" name="cari"><i class="material-icons">save</i><span>Save</span></button>
                                    </div>
                                </div>
                            </form>
                        </div>         
                    </div>   
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<script>    
    var primary_table = null;
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