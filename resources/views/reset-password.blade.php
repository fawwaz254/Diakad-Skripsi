@extends('app')
@section('meta')
<!-- Meta -->
@endsection

@section('content')
<!-- <body class="login-page" style="background-color: #006302;"> -->

<body class="login-page" style="background-color: #36C5F1;">
    <div class="login-box">
        <div class="card is-login">
            <div class="row">
                <div class="col-md-6 hidden-sm hidden-xs">
                    <img src="{{asset('logo/diakad.png')}}" alt="Logo Diakad" style="width: 100%;" />
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
                                <div class="col-xs-12">
                                    <label class="form-label" style="color: #14142C; font-weight:300;">Password</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="password" class="form-control" name="password" required="" aria-required="true" aria-invalid="true" style="background-color: transparent;">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <label class="form-label" style="color: #14142C; font-weight:300;">Konfirmasi Password</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="password" class="form-control" name="password_confirmation" required="" aria-required="true" aria-invalid="true" style="background-color: transparent;">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <button class="btn btn-block waves-effect" style="background-color:#36C5F1;color:#fff;padding:10px" type="submit">RESET MY PASSWORD</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <div class="row" style="background-color:#f7f7f7;margin-right:0;margin-left:0;border-bottom-left-radius: 5px;border-bottom-right-radius: 5px;padding-top:25px;padding-bottom:25px ">
                <div class="row">
                    <div class="col-xs-12 align-center">
                        <img src="https://diakademik.test/favicon_io/favicon-circle.png" height="24" /><b>Diakad</b> By <a href="https://edumate.id" style="color:#36C5F1;" target="_blank"><b>EDUMATE</b></a></span>
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
                    } else if (response.status == 205) {
                        $('#modalMaster').modal('hide');
                        vex.dialog.alert(response.message);
                        primary_table.ajax.reload(null, false);
                    } else if (response.status == 300) {
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