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
                                DATA SARANA PRASARANA
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    @php
                                        $count_dokumen = \App\Models\ArsipDokumen::count();
                                        $last_updated_dokumen = \App\Models\ArsipDokumen::orderBy('updated_at', 'desc')->first();
                                    @endphp
                                    @if($last_updated_dokumen)
                                    <div class="text-center"><b>Last updated: {{date_format(date_create($last_updated_dokumen->updated_at), 'd M Y H:i')}}</b></div>
                                    @else
                                    <div class="text-center"><b>Last updated: -</b></div>
                                    @endif
                                    <div class="info-box bg-pink hover-expand-effect">
                                        <div class="icon">
                                            <i class="material-icons">archive</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Arsip</div>
                                            <div class="number count-to" data-from="0" data-to="{{$count_dokumen}}" data-speed="15" data-fresh-interval="20">{{$count_dokumen}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    @php
                                        $count_file = \App\Models\ArsipDokumenFile::count();
                                        $last_updated_file = \App\Models\ArsipDokumenFile::orderBy('updated_at', 'desc')->first();
                                    @endphp
                                    @if($last_updated_file)
                                    <div class="text-center"><b>Last updated: {{date_format(date_create($last_updated_file->updated_at), 'd M Y H:i')}}</b></div>
                                    @else
                                    <div class="text-center"><b>Last updated: -</b></div>
                                    @endif
                                    <div class="info-box bg-cyan hover-expand-effect">
                                        <div class="icon">
                                            <i class="material-icons">attach_file</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">File</div>
                                            <div class="number count-to" data-from="0" data-to="{{$count_file}}" data-speed="15" data-fresh-interval="20">{{$count_file}}</div>
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