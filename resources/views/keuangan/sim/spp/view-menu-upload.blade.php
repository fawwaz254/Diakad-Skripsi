<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        UPLOAD PEMBAYARAN
                    </h2>
                </div>
                @include('keuangan/sim/spp/partials/header-card-menu')
            </div>
            <div class="card">
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Upload File Excel
                            </h2>
                            <form id="form-upload"
                                action="{{ url(Request::segment(1) . '/sim/spp/upload-pembayaran') }}" method="post"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}
                                Pilih File Excel
                                <input type="file" name="file-excel" id="file-excel"
                                    accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, .csv, .xls">
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
                                {{-- <li>
                                    Download EXCEL dari menu SPP > EXCEL {{ env('APP_NAME', 'dsmart edu') }} Aplikasi
                                    Keuangan
                                </li> --}}
                                <li>
                                    Download Upload Template Pembayaran SPP
                                </li>

                                {{-- <div class="col-xs-6 col-sm-6 col-md-6"> --}}
                                <a href="{{ route('keuangan/download-contoh-upload-pembayaran') }}">
                                    <button class="btn btn-block bg-green waves-effect" type="submit">
                                        <i class="material-icons">cloud_upload</i>
                                        <span>Download Template Upload Pembayaran SPP</span>
                                    </button>
                                </a>

                                <li>
                                    Download Upload Template Pembayaran non-SPP
                                </li>
                                <a href="{{ route('keuangan/download-contoh-upload-pembayaran-non-spp') }}">
                                    <button class="btn btn-block bg-green waves-effect" type="submit">
                                        <i class="material-icons">cloud_upload</i>
                                        <span>Download Template Upload Pembayaran NON SPP</span>
                                    </button>
                                </a>
                                <li>
                                    Note = Untuk tanggal harus mengunakan format 2023-03-20,
                                    <br>
                                    (Jika tidak bisa gunakan petik di depan)
                                </li>
                                {{-- </div> --}}
                            </ul>
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
