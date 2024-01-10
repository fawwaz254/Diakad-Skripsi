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
    <link href="{{ asset('plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="{{ asset('plugins/node-waves/waves.css') }}" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="{{ asset('plugins/animate-css/animate.css') }}" rel="stylesheet" />

    <link href="{{ asset('plugins/multi-select/css/multi-select.css') }}" rel="stylesheet">

    <!-- Toast -->
    <link rel="stylesheet" href="{{ asset('plugins/vex-4.0.1/dist/css/vex.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/vex-4.0.1/dist/css/vex-theme-default.css') }}">

    <!-- Bootstrap Select Css -->
    <link href="{{ asset('plugins/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet" />

    <!-- <link href="{{ asset('plugins/nprogress-0.2.0/nprogress.css') }}" rel="stylesheet"> -->

    <!-- Sweetalert Css -->
    <link href="{{ asset('plugins/sweetalert/sweetalert.css') }}" rel="stylesheet" />

    <!-- JQuery DataTable Css -->
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.5.4/css/buttons.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/select/1.2.7/css/select.dataTables.min.css" rel="stylesheet">

    <!-- Bootstrap Material Datetime Picker Css -->
    <link href="{{ asset('plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}"
        rel="stylesheet" />

    <!-- Custom Css -->
    <link href="{{ asset('css/style.css?v=6') }}" rel="stylesheet">
    <link href="{{ asset('css/loadertemp.css?v=6') }}" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="{{ asset('css/themes/all-themes.css') }}" rel="stylesheet" />

    <!-- Select2 Css -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />

    <link href="{{ asset('plugins/dropzone/dropzone.css') }}" rel="stylesheet">
    <link href="{{ asset('css/pace.css') }}" rel="stylesheet">

    <link href="https://cdn.datatables.net/fixedcolumns/3.3.0/css/fixedColumns.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
        integrity="sha512-SfTiTlX6kk+qitfevl/7LibUOeJWlt9rbyDn92a1DqWOw9vWG2MFoays0sgObmWazO5BQPiFucnnEAjpAB+/Sw=="
        crossorigin="anonymous" />

    <script>
        var base_url = document.getElementsByTagName('base')[0].getAttribute('href');
        var role_url = document.getElementsByTagName('meta')[2].getAttribute('content');
    </script>

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
<!-- <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script data-pace-options='{ "document": false, "startOnPageLoad": false }'
    src="https://cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js"></script>

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

<!-- Autosize Plugin Js -->
<script src="{{ asset('plugins/autosize/autosize.js') }}"></script>

<!-- Jquery DataTable Plugin Js -->
<!-- <script src="{{ asset('plugins/jquery-datatable/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js') }}"></script> -->
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
<!-- <script src="{{ asset('plugins/nprogress-0.2.0//nprogress.js') }}"></script> -->

<!-- SweetAlert Plugin Js -->
<script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}"></script>

<!-- Moment Plugin Js -->
<script src="{{ asset('plugins/momentjs/moment-with-locales.min.js') }}"></script>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"
    integrity="sha256-LlHVI5rUauudM5ZcZaD6hHPHKrA7CSefHHnKgq+/AZc=" crossorigin="anonymous"></script>

<script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.0/js/dataTables.fixedColumns.min.js">
</script>
<script src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment-with-locales.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/id.min.js"></script>

<!-- Signature Pad -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>


{{-- jexcel --}}
<script src="https://bossanova.uk/jspreadsheet/v4/jexcel.js"></script>
<script src="https://jsuites.net/v4/jsuites.js"></script>

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
