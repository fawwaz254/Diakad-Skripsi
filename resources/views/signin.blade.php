@extends('app')
@section('meta')
    <!-- Meta -->
@endsection

@section('content')
    <!-- <body class="login-page" style="background-color: #006302;"> -->

    <body class="login-page" style="background-color: #13172e;">
        <div class="login-box">
            <div class="card is-login">

                @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-block">
                        <strong>{{ $message }}</strong>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6 hidden-sm hidden-xs">
                        @if ($sekolah->nm_singkat_sekolah == 'smawidyadarma')
                            <img src="{{ asset('logo/eduschool.png') }}" alt="Logo Diakad" style="width: 100%;" />
                        @else
                            <img src="{{ asset('logo/diakad.png') }}" alt="Logo Diakad" style="width: 100%;" />
                        @endif

                    </div>
                    <div class="col-md-6">
                        <div class="body" id="khusus-login">
                            <form class="form-validation" method="POST" action="{{ url('signin') }}">
                                {{ csrf_field() }}
                                <div class="msg" style="font-size:1.5em;line-height:50px">
                                    @if ($sekolah->nm_singkat_sekolah == 'smawidyadarma')
                                        <img class="hidden-md hidden-lg" src="{{ asset('logo/eduschool.png') }}"
                                            alt="Logo Diakad" style="height: 90px;" />
                                    @else
                                        <img class="hidden-md hidden-lg" src="{{ asset('logo/diakad.png') }}"
                                            alt="Logo Diakad" style="height: 90px;" />
                                    @endif

                                    <img src="https://diakad.sgp1.digitaloceanspaces.com/{{ $sekolah->nm_singkat_sekolah }}/global/logo-sekolah"
                                        alt="Logo Sekolah" style="height:90px;" />
                                    <br>
                                    {{ $sekolah->nm_sekolah }}
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
                                                <input type="text" class="form-control" name="username" required=""
                                                    aria-required="true" aria-invalid="true" value="{{ old('username') }}"
                                                    style="background-color: transparent;">
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
                                        <div class="form-group input-group form-float">
                                            <div class="form-line">
                                                <input type="password" class="form-control" name="password" required=""
                                                    minLength="3" aria-required="true"
                                                    style="background-color: transparent;">
                                                <label class="form-label" style="color: #555;">Password</label>
                                            </div>
                                            <span class="input-group-addon">
                                                <a href="javascript:void(0)" onclick="tooglePassword(this)"><i
                                                        class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <button class="btn btn-block waves-effect"
                                            style="background-color:#235789;color:#fff;padding:10px"
                                            type="submit">LOGIN</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <a href="/forget-password" target="_blank">Lupa Password ?</a>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                <div class="row"
                    style="background-color:#f7f7f7;margin-right:0;margin-left:0;border-bottom-left-radius: 5px;border-bottom-right-radius: 5px;padding-top:25px;padding-bottom:25px ">
                    <div class="row">
                        <div class="col-xs-12 align-center">
                            Powered By <a href="https://edumate.co.id" target="_blank">EDUMATE</a></span>
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
