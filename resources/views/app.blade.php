<!DOCTYPE html>
<html>

    <head>
        <base href="{{url('/')}}">
        <meta charset="UTF-8">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta content="{{request()->segment(1)}}" name="role">
        <meta name="token" content="{{csrf_token()}}">
        @yield('meta')
        @php
            $theme_name = Request::segment(1);
        @endphp

        @if($theme_name != '')
        <title><?=str_replace('-', ' ', strtoupper($theme_name))?> - Digital Akademik</title>
        @else
        <title>DIAKAD - Digital Akademik</title>
        @endif

        <!-- Favicon-->
        <link rel="icon" href="{{asset('media/logo-64x64.png')}}" type="image/x-icon">

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

        <!-- Bootstrap Core Css -->
        <link href="{{asset('plugins/bootstrap/css/bootstrap.css')}}" rel="stylesheet">

        <!-- Waves Effect Css -->
        <link href="{{asset('plugins/node-waves/waves.css')}}" rel="stylesheet" />

        <!-- Animation Css -->
        <link href="{{asset('plugins/animate-css/animate.css')}}" rel="stylesheet" />

        <!-- Toast -->
        <link rel="stylesheet" href="{{asset('plugins/vex-4.0.1/dist/css/vex.css')}}" >
        <link rel="stylesheet" href="{{asset('plugins/vex-4.0.1/dist/css/vex-theme-flat-attack.css')}}" >

        <!-- Bootstrap Select Css -->
        <link href="{{asset('plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />

        <link href="{{asset('plugins/nprogress-0.2.0/nprogress.css')}}" rel="stylesheet">

        <!-- Sweetalert Css -->
        <link href="{{asset('plugins/sweetalert/sweetalert.css')}}" rel="stylesheet" />

        <!-- JQuery DataTable Css -->
        <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/buttons/1.5.4/css/buttons.dataTables.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/select/1.2.7/css/select.dataTables.min.css" rel="stylesheet">

        <!-- Bootstrap Material Datetime Picker Css -->
        <link href="{{asset('plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}" rel="stylesheet" />

        <!-- Custom Css -->
        <link href="{{asset('css/style.css?v=5')}}" rel="stylesheet">
        <link href="{{asset('css/loadertemp.css?v=6')}}" rel="stylesheet">

        <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
        <link href="{{asset('css/themes/all-themes.css')}}" rel="stylesheet" />

        <!-- Select2 Css -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />

        <link href="{{asset('plugins/dropzone/dropzone.css')}}" rel="stylesheet">

        <script>
            var base_url = document.getElementsByTagName('base')[0].getAttribute('href');
            var role_url = document.getElementsByTagName('meta')[2].getAttribute('content');
        </script>

        <style type="text/css">
            table.table.dataTable{
                width: 100%;
            }

            .dt-buttons{
                padding-bottom:1rem;
            }

            .dt-button-collection .btn{
                display: block;
                width: 100%;
                margin-bottom: 0.25rem;
            }

            .button-page-length.active{
                background-color: #FFC107 !important;
                color: #fff;
            }

            .buttons-columnVisibility.active{
                background-color: #2196F3 !important;
                color: #fff;
            }

            table.dataTable thead .sorting, table.dataTable thead .sorting_asc, table.dataTable thead .sorting_desc{
                background-position: right 0px;
            }

            .bg-btn-submit{
                background-color: #235789;
                color: #fff !important;
            }
            
            .pace {
            -webkit-pointer-events: none;
            pointer-events: none;

            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;

            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            -ms-box-sizing: border-box;
            -o-box-sizing: border-box;
            box-sizing: border-box;

            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            border-radius: 10px;

            -webkit-background-clip: padding-box;
            -moz-background-clip: padding;
            background-clip: padding-box;

            z-index: 2000;
            position: fixed;
            margin: auto;
            top: 12px;
            left: 0;
            right: 0;
            bottom: 0;
            width: 200px;
            height: 50px;
            overflow: hidden;
            }

            .pace .pace-progress {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            -ms-box-sizing: border-box;
            -o-box-sizing: border-box;
            box-sizing: border-box;

            -webkit-border-radius: 2px;
            -moz-border-radius: 2px;
            border-radius: 2px;

            -webkit-background-clip: padding-box;
            -moz-background-clip: padding;
            background-clip: padding-box;

            -webkit-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);

            display: block;
            position: absolute;
            right: 100%;
            margin-right: -7px;
            width: 93%;
            top: 7px;
            height: 14px;
            font-size: 12px;
            background: #29d;
            color: #29d;
            line-height: 60px;
            font-weight: bold;
            font-family: Helvetica, Arial, "Lucida Grande", sans-serif;

            -webkit-box-shadow: 120px 0 #fff, 240px 0 #fff;
            -ms-box-shadow: 120px 0 #fff, 240px 0 #fff;
            box-shadow: 120px 0 #fff, 240px 0 #fff;
            }

            .pace .pace-progress:after {
            content: attr(data-progress-text);
            display: inline-block;
            position: fixed;
            width: 45px;
            text-align: right;
            right: 0;
            padding-right: 16px;
            top: 4px;
            }

            .pace .pace-progress[data-progress-text="0%"]:after { right: -200px }
            .pace .pace-progress[data-progress-text="1%"]:after { right: -198.14px }
            .pace .pace-progress[data-progress-text="2%"]:after { right: -196.28px }
            .pace .pace-progress[data-progress-text="3%"]:after { right: -194.42px }
            .pace .pace-progress[data-progress-text="4%"]:after { right: -192.56px }
            .pace .pace-progress[data-progress-text="5%"]:after { right: -190.7px }
            .pace .pace-progress[data-progress-text="6%"]:after { right: -188.84px }
            .pace .pace-progress[data-progress-text="7%"]:after { right: -186.98px }
            .pace .pace-progress[data-progress-text="8%"]:after { right: -185.12px }
            .pace .pace-progress[data-progress-text="9%"]:after { right: -183.26px }
            .pace .pace-progress[data-progress-text="10%"]:after { right: -181.4px }
            .pace .pace-progress[data-progress-text="11%"]:after { right: -179.54px }
            .pace .pace-progress[data-progress-text="12%"]:after { right: -177.68px }
            .pace .pace-progress[data-progress-text="13%"]:after { right: -175.82px }
            .pace .pace-progress[data-progress-text="14%"]:after { right: -173.96px }
            .pace .pace-progress[data-progress-text="15%"]:after { right: -172.1px }
            .pace .pace-progress[data-progress-text="16%"]:after { right: -170.24px }
            .pace .pace-progress[data-progress-text="17%"]:after { right: -168.38px }
            .pace .pace-progress[data-progress-text="18%"]:after { right: -166.52px }
            .pace .pace-progress[data-progress-text="19%"]:after { right: -164.66px }
            .pace .pace-progress[data-progress-text="20%"]:after { right: -162.8px }
            .pace .pace-progress[data-progress-text="21%"]:after { right: -160.94px }
            .pace .pace-progress[data-progress-text="22%"]:after { right: -159.08px }
            .pace .pace-progress[data-progress-text="23%"]:after { right: -157.22px }
            .pace .pace-progress[data-progress-text="24%"]:after { right: -155.36px }
            .pace .pace-progress[data-progress-text="25%"]:after { right: -153.5px }
            .pace .pace-progress[data-progress-text="26%"]:after { right: -151.64px }
            .pace .pace-progress[data-progress-text="27%"]:after { right: -149.78px }
            .pace .pace-progress[data-progress-text="28%"]:after { right: -147.92px }
            .pace .pace-progress[data-progress-text="29%"]:after { right: -146.06px }
            .pace .pace-progress[data-progress-text="30%"]:after { right: -144.2px }
            .pace .pace-progress[data-progress-text="31%"]:after { right: -142.34px }
            .pace .pace-progress[data-progress-text="32%"]:after { right: -140.48px }
            .pace .pace-progress[data-progress-text="33%"]:after { right: -138.62px }
            .pace .pace-progress[data-progress-text="34%"]:after { right: -136.76px }
            .pace .pace-progress[data-progress-text="35%"]:after { right: -134.9px }
            .pace .pace-progress[data-progress-text="36%"]:after { right: -133.04px }
            .pace .pace-progress[data-progress-text="37%"]:after { right: -131.18px }
            .pace .pace-progress[data-progress-text="38%"]:after { right: -129.32px }
            .pace .pace-progress[data-progress-text="39%"]:after { right: -127.46px }
            .pace .pace-progress[data-progress-text="40%"]:after { right: -125.6px }
            .pace .pace-progress[data-progress-text="41%"]:after { right: -123.74px }
            .pace .pace-progress[data-progress-text="42%"]:after { right: -121.88px }
            .pace .pace-progress[data-progress-text="43%"]:after { right: -120.02px }
            .pace .pace-progress[data-progress-text="44%"]:after { right: -118.16px }
            .pace .pace-progress[data-progress-text="45%"]:after { right: -116.3px }
            .pace .pace-progress[data-progress-text="46%"]:after { right: -114.44px }
            .pace .pace-progress[data-progress-text="47%"]:after { right: -112.58px }
            .pace .pace-progress[data-progress-text="48%"]:after { right: -110.72px }
            .pace .pace-progress[data-progress-text="49%"]:after { right: -108.86px }
            .pace .pace-progress[data-progress-text="50%"]:after { right: -107px }
            .pace .pace-progress[data-progress-text="51%"]:after { right: -105.14px }
            .pace .pace-progress[data-progress-text="52%"]:after { right: -103.28px }
            .pace .pace-progress[data-progress-text="53%"]:after { right: -101.42px }
            .pace .pace-progress[data-progress-text="54%"]:after { right: -99.56px }
            .pace .pace-progress[data-progress-text="55%"]:after { right: -97.7px }
            .pace .pace-progress[data-progress-text="56%"]:after { right: -95.84px }
            .pace .pace-progress[data-progress-text="57%"]:after { right: -93.98px }
            .pace .pace-progress[data-progress-text="58%"]:after { right: -92.12px }
            .pace .pace-progress[data-progress-text="59%"]:after { right: -90.26px }
            .pace .pace-progress[data-progress-text="60%"]:after { right: -88.4px }
            .pace .pace-progress[data-progress-text="61%"]:after { right: -86.53999999999999px }
            .pace .pace-progress[data-progress-text="62%"]:after { right: -84.68px }
            .pace .pace-progress[data-progress-text="63%"]:after { right: -82.82px }
            .pace .pace-progress[data-progress-text="64%"]:after { right: -80.96000000000001px }
            .pace .pace-progress[data-progress-text="65%"]:after { right: -79.1px }
            .pace .pace-progress[data-progress-text="66%"]:after { right: -77.24px }
            .pace .pace-progress[data-progress-text="67%"]:after { right: -75.38px }
            .pace .pace-progress[data-progress-text="68%"]:after { right: -73.52px }
            .pace .pace-progress[data-progress-text="69%"]:after { right: -71.66px }
            .pace .pace-progress[data-progress-text="70%"]:after { right: -69.8px }
            .pace .pace-progress[data-progress-text="71%"]:after { right: -67.94px }
            .pace .pace-progress[data-progress-text="72%"]:after { right: -66.08px }
            .pace .pace-progress[data-progress-text="73%"]:after { right: -64.22px }
            .pace .pace-progress[data-progress-text="74%"]:after { right: -62.36px }
            .pace .pace-progress[data-progress-text="75%"]:after { right: -60.5px }
            .pace .pace-progress[data-progress-text="76%"]:after { right: -58.64px }
            .pace .pace-progress[data-progress-text="77%"]:after { right: -56.78px }
            .pace .pace-progress[data-progress-text="78%"]:after { right: -54.92px }
            .pace .pace-progress[data-progress-text="79%"]:after { right: -53.06px }
            .pace .pace-progress[data-progress-text="80%"]:after { right: -51.2px }
            .pace .pace-progress[data-progress-text="81%"]:after { right: -49.34px }
            .pace .pace-progress[data-progress-text="82%"]:after { right: -47.480000000000004px }
            .pace .pace-progress[data-progress-text="83%"]:after { right: -45.62px }
            .pace .pace-progress[data-progress-text="84%"]:after { right: -43.76px }
            .pace .pace-progress[data-progress-text="85%"]:after { right: -41.9px }
            .pace .pace-progress[data-progress-text="86%"]:after { right: -40.04px }
            .pace .pace-progress[data-progress-text="87%"]:after { right: -38.18px }
            .pace .pace-progress[data-progress-text="88%"]:after { right: -36.32px }
            .pace .pace-progress[data-progress-text="89%"]:after { right: -34.46px }
            .pace .pace-progress[data-progress-text="90%"]:after { right: -32.6px }
            .pace .pace-progress[data-progress-text="91%"]:after { right: -30.740000000000002px }
            .pace .pace-progress[data-progress-text="92%"]:after { right: -28.880000000000003px }
            .pace .pace-progress[data-progress-text="93%"]:after { right: -27.02px }
            .pace .pace-progress[data-progress-text="94%"]:after { right: -25.16px }
            .pace .pace-progress[data-progress-text="95%"]:after { right: -23.3px }
            .pace .pace-progress[data-progress-text="96%"]:after { right: -21.439999999999998px }
            .pace .pace-progress[data-progress-text="97%"]:after { right: -19.58px }
            .pace .pace-progress[data-progress-text="98%"]:after { right: -17.72px }
            .pace .pace-progress[data-progress-text="99%"]:after { right: -15.86px }
            .pace .pace-progress[data-progress-text="100%"]:after { right: -14px }


            .pace .pace-activity {
            position: absolute;
            width: 100%;
            height: 28px;
            z-index: 2001;
            box-shadow: inset 0 0 0 2px #29d, inset 0 0 0 7px #FFF;
            border-radius: 10px;
            }

            .pace.pace-inactive {
            display: none;
            }

        </style>
    </head>

    @yield('content')

    <!-- Jquery Core Js -->
    <!-- <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script data-pace-options='{ "ajax": false, "document": false }' src="https://cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="{{asset('plugins/bootstrap/js/bootstrap.js')}}"></script>

    <!-- Select Plugin Js -->
    <script src="{{asset('plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="{{asset('plugins/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>

    <!-- Bootstrap Notify Plugin Js -->
    <script src="{{asset('plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="{{asset('plugins/node-waves/waves.js')}}"></script>

    <!-- Validation Plugin Js -->
    <script src="{{asset('plugins/jquery-validation/jquery.validate.js')}}"></script>

    <!-- Toast -->
    <script src="{{asset('plugins/vex-4.0.1/dist/js/vex.combined.min.js')}}"></script>	

    <!-- Autosize Plugin Js -->
    <script src="{{asset('plugins/autosize/autosize.js')}}"></script>

    <!-- Jquery DataTable Plugin Js -->
    <!-- <script src="{{asset('plugins/jquery-datatable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js')}}"></script> -->
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.4/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.2.7/js/dataTables.select.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.colVis.min.js"></script>

    <!-- NP Progress -->
    <!-- <script src="{{asset('plugins/nprogress-0.2.0//nprogress.js')}}"></script> -->

    <!-- SweetAlert Plugin Js -->
    <script src="{{asset('plugins/sweetalert/sweetalert.min.js')}}"></script>

    <!-- Moment Plugin Js -->
    <script src="{{asset('plugins/momentjs/moment-with-locales.min.js')}}"></script>

    <!-- Bootstrap Material Datetime Picker Plugin Js -->
    <script src="{{asset('plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js')}}"></script>

    <!-- Custom Js -->
    <script src="{{asset('js/admin.js?v0')}}"></script>
    <script src="{{asset('js/pages/ui/dialogs.js')}}"></script>
    <script src="{{asset('js/demo.js')}}"></script>

    <!-- Dropzone Plugin Js -->
    <script src="{{asset('plugins/dropzone/dropzone.js')}}"></script>

    <!-- Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js" integrity="sha256-LlHVI5rUauudM5ZcZaD6hHPHKrA7CSefHHnKgq+/AZc=" crossorigin="anonymous"></script>

    <script>
        $(function () {
            var loadingdt = '<div class="progressbar"><div class="stylization"></div><br><p style="font-size:9px;">Loading, mohon rehat sejenak...</p></div>';

            vex.defaultOptions.className = 'vex-theme-flat-attack';
            $.extend( $.fn.dataTable.defaults, {
                language: {
                    "processing": "" +loadingdt+""
                },
            });

            @if(session()->has('toast'))
                $(window).load(function(){
                    vex.dialog.alert('{{session('toast')}}');
                });
            @endif

            $('.form-validation').validate({
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
                }
            });
        });
    </script>
    @yield('js')
</html>