<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="block-header">
                <h2>
                    <a class="btn bg-blue waves-effect target-link "
                        href="{{ url(Request::segment(1) . '#' . Request::segment(2)) . '/list-kunjungan-magang' }}">
                        <i class="material-icons">backspace</i>
                        Kembali
                    </a>
                </h2>
            </div>
            <div class="card">
                <div class="header d-flex justify-content-between">
                    <h2>
                        ADD KUNJUNGAN MAGANG
                    </h2>
                </div>
                <div class="body">
                    <form id="form-upload" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2)) . '/action-kunjungan-magang/add' }}"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <h2 class="card-inside-title">
                            Periode Magang
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="periode_magang">
                                    <option value="">-- Pilih Periode Magang --</option>
                                    @foreach ($periode_magang as $periode)
                                        <option value="{{ $periode->id_periode_magang }}">
                                            {{ $periode->nm_periode_magang . ' ' }}{{ $periode->nomor_sk_periode_magang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Rekanan Magang
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="rekanan_magang">
                                    <option value="">-- Pilih Rekanan Magang --</option>
                                    @foreach ($rekanan_magang as $rekanan)
                                        <option value="{{ $rekanan->id_rekanan_magang }}">
                                            {{ $rekanan->nm_rekanan_magang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            keterangan Kunjungan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <textarea name="keterangan_kunjungan" id="" class="form-control" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Foto Kunjungan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="file" class="form-control" name="foto_kunjungan" aria-required="true"
                                    aria-invalid="true" accept=".png, .jpg, .jpeg">
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit">
                                    <i class="material-icons">save</i>
                                    <span>Save</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
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

            var formData = new FormData(form);
            console.log(formData);

            setTimeout(() => {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    enctype: 'multipart/form-data',
                    data: formData,
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
                            setTimeout(() => {
                                loadURI(response.path);
                            }, 2000);
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
