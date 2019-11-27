@extends('app')
@section('meta')
<!-- Meta -->
@endsection

@section('content')
<!-- <body class="login-page" style="background-color: #006302;"> -->
<body class="login-page" style="background-image: url({{asset('media/login-bg.jpg')}}); background-repeat: no-repeat; background-size: cover; background-color: whitesmoke;">
    <div class="login-box">
        <!-- <div class="logo">
            <a href="javascript:void(0);">Welcome to DIAKAD</b></a>
            <small>Digital Akademik</small>
        </div> -->
        <div class="card is-login">
            <div class="row">
                <div class="col-md-6 hidden-sm hidden-xs">
                    <img src="https://diakad.sgp1.cdn.digitaloceanspaces.com/signin-logo.png" alt="Logo Diakad" style="width: 100%;" />
                </div>
                <div class="col-md-6">
                    <div class="body" id="khusus-login">
                        <form class="form-validation" method="POST" action="{{url('signin')}}">
                            {{csrf_field()}}
                            <div class="msg" style="font-size:1.5em;line-height:50px">
                                <img class="hidden-md hidden-lg" src="https://diakad.sgp1.cdn.digitaloceanspaces.com/signin-logo.png" alt="Logo Diakad" style="height: 90px;" />
                                <img src="https://diakad.sgp1.digitaloceanspaces.com/{{$sekolah->nm_singkat_sekolah}}/global/logo-sekolah" alt="Logo Sekolah" style="height:90px;" />
                                <br>
                                <strong>{{$sekolah->nm_sekolah}}</strong>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="material-icons">person</i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="username" required="" aria-required="true" aria-invalid="true" value="{{old('username')}}" style="background-color: transparent;">
                                            <label class="form-label" style="color: #555;">Username</label>
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
                                            <input type="password" class="form-control" name="password" required="" minLength="4" aria-required="true" style="background-color: transparent;">
                                            <label class="form-label" style="color: #555;">Password</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <button class="btn btn-block waves-effect" style="background-color:#235789;color:#fff;padding:10px" type="submit">LOGIN</button>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
            <div class="row" style="background-color:#f7f7f7;margin-right:0;margin-left:0;border-bottom-left-radius: 5px;border-bottom-right-radius: 5px;padding-top:25px;padding-bottom:25px ">
                    <div class="row">
                            <div class="col-xs-12 align-center">
                                <span style="color:#a7a7a7">Official Website : <a href="https://www.diakad.id" target="_blank">DIAKAD</a></span><br class="visible-xs-block"><span style="color:#a7a7a7" id="khusus-footer">Powered By <a href="https://www.solusimaster.com" target="_blank">Solusi Master</a></span>
                            </div>
                        </div>
            </div>
        </div>
    </div>
</body>
@endsection

@section('js')
<!-- Javascript -->
@endsection