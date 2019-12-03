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
        <link rel="icon" href="{{asset('favicon.ico')}}" type="image/x-icon">

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

        <script>
            var base_url = document.getElementsByTagName('base')[0].getAttribute('href');
            var role_url = document.getElementsByTagName('meta')[2].getAttribute('content');
        </script>

        <style type="text/css">
            table.table.dataTable{
                width: 100%;
            }

            .bg-btn-submit{
                background-color: #235789;
                color: #fff !important;
            }
        </style>
    </head>

    @yield('content')

    <!-- Jquery Core Js -->
    <!-- <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>


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
    <script src="{{asset('plugins/nprogress-0.2.0//nprogress.js')}}"></script>

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

    <!-- Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>

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