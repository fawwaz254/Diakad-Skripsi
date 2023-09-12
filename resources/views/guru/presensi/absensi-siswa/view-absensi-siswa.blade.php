<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        PILIH ABSENSI SISWA
                    </h2>
                </div>
                <div class="body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#kbm_with_icon_title" data-toggle="tab" aria-expanded="true">
                                <i class="material-icons">class</i> KELAS KBM
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#uts_with_icon_title" data-toggle="tab">
                                <i class="material-icons">assignment</i> UJIAN UTS
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#uas_with_icon_title" data-toggle="tab">
                                <i class="material-icons">send</i> UJIAN UAS
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="kbm_with_icon_title">
                            <form id="form-validation" method="POST"
                                action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/post-kbm-absensi-siswa') }}">
                                {{ csrf_field() }}
                                <h2 class="card-inside-title">
                                    Kelas KBM
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <select class="form-control show-tick" name="id_jadwal_kelas_mp"
                                            onchange="changeKelas(this)">
                                            <option value="" disabled selected>-- Pilih Kelas KBM --</option>
                                            @foreach ($grup_kbm_perhari as $hari => $data_kbm)
                                                <optgroup label="{{ $hari }}">
                                                    @foreach ($data_kbm as $data)
                                                        <option value="{{ $data->id_jadwal_kelas_mp }}">
                                                            {{ $data->nm_mata_pelajaran }} - {{ $data->nm_kelas }} -
                                                            {{ $data->nm_ruangan }} - JAM
                                                            {{ $data->jam_mulai }}:{{ $data->menit_mulai }} -
                                                            {{ $data->jam_selesai }}:{{ $data->menit_selesai }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <h2 class="card-inside-title">
                                    Pertemuan pekan ke
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <select class="form-control show-tick" name="pertemuan_ke">
                                            <option value="" disabled selected>-- Pilih Pertemuan pekan ke --
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                                class="material-icons">save</i><span>Manual</span></button>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <a class="btn btn-block bg-blue waves-effect" onclick="absensiBarcodeKbm()"><i
                                                class="material-icons">save</i><span>Barcode</span></a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="uts_with_icon_title">
                            <form id="form-validation-uts" method="POST"
                                action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/post-uts-absensi-siswa') }}">
                                {{ csrf_field() }}
                                <h2 class="card-inside-title">
                                    Ujian UTS
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <select class="form-control show-tick" name="id_ujian_mp">
                                            @foreach ($data_uts as $data)
                                                <option value="{{ $data->id_ujian_mp }}">
                                                    {{ $data->nm_mata_pelajaran }} - {{ $data->nm_kelas }} -
                                                    {{ $data->nm_ruangan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                                class="material-icons">save</i><span>Save</span></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="uas_with_icon_title">
                            <form id="form-validation-uas" method="POST"
                                action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/post-uas-absensi-siswa') }}">
                                {{ csrf_field() }}
                                <h2 class="card-inside-title">
                                    Ujian UAS
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <select class="form-control show-tick" name="id_ujian_mp">
                                            @foreach ($data_uas as $data)
                                                <option value="{{ $data->id_ujian_mp }}">
                                                    {{ $data->nm_mata_pelajaran }} - {{ $data->nm_kelas }} -
                                                    {{ $data->nm_ruangan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                                class="material-icons">save</i><span>Save</span></button>
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
    $('#form-validation-uts').validate({
        rules: {
            'checkbox': {
                required: true
            },
            'gender': {
                required: true
            }
        },
        highlight: function(input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function(input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function(error, element) {
            $(element).parents('.form-group').append(error);
        },
        submitHandler: function(form) {
            $('button').attr('disabled', 'disabled');
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
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
                    $('button').removeAttr('disabled', 'disabled');
                }
            });
        }
    });
    $('#form-validation-uas').validate({
        rules: {
            'checkbox': {
                required: true
            },
            'gender': {
                required: true
            }
        },
        highlight: function(input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function(input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function(error, element) {
            $(element).parents('.form-group').append(error);
        },
        submitHandler: function(form) {
            $('button').attr('disabled', 'disabled');
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
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
                    $('button').removeAttr('disabled', 'disabled');
                }
            });
        }
    });

    function changeKelas(el) {
        $.ajax({
            url: '{{ url(Request::segment(1) . '/' . Request::segment(2) . '/pertemuan-byjadwalkelasmp') }}',
            type: 'POST',
            data: {
                id_jadwal_kelas_mp: $('select[name=id_jadwal_kelas_mp]').val()
            },
            success: function(result) {
                $('select[name=pertemuan_ke]').html('');
                $('select[name=pertemuan_ke]').append(
                    '<option value="" disabled selected >-- Pilih Pertemuan pekan ke --</option>');
                $.each(result, function(key, item) {
                    $('select[name=pertemuan_ke]').append('<option value="' + item.value + '">' +
                        item.text + '</option>');
                });
            }
        });
    }


    function absensiBarcodeKbm() {
        if ($('select[name=id_jadwal_kelas_mp]').val() && $('select[name=pertemuan_ke]')
            .val()) {
            var url_barcode = base_url + '/' + role_url + '/presensi/absensi-siswa/view-kbm-barcode/' +
                $('select[name=id_jadwal_kelas_mp]').val() + '/' + $('select[name=pertemuan_ke]')
                .val();
            window.open(url_barcode, "_blank");
        } else {
            vex.dialog.alert('Data Harus Dipilih');
        }
    }
</script>
