@extends('app')
@section('meta')
<!-- Meta -->
@endsection

@section('content')
    @php
        $semester_aktif = \App\Models\Semester::where('is_aktif_semester','=',1)->first();
    @endphp
<body>
    <section class="section">
        <div class="container-fluid">
            <div class="block-header">
            </div>
            <div class="block-header">
                <h2><a class="btn bg-blue waves-effect" href="{{url('reporting-dashboard')}}"><i class="material-icons">backspace</i><span>Kembali</span></a>&nbsp; &nbsp; AKADEMIK - {{\App\Models\Sekolah::first()->nm_sekolah}}</h2>
            </div>

            <!-- Widgets -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                DATA SUMBER DAYA
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    @php
                                        $count_guru = \App\Models\Guru::count();
                                        $last_updated_guru = \App\Models\Guru::orderBy('updated_at', 'desc')->first();
                                    @endphp
                                    @if($last_updated_guru)
                                    <div class="text-center"><b>Last updated: {{date_format(date_create($last_updated_guru->updated_at), 'd M Y H:i')}}</b></div>
                                    @else
                                    <div class="text-center"><b>Last updated: -</b></div>
                                    @endif
                                    <div class="info-box bg-pink hover-expand-effect">
                                        <div class="icon">
                                            <i class="material-icons">school</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Guru</div>
                                            <div class="number count-to" data-from="0" data-to="{{$count_guru}}" data-speed="15" data-fresh-interval="20">{{$count_guru}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    @php
                                        $count_staff = \App\Models\Staff::count();
                                        $last_updated_staff = \App\Models\Staff::orderBy('updated_at', 'desc')->first();
                                    @endphp
                                    @if($last_updated_staff)
                                    <div class="text-center"><b>Last updated: {{date_format(date_create($last_updated_staff->updated_at), 'd M Y H:i')}}</b></div>
                                    @else
                                    <div class="text-center"><b>Last updated: -</b></div>
                                    @endif
                                    <div class="info-box bg-cyan hover-expand-effect">
                                        <div class="icon">
                                            <i class="material-icons">person_outline</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Tendik</div>
                                            <div class="number count-to" data-from="0" data-to="{{$count_staff}}" data-speed="15" data-fresh-interval="20">{{$count_staff}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Widgets -->

        </div>
    </section>
</body>
@endsection

@section('js')
<!-- Javascript -->
@endsection