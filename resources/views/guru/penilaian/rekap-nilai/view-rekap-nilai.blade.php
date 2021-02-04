<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        PILIH MATA PELAJARAN
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" >
                        <label>Mata Pelajaran</label>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_kelas_mp" required>
                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                    @foreach($grup_semester_kelas as $tahun_ajaran => $grup_kelas)
                                        @foreach($grup_kelas as $nm_semester => $datapergrup)
                                            <optgroup label="{{$nm_semester}} ({{$tahun_ajaran}})">
                                                @foreach($datapergrup as $data)
                                                    @if($data->pjmp_pengampu_mp == 1)
                                                        <option value="{{$data->id_kelas_mp}}">{{$data->nm_mata_pelajaran . " (" . $data->kd_mata_pelajaran . ") Kelas " . $data->nm_kelas . " (PJMP)"}}</option>
                                                    @else
                                                        <option value="{{$data->id_kelas_mp}}">{{$data->nm_mata_pelajaran . " (" . $data->kd_mata_pelajaran . ") Kelas " . $data->nm_kelas . " (Anggota)"}}</option>
                                                    @endif
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    @endforeach 
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-btn-submit waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let modul_url       = '{{ $auth_data->modul_url }}';
    let menu_url        = '{{ $auth_data->menu_url }}';

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
        highlight: function (input) {
            $(input).parents('.form-group').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-group').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error);
        },
        submitHandler: function(form) {
            $('button').attr('disabled', 'disabled');
            let params = $(form).serializeArray();
            console.log(params);
            let load_url = modul_url + '/' + menu_url + '/detail';
            $.each(params, function(i, field){
                load_url += '/' + field.value;
            });
            loadURI(load_url);
            $('button').removeAttr('disabled');
        }
    });
</script>