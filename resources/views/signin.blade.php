@extends('app')
@section('meta')
<!-- Meta -->
@endsection

@section('content')

<body class="login-page" style="background-color: #36C5F1;">
    <div class="login-box">
        <div class="card is-login">

            @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-block">
                <strong>{{ $message }}</strong>
            </div>
            @endif

            <div class="row">
                <div class="col-md-6 hidden-sm hidden-xs">
                    <img src="{{ asset('logo/diakad.png') }}" alt="Logo Diakad" style="width: 100%;" />
                </div>
                <div class="col-md-6">
                    <div class="body" id="khusus-login">
                        <form class="form-validation" method="POST" action="{{ url('signin') }}">
                            {{ csrf_field() }}
                            <div class="msg" style="font-size:1.5em;line-height:50px">
                                <img class="hidden-md hidden-lg" src="{{ asset('logo/diakad.png') }}" alt="Logo Diakad" style="height: 90px;" />

                                <img src="https://diakad.sgp1.digitaloceanspaces.com/{{ $sekolah->nm_singkat_sekolah }}/global/logo-sekolah" alt="Logo Sekolah" style="height:90px;" />
                                <br>
                                {{ $sekolah->nm_sekolah }}
                            </div>
                            <div class="row clearfix">
                                <div class="col-xs-12">
                                    <label class="form-label" style="color: #14142C; font-weight:300;">Username</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" placeholder="Masukkan username di sini..." class="form-control" name="username" required="" aria-required="true" aria-invalid="true" value="{{ old('username') }}" style="background-color: transparent;">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <label class="form-label" style="color: #14142C; font-weight:300;">Password</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="password" class="form-control" placeholder="Masukkan password di sini..." name="password" required="" minLength="3" aria-required="true" style="background-color: transparent;">
                                        </div>
                                        <span style="right: 32px;position: absolute;top: 52.5%;">
                                            <a href="javascript:void(0)" onclick="tooglePassword(this)"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-xs-12 text-right">
                                    <a href="/forget-password" style="color:#36C5F1;" target="_blank"><b>Lupa Password?</b></a>
                                </div>
                                <div class="col-xs-12">
                                    <button class="btn btn-block waves-effect" style="background-color:#36C5F1;color:#fff;padding:10px" type="submit">LOGIN</button>
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
<!-- Javascript -->
<script>
    localStorage.clear();

    function tooglePassword(el) {
        $(el).find('i').toggleClass("fa-eye fa-eye-slash");
        var input = $('input[name=password]');
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    }
</script>
@endsection