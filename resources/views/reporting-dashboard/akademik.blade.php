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
                                DATA AKADEMIK
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    @php
                                        $count_kalender = \App\Models\JadwalKegiatan::where('id_semester', $semester_aktif->id_semester)->count();
                                        $last_updated_kalender = \App\Models\JadwalKegiatan::where('id_semester', $semester_aktif->id_semester)->orderBy('updated_at', 'desc')->first();
                                    @endphp
                                    @if($last_updated_kalender)
                                    <div class="text-center"><b>Last updated: {{date_format(date_create($last_updated_kalender->updated_at), 'd M Y H:i')}}</b></div>
                                    @else
                                    <div class="text-center"><b>Last updated: -</b></div>
                                    @endif
                                    <div class="info-box bg-pink hover-expand-effect">
                                        <div class="icon">
                                            <i class="material-icons">insert_invitation</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Kalender Akademik</div>
                                            <div class="number count-to" data-from="0" data-to="{{$count_kalender}}" data-speed="15" data-fresh-interval="20">{{$count_kalender}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    @php
                                        $count_class = \App\Models\Kelas::count();
                                        $last_updated_class = \App\Models\Kelas::orderBy('updated_at', 'desc')->first();
                                    @endphp
                                    @if($last_updated_class)
                                    <div class="text-center"><b>Last updated: {{date_format(date_create($last_updated_class->updated_at), 'd M Y H:i')}}</b></div>
                                    @else
                                    <div class="text-center"><b>Last updated: -</b></div>
                                    @endif
                                    <div class="info-box bg-cyan hover-expand-effect">
                                        <div class="icon">
                                            <i class="material-icons">class</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Kelas</div>
                                            <div class="number count-to" data-from="0" data-to="{{$count_class}}" data-speed="15" data-fresh-interval="20">{{$count_class}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    @php
                                        $count_wisuda = \App\Models\PengajuanWisuda::with('periode_wisuda')->whereHas('periode_wisuda', function($q) use ($semester_aktif){
                                            $q->where('id_semester', $semester_aktif->id_semester);
                                        })->count();
                                        $last_updated_wisuda = \App\Models\PengajuanWisuda::with('periode_wisuda')->whereHas('periode_wisuda', function($q) use ($semester_aktif){
                                            $q->where('id_semester', $semester_aktif->id_semester);
                                        })->orderBy('updated_at', 'desc')->first();
                                    @endphp
                                    @if($last_updated_wisuda)
                                    <div class="text-center"><b>Last updated: {{date_format(date_create($last_updated_wisuda->updated_at), 'd M Y H:i')}}</b></div>
                                    @else
                                    <div class="text-center"><b>Last updated: -</b></div>
                                    @endif
                                    <div class="info-box bg-red hover-expand-effect">
                                        <div class="icon">
                                            <i class="material-icons">check_circle</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Pengajuan Wisuda</div>
                                            <div class="number count-to" data-from="0" data-to="{{$count_wisuda}}" data-speed="15" data-fresh-interval="20">{{$count_wisuda}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    @php
                                        $count_kurikulum = \App\Models\Kurikulum::count();
                                        $last_updated_kurikulum = \App\Models\Kurikulum::orderBy('updated_at', 'desc')->first();
                                    @endphp
                                    @if($last_updated_kurikulum)
                                    <div class="text-center"><b>Last updated: {{date_format(date_create($last_updated_kurikulum->updated_at), 'd M Y H:i')}}</b></div>
                                    @else
                                    <div class="text-center"><b>Last updated: -</b></div>
                                    @endif
                                    <div class="info-box bg-black hover-expand-effect">
                                        <div class="icon">
                                            <i class="material-icons">format_align_left</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Kurikulum</div>
                                            <div class="number count-to" data-from="0" data-to="{{$count_kurikulum}}" data-speed="15" data-fresh-interval="20">{{$count_kurikulum}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    @php
                                        $count_mata_pelajaran = \App\Models\MataPelajaran::count();
                                        $last_updated_mata_pelajaran = \App\Models\MataPelajaran::orderBy('updated_at', 'desc')->first();
                                    @endphp
                                    @if($last_updated_mata_pelajaran)
                                    <div class="text-center"><b>Last updated: {{date_format(date_create($last_updated_mata_pelajaran->updated_at), 'd M Y H:i')}}</b></div>
                                    @else
                                    <div class="text-center"><b>Last updated: -</b></div>
                                    @endif
                                    <div class="info-box bg-green hover-expand-effect">
                                        <div class="icon">
                                            <i class="material-icons">border_color</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Mata Pelajaran</div>
                                            <div class="number count-to" data-from="0" data-to="{{$count_mata_pelajaran}}" data-speed="15" data-fresh-interval="20">{{$count_mata_pelajaran}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    @php
                                        $count_kelas_mp = \App\Models\KelasMp::where('id_semester', $semester_aktif->id_semester)->count();
                                        $last_updated_kelas_mp = \App\Models\KelasMp::where('id_semester', $semester_aktif->id_semester)->orderBy('updated_at', 'desc')->first();
                                    @endphp
                                    @if($last_updated_kelas_mp)
                                    <div class="text-center"><b>Last updated: {{date_format(date_create($last_updated_kelas_mp->updated_at), 'd M Y H:i')}}</b></div>
                                    @else
                                    <div class="text-center"><b>Last updated: -</b></div>
                                    @endif
                                    <div class="info-box bg-blue hover-expand-effect">
                                        <div class="icon">
                                            <i class="material-icons">class</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Plotting Kelas</div>
                                            <div class="number count-to" data-from="0" data-to="{{$count_kelas_mp}}" data-speed="15" data-fresh-interval="20">{{$count_kelas_mp}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    @php
                                        $count_pengambilan_mp = \App\Models\PengambilanMp::where('id_semester', $semester_aktif->id_semester)->count();
                                        $last_updated_pengambilan_mp = \App\Models\PengambilanMp::where('id_semester', $semester_aktif->id_semester)->orderBy('updated_at', 'desc')->first();
                                    @endphp
                                    @if($last_updated_pengambilan_mp)
                                    <div class="text-center"><b>Last updated: {{date_format(date_create($last_updated_pengambilan_mp->updated_at), 'd M Y H:i')}}</b></div>
                                    @else
                                    <div class="text-center"><b>Last updated: -</b></div>
                                    @endif
                                    <div class="info-box bg-teal hover-expand-effect">
                                        <div class="icon">
                                            <i class="material-icons">group_work</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Plotting Mapel Siswa</div>
                                            <div class="number count-to" data-from="0" data-to="{{$count_pengambilan_mp}}" data-speed="15" data-fresh-interval="20">{{$count_pengambilan_mp}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    @php
                                        $count_ujian = \App\Models\UjianMp::with('kelas_mp')->whereHas('kelas_mp', function($q) use ($semester_aktif){
                                            $q->where('id_semester', $semester_aktif->id_semester);
                                        })->count();
                                        $last_updated_ujian = \App\Models\UjianMp::with('kelas_mp')->whereHas('kelas_mp', function($q) use ($semester_aktif){
                                            $q->where('id_semester', $semester_aktif->id_semester);
                                        })->orderBy('updated_at', 'desc')->first();
                                    @endphp
                                    @if($last_updated_ujian)
                                    <div class="text-center"><b>Last updated: {{date_format(date_create($last_updated_ujian->updated_at), 'd M Y H:i')}}</b></div>
                                    @else
                                    <div class="text-center"><b>Last updated: -</b></div>
                                    @endif
                                    <div class="info-box bg-orange hover-expand-effect">
                                        <div class="icon">
                                            <i class="material-icons">play_for_work</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Ujian</div>
                                            <div class="number count-to" data-from="0" data-to="{{$count_ujian}}" data-speed="15" data-fresh-interval="20">{{$count_ujian}}</div>
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