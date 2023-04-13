<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        PILIH EKSKUL
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Semester</label>
                                        <select class="form-control show-tick" name="id_semester" required="">
                                            @foreach ($data_semester as $data)
                                                <option value="{{ $data->id_semester }}"
                                                    {{ (!empty($selected_semester) && $selected_semester->id_semester == $data->id_semester ? 'selected' : $data->is_aktif_semester == 1) ? 'selected' : '' }}>
                                                    {{ $data->tahun_ajaran }}
                                                    {{ $data->nm_semester }}
                                                    @if ($data->is_aktif_semester == 1)
                                                        (Aktif)
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Ekskul</label>
                                        <select class="form-control show-tick" name="id_ekskul" required="">
                                            <option value="">-- Pilih Ekskul --</option>
                                            @foreach ($data_ekskul as $data)
                                                @if (!empty($data->ekskul))
                                                    <option
                                                        {{ $id_ekskul == $data->ekskul->id_ekskul ? 'selected' : '' }}
                                                        value="{{ $data->ekskul->id_ekskul }}">
                                                        {{ $data->ekskul->nm_ekskul }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-btn-submit waves-effect" type="submit"><i
                                        class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let modul_url = '{{ $auth_data->modul_url }}';
    let menu_url = '{{ $auth_data->menu_url }}';

    $('select:not(.ms)').selectpicker();
    $('#form-validation').validate({
        rules: {
            'checkbox': {
                required: true
            },
            'gender': {
                required: true
            }
        },
        highlight: function(input) {
            $(input).parents('.form-group').addClass('error');
        },
        unhighlight: function(input) {
            $(input).parents('.form-group').removeClass('error');
        },
        errorPlacement: function(error, element) {
            $(element).parents('.form-group').append(error);
        },
        submitHandler: function(form) {
            $('button').attr('disabled', 'disabled');
            let params = $(form).serializeArray();
            let load_url = modul_url + '/' + menu_url + '/detail';
            $.each(params, function(i, field) {
                load_url += '/' + field.value;
            });
            loadURI(load_url);
            $('button').removeAttr('disabled');
        }
    });
</script>
