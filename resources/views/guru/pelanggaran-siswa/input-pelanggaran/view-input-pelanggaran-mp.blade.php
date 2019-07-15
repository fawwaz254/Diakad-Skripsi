<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-orange">
                    <h2>
                        PILIH KELAS KBM
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-input-pelanggaran-mp')}}">
                            {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Kelas KBM
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_jadwal_kelas_mp" onchange="changeKelas(this)">
                                    <option value="" disabled selected >-- Pilih Kelas KBM --</option>
                                    @foreach($data_kbm as $data)
                                        <option value="{{$data->id_jadwal_kelas_mp}}">{{$data->nm_jadwal_hari}} - {{$data->nm_mata_pelajaran}} - {{$data->nm_kelas}} - {{$data->nm_ruangan}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Pertemuan Ke
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="pertemuan_ke">
                                    <option value="" disabled selected >-- Pilih Pertemuan Ke --</option>
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div>
                    </form>
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
        highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error);
        },
        submitHandler: function(form) {
            $('button').attr('disabled', 'disabled');
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    if(response.status == 200){
                        vex.dialog.alert(response.message);
                    }else if(response.status == 201){
                        vex.dialog.alert(response.message);
                        window.location.href = response.link;
                    }else if(response.status == 202){
                        vex.dialog.alert(response.message);
                        loadURI(response.path);
                    }else if(response.status == 203){
                        vex.dialog.alert(response.message);
                        primary_table.ajax.reload(null, false);
                    }else if(response.status == 204){
                        loadURI(response.path);
                    }else if(response.status == 300){
                        vex.dialog.alert(response.message);
                    }
                },
                complete: function() {
                    $('button').removeAttr('disabled', 'disabled');
                }
            });
        }
    });

    function changeKelas(el){
        $.ajax({
            url: '{{url(Request::segment(1).'/'.Request::segment(2).'/pertemuan-byjadwalkelasmp')}}',
            type: 'POST',
            data: {
                id_jadwal_kelas_mp: $('select[name=id_jadwal_kelas_mp]').val()
            },
            success: function(result) {
                $('select[name=pertemuan_ke]').html('');
                $('select[name=pertemuan_ke]').append('<option value="" disabled selected >-- Pilih Pertemuan Ke --</option>');
                $.each(result, function( key, item ) {
                    $('select[name=pertemuan_ke]').append('<option value="'+item.value+'">'+item.text+'</option>');
                });
            }
        });
    }
</script>