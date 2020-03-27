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
                                DATA BK & KESISWAAN
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    @php
                                        $count_prestasi = \App\Models\PrestasiSiswa::where('id_semester', $semester_aktif->id_semester)->count();
                                        $last_updated_prestasi = \App\Models\PrestasiSiswa::where('id_semester', $semester_aktif->id_semester)->orderBy('updated_at', 'desc')->first();
                                    @endphp
                                    @if($last_updated_prestasi)
                                    <div class="text-center"><b>Last updated: {{date_format(date_create($last_updated_prestasi->updated_at), 'd M Y H:i')}}</b></div>
                                    @else
                                    <div class="text-center"><b>Last updated: -</b></div>
                                    @endif
                                    <div class="info-box bg-pink hover-expand-effect">
                                        <div class="icon">
                                            <i class="material-icons">done_all</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Prestasi</div>
                                            <div class="number count-to" data-from="0" data-to="{{$count_prestasi}}" data-speed="15" data-fresh-interval="20">{{$count_prestasi}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    @php
                                        $count_beasiswa = \App\Models\BeasiswaSiswa::count();
                                        $last_updated_beasiswa = \App\Models\BeasiswaSiswa::orderBy('updated_at', 'desc')->first();
                                    @endphp
                                    @if($last_updated_beasiswa)
                                    <div class="text-center"><b>Last updated: {{date_format(date_create($last_updated_beasiswa->updated_at), 'd M Y H:i')}}</b></div>
                                    @else
                                    <div class="text-center"><b>Last updated: -</b></div>
                                    @endif
                                    <div class="info-box bg-cyan hover-expand-effect">
                                        <div class="icon">
                                            <i class="material-icons">card_giftcard</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Beasiswa</div>
                                            <div class="number count-to" data-from="0" data-to="{{$count_beasiswa}}" data-speed="15" data-fresh-interval="20">{{$count_beasiswa}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    @php
                                        $count_home_visit = \App\Models\HomeVisit::where('is_berkas_lengkap', 1)->where('id_semester', $semester_aktif->id_semester)->count();
                                        $last_updated_home_visit = \App\Models\HomeVisit::where('is_berkas_lengkap', 1)->where('id_semester', $semester_aktif->id_semester)->orderBy('updated_at', 'desc')->first();
                                    @endphp
                                    @if($last_updated_home_visit)
                                    <div class="text-center"><b>Last updated: {{date_format(date_create($last_updated_home_visit->updated_at), 'd M Y H:i')}}</b></div>
                                    @else
                                    <div class="text-center"><b>Last updated: -</b></div>
                                    @endif
                                    <div class="info-box bg-orange hover-expand-effect">
                                        <div class="icon">
                                            <i class="material-icons">home</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Home Visit</div>
                                            <div class="number count-to" data-from="0" data-to="{{$count_home_visit}}" data-speed="15" data-fresh-interval="20">{{$count_home_visit}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    @php
                                        $count_ekskul = \App\Models\PresensiEkskul::where('id_semester', $semester_aktif->id_semester)->count();
                                        $last_updated_ekskul = \App\Models\PresensiEkskul::where('id_semester', $semester_aktif->id_semester)->orderBy('updated_at', 'desc')->first();
                                    @endphp
                                    @if($last_updated_ekskul)
                                    <div class="text-center"><b>Last updated: {{date_format(date_create($last_updated_ekskul->updated_at), 'd M Y H:i')}}</b></div>
                                    @else
                                    <div class="text-center"><b>Last updated: -</b></div>
                                    @endif
                                    <div class="info-box bg-teal hover-expand-effect">
                                        <div class="icon">
                                            <i class="material-icons">accessibility</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Presensi Ekskul</div>
                                            <div class="number count-to" data-from="0" data-to="{{$count_ekskul}}" data-speed="15" data-fresh-interval="20">{{$count_ekskul}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    @php
                                        $count_pelanggaran = \App\Models\PelanggaranSiswa::where('id_semester', $semester_aktif->id_semester)->count();
                                        $last_updated_pelanggaran = \App\Models\PelanggaranSiswa::where('id_semester', $semester_aktif->id_semester)->orderBy('updated_at', 'desc')->first();
                                    @endphp
                                    @if($last_updated_pelanggaran)
                                    <div class="text-center"><b>Last updated: {{date_format(date_create($last_updated_pelanggaran->updated_at), 'd M Y H:i')}}</b></div>
                                    @else
                                    <div class="text-center"><b>Last updated: -</b></div>
                                    @endif
                                    <div class="info-box bg-blue hover-expand-effect">
                                        <div class="icon">
                                            <i class="material-icons">bug_report</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Pelanggaran Siswa</div>
                                            <div class="number count-to" data-from="0" data-to="{{$count_pelanggaran}}" data-speed="15" data-fresh-interval="20">{{$count_pelanggaran}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    @php
                                        $count_tindakan = \App\Models\PelanggaranSiswa::where('is_sudah_tindakan', 1)->where('id_semester', $semester_aktif->id_semester)->count();
                                        $last_updated_tindakan = \App\Models\PelanggaranSiswa::where('is_sudah_tindakan', 1)->where('id_semester', $semester_aktif->id_semester)->orderBy('updated_at', 'desc')->first();
                                    @endphp
                                    @if($last_updated_tindakan)
                                    <div class="text-center"><b>Last updated: {{date_format(date_create($last_updated_tindakan->updated_at), 'd M Y H:i')}}</b></div>
                                    @else
                                    <div class="text-center"><b>Last updated: -</b></div>
                                    @endif
                                    <div class="info-box bg-black hover-expand-effect">
                                        <div class="icon">
                                            <i class="material-icons">gavel</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Tindakan BK</div>
                                            <div class="number count-to" data-from="0" data-to="{{$count_tindakan}}" data-speed="15" data-fresh-interval="20">{{$count_tindakan}}</div>
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