@extends('app')
@section('meta')
<!-- Meta -->
@endsection

@section('content')
<!-- <body class="login-page" style="background-color: #006302;"> -->
<body class="login-page" style="background-image: url({{asset('media/login-bg2.jpeg')}}); background-repeat: no-repeat; background-size: cover; background-color: whitesmoke;">
    <div class="login-box">
        <div class="card is-login">
            <div class="row">
                <div class="col-md-6 hidden-sm hidden-xs">
                    @if($sekolah->nm_singkat_sekolah == 'smawidyadarma')
                     <img src="{{asset('logo/diakad.png')}}" alt="Logo Diakad" style="width: 100%;" />
                    @else
                     <img src="{{asset('logo/diakad.png')}}" alt="Logo Diakad" style="width: 100%;" />
                    @endif
                   
                </div>
                <div class="col-md-6">
                    <div class="body" id="khusus-login">
                        <form id="form-validation" method="POST" action="{{url('reset-password-action')}}">
                            {{csrf_field()}}
                            <div class="msg" style="font-size:1.5em;line-height:50px">
                                @if($sekolah->nm_singkat_sekolah == 'smawidyadarma')
                                <img class="hidden-md hidden-lg" src="{{asset('logo/logo-dsm.png')}}" alt="Logo Diakad" style="height: 90px;" />
                                @else
                                <img class="hidden-md hidden-lg" src="{{asset('logo/logo-diakad-by-dsm.png')}}" alt="Logo Diakad" style="height: 90px;" />
                                @endif
                                
                                <img src="https://diakad.sgp1.digitaloceanspaces.com/{{$sekolah->nm_singkat_sekolah}}/global/logo-sekolah" alt="Logo Sekolah" style="height:90px;" />
                                <br>
                                {{$sekolah->nm_sekolah}}
                            </div>
                            <center>
                                <p>Reset Password Email {{session('reset_email')}}</p>
                            <br>
                            </center>

                            <div class="row clearfix">
                                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="material-icons">lock</i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="password" class="form-control" name="password" required="" aria-required="true" aria-invalid="true"style="background-color: transparent;">
                                            <label class="form-label" style="color: #555;">Password</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="material-icons">lock</i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="password" class="form-control" name="password_confirmation" required="" aria-required="true" aria-invalid="true"style="background-color: transparent;">
                                            <label class="form-label" style="color: #555;">Konfirmasi Password</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-xs-12">
                                    <button class="btn btn-block waves-effect" style="background-color:#235789;color:#fff;padding:10px" type="submit">RESET MY PASSWORD</button>
                                </div>
                            </div>
                                           
                        </form>
                    </div>
                </div>
            </div>
            <div class="row" style="background-color:#f7f7f7;margin-right:0;margin-left:0;border-bottom-left-radius: 5px;border-bottom-right-radius: 5px;padding-top:25px;padding-bottom:25px ">
                    <div class="row">
                            <div class="col-xs-12 align-center">
                                Powered By <a href="https://edumate.id" target="_blank">EDUMATE</a></span>
                            </div>
                        </div>
            </div>
        </div>
    </div>
</body>
@endsection

@section('js')

<script>    
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
                    }else if(response.status == 205){
                        $('#modalMaster').modal('hide');
                        vex.dialog.alert(response.message);
                        primary_table.ajax.reload(null, false);
                    }else if(response.status == 300){
                        vex.dialog.alert(response.message);
                    }
                },
                complete: function() {
                    $('button').removeAttr('disabled');
                }
            });
        }
    });
</script>
@endsection