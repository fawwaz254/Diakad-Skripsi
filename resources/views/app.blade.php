<!DOCTYPE html>
<html>

<head>
    <base href="{{ url('/') }}">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta content="{{ request()->segment(1) }}" name="role">
    <meta name="token" content="{{ csrf_token() }}">
    @yield('meta')
    @php
        $theme_name = Request::segment(1);
    @endphp

    @if ($theme_name != '')
        <title><?= str_replace('-', ' ', strtoupper($theme_name)) ?> - Sekolah Berbasis Teknologi by EDUMATE</title>
    @else
        <title>{{ strtoupper(env('APP_NAME', 'diakad')) }} - Sekolah Berbasis Teknologi by EDUMATE</title>
    @endif

    <!-- Favicon-->
    <link rel="icon" href="{{ asset('favicon_io/favicon-circle.png') }}" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet"
        type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="{{ asset('plugins/bootstrap/css/bootstrap.css') }}?v=2" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="{{ asset('plugins/node-waves/waves.css') }}" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="{{ asset('plugins/animate-css/animate.css') }}" rel="stylesheet" />

    <link href="{{ asset('plugins/multi-select/css/multi-select.css') }}" rel="stylesheet">

    <!-- Toast -->
    <link href="{{ asset('vendor/css/toastify/toastify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/vex-4.0.1/dist/css/vex.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/vex-4.0.1/dist/css/vex-theme-default.css') }}" rel="stylesheet">

    <!-- Bootstrap Select Css -->
    <link href="{{ asset('plugins/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet" />

    <!-- <link href="{{ asset('plugins/nprogress-0.2.0/nprogress.css') }}" rel="stylesheet"> -->

    <!-- Sweetalert Css -->
    <link href="{{ asset('plugins/sweetalert/sweetalert.css') }}" rel="stylesheet" />

    <!-- JQuery DataTable Css -->
    <link href="{{ asset('vendor/css/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/css/datatables/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/css/datatables/buttons.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/css/datatables/select.dataTables.min.css') }}" rel="stylesheet">

    <!-- Bootstrap Material Datetime Picker Css -->
    <link href="{{ asset('plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}"
        rel="stylesheet" />

    <!-- Custom Css -->
    <link href="{{ asset('css/style.css?v=7') }}" rel="stylesheet">
    <link href="{{ asset('css/loadertemp.css?v=6') }}" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="{{ asset('css/themes/all-themes.css') }}" rel="stylesheet" />

    <!-- Select2 Css -->
    <link href="{{ asset('vendor/css/select2/select2.min.css') }}" rel="stylesheet">

    <link href="{{ asset('plugins/dropzone/dropzone.css') }}" rel="stylesheet">
    <link href="{{ asset('css/pace.css') }}" rel="stylesheet">

    <link href="{{ asset('vendor/css/datatables/fixedColumns.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/css/datatables/fixedHeader.dataTables.min.css') }}" rel="stylesheet">

    <link href="{{ asset('plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

    <script>
        var base_url = document.getElementsByTagName('base')[0].getAttribute('href');
        var role_url = document.getElementsByTagName('meta')[2].getAttribute('content');
    </script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}

    <style type="text/css">
        table.table.dataTable {
            width: 100%;
        }

        .dt-buttons {
            padding-bottom: 1rem;
        }

        .dt-button-collection .btn {
            display: block;
            width: 100%;
            margin-bottom: 0.25rem;
        }

        .button-page-length.active {
            background-color: #FFC107 !important;
            color: #fff;
        }

        .buttons-columnVisibility.active {
            background-color: #2196F3 !important;
            color: #fff;
        }

        table.dataTable thead .sorting,
        table.dataTable thead .sorting_asc,
        table.dataTable thead .sorting_desc {
            background-position: right 0px;
        }

        .bg-btn-submit {
            background-color: #235789;
            color: #fff !important;
        }

        .capitalize {
            text-transform: capitalize;
        }
    </style>
</head>

@yield('content')



<!-- Jquery Core Js -->
<script src="{{ asset('vendor/js/jquery/jquery.min.js') }}"></script>
<script data-pace-options='{ "document": false, "startOnPageLoad": false }'
    src="{{ asset('vendor/js/pace/pace.min.js') }}"></script>

<!-- Bootstrap Core Js -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.js') }}"></script>

<!-- Select Plugin Js -->
<script src="{{ asset('plugins/bootstrap-select/js/bootstrap-select.js') }}"></script>

<!-- Slimscroll Plugin Js -->
<script src="{{ asset('plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>

<!-- Bootstrap Notify Plugin Js -->
<script src="{{ asset('plugins/bootstrap-notify/bootstrap-notify.js') }}"></script>

<!-- Waves Effect Plugin Js -->
<script src="{{ asset('plugins/node-waves/waves.js') }}"></script>

<!-- Validation Plugin Js -->
<script src="{{ asset('plugins/jquery-validation/jquery.validate.js') }}"></script>

