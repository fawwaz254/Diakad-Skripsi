<div class="container-fluid">
    <h2><a class="btn bg-blue waves-effect target-link"
            href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/tracer-alumni') }}">
            <i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        UPLOAD DATA TRACER ALUMNI SMP
                    </h2>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <form id="form-upload"
                            action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/post-file-excel') }}"
                            method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            {{-- {{url(Request::segment(1).'/'.Request::segment(2). '/' . Request::segment(3). '/post-file-excel')}} --}}
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                {{-- <h2 class="card-inside-title">Status </h2>
                                <div class="form-line">
                                    <select class="form-control show-tick" name="status">
                                        <option value="" selected disabled> Pilih Status </option>
                                        <option value="bekerja">Bekerja</option>
                                        <option value="wirausaha">Wirausaha</option>
                                        <option value="kuliah">Kuliah</option>
                                        <option value="menunggu">Menunggu</option>
                                    </select>
                                </div> --}}
                                <h2 class="card-inside-title">Pilih File Excel</h2>
                                <input type="file" name="file-excel" id="file-excel"
                                    accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, .xls">
                                <br>
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <button class="btn btn-block bg-red waves-effect" type="submit">
                                        <i class="material-icons">cloud_upload</i>
                                        <span>Upload File Excel</span>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                <p>Format Excel</p>
                            </h2>

                            <div class="col-md-8 col-sm-12 col-xs-12">
                                <b>Panduan</b><br>
                                <p>
                                    1. Download file<br>
                                    2. isi data<br>
                                    3. Pastikan mengunakan huruf kapital untuk kolom <br>
                                    (Jurusan, Kelas, Jenis Sekolah dan Jurusan Sekolah) <br>
                                    4. Upload file <br></p>

                                <hr>
                                <a href="{{ route('tracer-alumni-smp/download-file-excel') }}">
                                    <button class="btn btn-block bg-green waves-effect" type="submit">
                                        <i class="material-icons">cloud_upload</i>
                                        <span>Download Template</span>
                                    </button>
                                </a>
                            </div>
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
