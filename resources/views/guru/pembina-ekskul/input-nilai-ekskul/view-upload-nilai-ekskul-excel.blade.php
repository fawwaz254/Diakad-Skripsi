<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="block-header clearfix">
                <a class="btn bg-blue waves-effect target-link" style="float: left;"
                    href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/input-nilai-ekskul/detail/' . $id_semester . '/' . $id_ekskul) }}">
                    <i class="material-icons">keyboard_backspace</i><span>Kembali</span>
                </a>

                <a class="btn bg-green waves-effect" style="float: right;"
                    href="{{ url(Request::segment(1) . '/pembina-ekskul/input-nilai-ekskul/excel/' . $id_semester . '/' . $id_ekskul . '/template') }}"
                    target="_blank">
                    <i class="material-icons">description</i><span>Template Excel</span>
                </a>
            </div>
            <div class="card">
                <div class="header">
                    <h2>
                        Upload Nilai Ekstrakurikuler {{ $nm_ekskul->nm_ekskul }}
                    </h2>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Upload Excel
                            </h2>
                            <form id="form-upload"
                                action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/' . Request::segment(4) . '/' . Request::segment(5) . '/' . Request::segment(6)) }}"
                                method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <input type="hidden" name="id_ekskul" id="id_ekskul" value="{{ $id_ekskul }}" />
                                <input type="hidden" name="id_semester" id="id_semester" value="{{ $id_semester }}" />
                                Pilih File Excel
                                <input type="file" name="file-excel" id="file-excel"
                                    accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, .xls">
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
                            <ol>
                                <li>Download template excel terlebih dahulu sesuai dengan ekstrakurikuler yang
                                    dipilih.</li>
                                <li>Pastikan menggunakan template excel terbaru.</li>
                                <li>Input nilai pada komponen nilai yang tersedia, (Jangan mengubah isi kolom lain
                                    selain kolom nilai).
                                </li>
                                <li>Upload.</li>
                            </ol>
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
