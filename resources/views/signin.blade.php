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
        <div class="card" style="background: rgba(255, 255, 255, 0.5);">
            <img src="{{asset('media/logo-diakad.png')}}" alt="Logo Diakad" style="width: 100%;" />
            <div class="body">
                <form class="form-validation" method="POST" action="{{url('signin')}}">
                {{csrf_field()}}
                    <div class="msg"><strong>{{$sekolah->nm_sekolah}}</strong>&nbsp;&nbsp;
                    <img src="https://diakad.sgp1.digitaloceanspaces.com/{{$sekolah->nm_singkat_sekolah}}/global/logo-sekolah" alt="Logo Sekolah" style="width:64px;" /></div>
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
                            <button class="btn btn-block bg-pink waves-effect" type="submit">LOGIN</button>
                        </div>
                    </div>
                    <div class="row m-t-15 m-b--20">
                        <div class="col-xs-12 align-center">
                            Official Website : <a href="https://www.diakad.id" target="_blank">DIAKAD</a>
                        </div>
                        <div class="col-xs-12 align-center">
                            Powered By <a href="https://www.solusimaster.com" target="_blank">Solusi Master</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
@endsection

@section('js')
<!-- Javascript -->
@endsection