<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        SETTING WALI MURID
                    </h2>
                </div>
                <div class="body">
                    {{-- <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="{{!empty($act)? '' : 'active'}}">
                            <a href="#data-wali-murid" data-toggle="tab" aria-expanded="true">
                                <i class="material-icons">create</i> Data Wali Murid
                            </a>
                        </li>
                        <li role="presentation" class="{{!empty($act)? 'active' : ''}}">
                            <a href="#tambah-data-wali-murid" data-toggle="tab">
                                <i class="material-icons">edit</i> Tambah Data Wali Murid
                            </a>
                        </li>
                    </ul> --}}
                    <div class="tab-content">
                        @if (!empty($act))
                            <div role="tabpanel" class="tab-pane fade" id="data-wali-murid">
                            @else
                                <div role="tabpanel" class="tab-pane fade active in" id="data-wali-murid">
                        @endif
                        <form id="form-validation" method="POST"
                            action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/post-view-setting-wali-murid') }}">
                            {{ csrf_field() }}

                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <h2 class="card-inside-title">
                                        Jurusan
                                    </h2>
                                    <select class="form-control show-tick" name="id_jurusan"
                                        onchange="changeJurusan(this)">
                                        <option value="0">-- Semua --</option>
                                        @foreach ($data_jurusan as $data)
                                            <option value="{{ $data->id_jurusan }}">{{ $data->nm_jurusan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <h2 class="card-inside-title">
                                        Kelas
                                    </h2>
                                    <select class="form-control show-tick" name="id_kelas">
                                        <option value="0">-- Semua --</option>
                                        @foreach ($data_kelas as $data)
                                            <option value="{{ $data->id_kelas }}">{{ $data->nm_kelas }}</option>
                                        @endforeach
                                    </select>
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

                    {{-- @if (!empty($act))
                        <div role="tabpanel" class="tab-pane fade active in" id="tambah-data-wali-murid">
                        @else
                            <div role="tabpanel" class="tab-pane fade" id="tambah-data-wali-murid">
                    @endif
                    <form id="form-validation1" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/action-setting-wali-murid/add/' . $id_wali_murid) }}"
                        autocomplete="off">
                        {{ csrf_field() }}
                        <h2 class="card-inside-title">
                            Nama Wali Murid
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_wali_murid" required=""
                                    aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            No. HP Wali Murid
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nomor_hp_wali_murid" required=""
                                    aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <h2 class="card-inside-title">
                                    Gelar Depan
                                </h2>
                                <input type="text" class="form-control" name="gelar_depan" aria-required="true"
                                    aria-invalid="true">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <h2 class="card-inside-title">
                                    Gelar Belakang
                                </h2>
                                <input type="text" class="form-control" name="gelar_belakang" aria-required="true"
                                    aria-invalid="true">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                        class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div> --}}
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
                        clearInput();
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

    function clearInput() {
        $('input[name=nm_wali_murid]').val('');
        $('input[name=nomor_hp_wali_murid]').val('');
        $('input[name=gelar_depan]').val('');
        $('input[name=gelar_belakang]').val('');
    }

    function changeJurusan(el) {
        $('select[name=id_kelas]').html('');
        $.ajax({
            url: '{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/getDataKelas') }}',
            type: 'POST',
            data: {
                jurusan: $('select[name=id_jurusan]').val(),
            },
            success: function(kelas) {

                $('select[name=id_kelas]').html('');
                var html = '<option value="0">-- Semua --</option>';
                $.each(kelas, function(key, item) {
                    html += '<option value="' + item.id_kelas + '">' + item.nm_kelas + '</option>'
                });
                $('select[name=id_kelas]').html(html);
            }
        });
    }
</script>