<!-- Toast -->
<script src="{{ asset('plugins/vex-4.0.1/dist/js/vex.combined.min.js') }}"></script>
<script src="{{ asset('vendor/js/toastify/toastify.min.js') }}"></script>

<!-- Autosize Plugin Js -->
<script src="{{ asset('plugins/autosize/autosize.js') }}"></script>

<!-- Jquery DataTable Plugin Js -->

<script src="{{ asset('vendor/js/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/js/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('vendor/js/datatables/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('vendor/js/datatables/dataTables.select.min.js') }}"></script>
<script src="{{ asset('vendor/js/datatables/buttons.flash.min.js') }}"></script>
<script src="{{ asset('vendor/js/datatables/jszip.min.js') }}"></script>
<script src="{{ asset('vendor/js/datatables/pdfmake.min.js') }}"></script>
<script src="{{ asset('vendor/js/datatables/vfs_fonts.js') }}"></script>
<script src="{{ asset('vendor/js/datatables/buttons.html5.min.js') }}"></script>
<script src="{{ asset('vendor/js/datatables/buttons.print.min.js') }}"></script>
<script src="{{ asset('vendor/js/datatables/buttons.colVis.min.js') }}"></script>

<!-- NP Progress -->
<!-- <script src="{{ asset('plugins/nprogress-0.2.0//nprogress.js') }}"></script> -->

<!-- SweetAlert Plugin Js -->
<script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}"></script>

<!-- Moment Plugin Js -->
<script src="{{ asset('plugins/momentjs/moment-with-locales.min.js') }}"></script>

<!-- HTML5 QrCode -->
<script src="{{ asset('vendor/js/qrcode/html5-qrcode.min.js') }}"></script>

<!-- Bootstrap Material Datetime Picker Plugin Js -->
<script src="{{ asset('plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}">
</script>

<!-- Multi Select Plugin Js -->
<script src="{{ asset('plugins/multi-select/js/jquery.multi-select.js') }}"></script>

<!-- Custom Js -->
<script src="{{ asset('js/admin.js?v0') }}"></script>
<script src="{{ asset('js/pages/ui/dialogs.js') }}"></script>
<script src="{{ asset('js/demo.js') }}"></script>

<!-- Dropzone Plugin Js -->
<script src="{{ asset('plugins/dropzone/dropzone.js') }}"></script>

<!-- Select2 -->
<script src="{{ asset('vendor/js/select2/select2.min.js') }}"></script>

<script src="{{ asset('vendor/js/numeral.min.js') }}"></script>

<script src="{{ asset('vendor/js/datatables/dataTables.fixedColumns.min.js') }}"></script>
<script src="{{ asset('vendor/js/datatables/dataTables.fixedHeader.min.js') }}"></script>

<script src="{{ asset('vendor/js/moment-with-locales.min.js') }}"></script>
<script src="{{ asset('vendor/js/locale-id.min.js') }}"></script>

{{-- cart.js --}}
<script src="{{ asset('vendor/js/charts/chart.min.js') }}"></script>

<!-- ApexChart.js #humas-chart -->
<script src="{{ asset('vendor/js/charts/apexcharts.min.js') }}"></script>

<!-- Signature Pad -->
<script src="{{ asset('vendor/js/signature/signature_pad.umd.min.js') }}"></script>

{{-- jexcel --}}
<script src="{{ asset('js/jexcel/jexcel.js') }}"></script>
<script src="{{ asset('js/jexcel/jsuites.js') }}"></script>

<script>
    $(function() {
        var loadingdt =
            '<div class="progressbar"><div class="stylization"></div><br><p style="font-size:9px;">Loading, mohon rehat sejenak...</p></div>';

        vex.defaultOptions.className = 'vex-theme-default';
        $.extend($.fn.dataTable.defaults, {
            language: {
                "processing": "" + loadingdt + ""
            },
        });

        @if (session()->has('toast'))
            $(window).load(function() {
                vex.dialog.alert('{{ session('toast') }}');
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
            highlight: function(input) {
                $(input).parents('.form-line').addClass('error');
            },
            unhighlight: function(input) {
                $(input).parents('.form-line').removeClass('error');
            },
            errorPlacement: function(error, element) {
                $(element).parents('.form-group').append(error);
            }
        });
    });

    // const evtSource = new EventSource(base_url + '/api/testing' , { withCredentials: true } );
    // evtSource.onmessage = function(event) {
    //     const newElement = document.createElement("p");
    //     const eventList = document.getElementById("khusus-login");

    //     newElement.textContent = "message: " + event.data;
    //     eventList.appendChild(newElement);
    // }
</script>


@if (isset(request()->auth_data->google_analytic_id))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ request()->auth_data->google_analytic_id }}">
    </script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', '{{ request()->auth_data->google_analytic_id }}');
    </script>
@endif

@yield('js')

</html>
