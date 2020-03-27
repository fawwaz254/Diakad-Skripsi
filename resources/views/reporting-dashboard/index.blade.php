@extends('app')
@section('meta')
<!-- Meta -->
@endsection

@section('content')
<body>
    <section class="section">
        <div class="container-fluid">
            <div class="block-header">
            </div>
            <div class="block-header">
                <h2>{{\App\Models\Sekolah::first()->nm_sekolah}}</h2>
            </div>

            <!-- Widgets -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Pilih Departemen
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
                                    <a class="btn btn-success btn-lg btn-block waves-effect" href="{{url('reporting-dashboard/akademik')}}">Akademik</a>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
                                    <a class="btn btn-primary btn-lg btn-block waves-effect" href="{{url('reporting-dashboard/bk-kesiswaan')}}">BK & Kesiswaan</a>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
                                    <a class="btn btn-danger btn-lg btn-block waves-effect" href="{{url('reporting-dashboard/keuangan')}}">Keuangan</a>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
                                    <a class="btn btn-warning btn-lg btn-block waves-effect" href="{{url('reporting-dashboard/sarpras')}}">Sarpras</a>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
                                    <a class="btn bg-teal btn-lg btn-block waves-effect" href="{{url('reporting-dashboard/sekretariat')}}">Sekretariat</a>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
                                    <a class="btn bg-cyan btn-lg btn-block waves-effect" href="{{url('reporting-dashboard/sumber-daya')}}">Sumber Daya</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
@endsection

@section('js')
<!-- Javascript -->
@endsection