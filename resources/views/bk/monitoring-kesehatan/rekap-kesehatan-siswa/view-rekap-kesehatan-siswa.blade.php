<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        FILTER KELAS
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Kelas</label>
                                        <select class="form-control show-tick" name="id_kelas" required="">
                                            <option value="">-- Pilih Kelas --</option>
                                            @foreach($data_kelas as $data)
                                                <option {{(!empty($selected_kelas) && $selected_kelas->id_kelas == $data->id_kelas)? 'selected' : ''}} value="{{$data->id_kelas}}">{{$data->nm_kelas}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
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
    $(function(){    
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: false
        });
    });
</script>
<script>
    let modul_url       = '{{ Request::segment(2) }}';
    let menu_url        = '{{ Request::segment(3) }}';

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
            let load_url = modul_url + '/' + menu_url;
            $.each(params, function(i, field){
                load_url += '/' + field.value;
            });
            loadURI(load_url);
            $('button').removeAttr('disabled');
        }
    });
</script>