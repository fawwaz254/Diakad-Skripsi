<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link "
                href="{{ url(Request::segment(1) . '#siswa/setting-wali-murid/view-kelas/' . $id_jurusan . '/' . $id_kelas) }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        UPLOAD SETTING WALI MURID KELAS {{ $kelas ? $kelas->nm_kelas : '' }}
                    </h2>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Petunjuk
                            </h2>
                            <h5>Download file di bawah terlebih dahulu :</h5>
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
                                    Nama Wali Murid : Nama Lengkap Wali Murid dari Siswa di sampingnya
                                </li>
                                <li>
                                    Telp Wali Murid : Nomor Telepon Wali Murid dari Siswa di sampingnya
                                </li>
                                <li>
                                    Gelar Depan : Gelar Depan Wali Murid dari Siswa di sampingnya
                                </li>
                                <li>
                                    Gelar Belakang : Gelar Belakang Wali Murid dari Siswa di sampingnya
                                </li>
                            </ul>
                            <h5>Catatan: Hanya ubah kolom yang terkait dengan wali murid</h5>
                            <a class="btn btn-block bg-blue waves-effect" target="_blank"
                                href="{{ route('siswa/download-file-excel-wali-murid') }}">
                                <i class="material-icons">cloud_upload</i>
                                <span>Download File Excel</span>
                            </a>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Upload File Excel
                            </h2>
                            <form id="form-upload"
                                action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/upload/' . $id_jurusan . '/' . $id_kelas) }}"
                                method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                Pilih File Excel
                                <input type="file" name="file-excel" id="file-excel"
                                    accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                                <br>
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <button class="btn btn-block bg-red waves-effect" type="submit">
                                        <i class="material-icons">cloud_upload</i>
                                        <span>Upload File Excel</span>
                                    </button>
                                </div>
                            </form>
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
        highlight: function(input) {
            $(input).addClass('is-danger');
        },
        unhighlight: function(input) {
            $(input).removeClass('is-danger');
        },
        errorPlacement: function(error, element) {
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
                        if (response.status == 200) {
                            vex.dialog.alert(response.message);
                        } else if (response.status == 201) {
                            vex.dialog.alert(response.message);
                            window.location.href = response.link;
                        } else if (response.status == 202) {
                            vex.dialog.alert(response.message);
                            loadURI(response.path);
                        } else if (response.status == 203) {
                            vex.dialog.alert(response.message);
                            primary_table.ajax.reload(null, false);
                        } else if (response.status == 204) {
                            loadURI(response.path);
                        } else if (response.status == 300) {
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
