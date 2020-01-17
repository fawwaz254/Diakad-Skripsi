<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        UPLOAD DATA SISWA
                    </h2>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Upload File Excel
                            </h2>
                            <form id="form-upload" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-file-excel')}}" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                Pilih File Excel
                                <input type="file" name="file-excel" id="file-excel" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                                <br>
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <button class="btn btn-block bg-red waves-effect" type="submit">
                                        <i class="material-icons">cloud_upload</i>
                                        <span>Upload File Excel</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Petunjuk
                            </h2>
                            <h5>Format susunan file excel, sebagai berikut :</h5>
                            <ul>
                                <li>
                                    NIS : Nomor Induk Siswa
                                </li>
                                <li>
                                    NISN : Nomor Induk Siswa Nasional
                                </li>
                                <li>
                                    Nama Lengkap : Nama Lengkap Siswa
                                </li>
                                <li>
                                    Jenis Kelamin : Jenis Kelamin Siswa. 
                                    <br>Keterangan : Isi Dengan  (<b>L</b> atau <b>P</b>)
                                </li>
                                <li>
                                    Status Siswa : Status Siswa, pastikan format penulisan benar sesuai dengan Nama Status. Contoh : Aktif. <br> <strong>Pastikan Sudah Dibuat</strong> data master melalui menu Data Akademik -> Data Status Siswa
                                </li>
                                <li>
                                    Kelas : Kelas Siswa, pastikan format penulisan benar sesuai dengan Nama Kelas. Contoh : 7-AK-1. <br> <strong>Pastikan Sudah Dibuat</strong> data master melalui menu Setting Kelas -> Data Kelas
                                </li>
                                <li>
                                    Tahun Masuk : tahun angkatan masuk 4 digit. Contoh : 2018. <br> <strong>Pastikan Sudah Dibuat</strong> Data Penerimaan dengan Jenis Penerimaan "Siswa Lama" pada Tahun tersebut melalui menu Pendaftaran -> Data Penerimaan.
                                </li>
                                <li>
                                    Semester Masuk : semester ketika siswa masuk, pastikan format penulisan benar sesuai dengan Kode Semester. Contoh : 20151. <br> <strong>Pastikan Sudah Dibuat</strong> data master melalui menu Data Akademik -> Data Nama Semester.
                                </li>
                                <li>
                                    Jalur : Jalur Masuk Siswa, pastikan format penulisan benar sesuai dengan Nama Jalur. Contoh : Reguler. <br> <strong>Pastikan Sudah Dibuat</strong> data master melalui menu Data Akademik -> Data Jalur
                                </li>
                            </ul>
                            <a href="{{ route('siswa/download-file-excel') }}">
                                <button class="btn btn-block bg-blue waves-effect" type="submit">
                                        <i class="material-icons">cloud_upload</i>
                                        <span>Download Template Excel</span>
                                        
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#form-upload').submit(function(e) {
        e.preventDefault();
    }).validate({
        highlight: function (input) {
            $(input).addClass('is-danger');
        },
        unhighlight: function (input) {
            $(input).removeClass('is-danger');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.control').addClass('help').addClass('is-danger').append(error);
        },
        submitHandler: function(form) {
            $('button').attr('disabled', 'disabled');
            
            setTimeout(() => {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    enctype: 'multipart/form-data',
                    data: new FormData($('#form-upload')[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
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
                        $('button').removeAttr('disabled');
                    }
                });
                
            }, 1000);
        }
    });
</script>