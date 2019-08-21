<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-pink">
                    <h2>
                        UPLOAD DATA GURU
                    </h2>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Upload File Excel
                            </h2>
                            <form  id="form-upload" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-file-excel')}}" method="post" enctype="multipart/form-data">
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
                                    NIP : Nomor Induk Pegawai
                                </li>

                                <li>
                                    Nama Lengkap : Nama Lengkap Guru/Tendik. <small><strong>Jika guru/tendik memiliki gelar, tuliskan nama tanpa gelar</strong></small>
                                </li>

                                <li>
                                    Jenis Kelamin : Jenis Kelamin Siswa. Keterangan : Isi Dengan (<b>L</b> atau <b>P</b>)
                                </li>

                                <li>
                                    Status Guru : Status Guru, pastikan format penulisan benar sesuai dengan Nama Status. Contoh : Aktif. <br>
                                    <strong>Cek di Menu Data Sumber Daya -> Data Status Aktif Guru</strong>
                                </li>
                                <li>
                                    Unit Kerja : Unit Kerja Guru/Tendik, pastikan format penulisan benar sesuai dengan Unit Kerja. Contoh : Keuangan.<br>
                                    <strong>Cek di Menu Data Sumber Daya -> Data Unit Kerja</strong>
                                </li>
                            </ul>
                            <a href="{{ route('guru/download-file-excel') }}">
                                <span>Download Template Excel</span>
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