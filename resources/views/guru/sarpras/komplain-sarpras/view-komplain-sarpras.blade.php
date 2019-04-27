<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-orange">
                    <h2>
                        PILIH KOMPLAIN SARPRAS
                    </h2>
                </div>
                <div class="body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#ruangan_with_icon_title" data-toggle="tab" aria-expanded="true">
                                <i class="material-icons">meeting_room</i> RUANGAN
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#bukualat_with_icon_title" data-toggle="tab">
                                <i class="material-icons">library_books</i> BUKU/ALAT
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="ruangan_with_icon_title">
                            <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-view-ruangan-sarpras')}}">
                                    {{csrf_field()}}
                                <h2 class="card-inside-title">
                                    Nama Ruangan
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <select class="form-control show-tick" name="id_ruangan">
                                            @foreach($data_ruangan as $data)
                                                <option value="{{$data->id_ruangan}}">{{$data->nm_ruangan}} - {{$data->nm_jenis_ruangan}}</option>
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
                                        <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="bukualat_with_icon_title">
                            <form id="form-validation1" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-view-bukualat-sarpras')}}">
                                    {{csrf_field()}}
                                <h2 class="card-inside-title">
                                    Buku/Alat
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <select class="form-control show-tick" name="id_buku_alat">
                                            @foreach($data_buku_alat as $data)
                                                <option value="{{$data->id_buku_alat}}">{{$data->nm_buku_alat}} - {{$data->nm_jenis_buku_alat}}</option>
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
                                        <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
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
</script>