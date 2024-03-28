@extends('app')
@section('meta')
<!-- Meta -->

<style>
    :where([autocomplete=one-time-code]) {
        --otp-digits: 6;
        /* length */
        --otc-ls: 2ch;
        --otc-gap: 1.25;
        /* private consts */
        --_otp-bgsz: calc(var(--otc-ls) + 1ch);

        all: unset;
        background: linear-gradient(90deg,
                var(--otc-bg, #EEE) calc(var(--otc-gap) * var(--otc-ls)),
                transparent 0) 0 0 / var(--_otp-bgsz) 100%;
        caret-color: var(--otc-cc, #333);
        clip-path: inset(0% calc(var(--otc-ls) / 2) 0% 0%);
        font-family: ui-monospace, monospace;
        font-size: var(--otc-fz, 2.5em);
        inline-size: calc(var(--otc-digits) * var(--_otp-bgsz));
        letter-spacing: var(--otc-ls);
        padding-block: var(--otc-pb, 1ch);
        padding-inline-start: calc(((var(--otc-ls) - 1ch) / 2) * var(--otc-gap));
    }

    .input-kode {
        height: 100px;
        font-size: xx-large;
        width: 335px;
        text-transform: uppercase;
    }

    @media only screen and (max-width: 600px) {
        .input-kode {
            height: 30px;
            font-size: large;
            width: 190px;
        }
    }
</style>
@endsection

@section('content')
<!-- <body class="login-page" style="background-color: #006302;"> -->

<body class="login-page" style="background-color: #13172e;">
    <div class="login-box" style="display: flex; justify-content: center; align-items: center; height: 100vh;">
        <div class="card is-login col-md-6">



            <div class="row justify-content-md-center">
                <div class="">
                    <div class="body" id="khusus-login">

                        <form id="kode" action="" method="post" style="display: flex; justify-content:center; align-items:center;">
                            @method('POST')
                            @CSRF
                            <div class="">
                                <h1 class="text-center">KODE FORM</h1>
                                <input type="text" class="input-kode" placeholder="" name="kode" autocomplete="one-time-code" maxlength="6" aria-describedby="sizing-addon1" required>
                            </div>
                        </form>

                    </div>
                </div>
                <div style="display: flex; justify-content:center; align-items:center; margin: 5px;">
                    <button class="btn btn-lg btn-info" type="submit" form="kode">
                        BUKA
                    </button>
                </div>
            </div>
            <div class="row" style="background-color:#f7f7f7;margin-right:0;margin-left:0;border-bottom-left-radius: 5px;border-bottom-right-radius: 5px;padding-top:25px;padding-bottom:25px ">
                <div class="row">
                    <div class="col-xs-12 align-center">
                        <img src="https://diakademik.test/favicon_io/favicon-circle.png" height="8" />Diakad By <a href="https://edumate.id" target="_blank">EDUMATE</a></span>
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

    const input = document.querySelector('[autocomplete=one-time-code');
    input.addEventListener('input', () => input.style.setProperty('--_otp-digit', input.selectionStart));
</script>
<script>
    if ("{{isset($error)}}" === "1") {
        swal({
            title: "Error!",
            text: "Kode Tidak Valid!",
            type: "error",
        });
    }
</script>
@endsection