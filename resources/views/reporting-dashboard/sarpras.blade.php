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
                                        $count_gedung = \App\Models\Gedung::count();
                                        $last_updated_gedung = \App\Models\Gedung::orderBy('updated_at', 'desc')->first();
                                    @endphp
                                    @if($last_updated_gedung)
                                    <div class="text-center"><b>Last updated: {{date_format(date_create($last_updated_gedung->updated_at), 'd M Y H:i')}}</b></div>
                                    @else
                                    <div class="text-center"><b>Last updated: -</b></div>
                                    @endif
                                    <div class="info-box bg-pink hover-expand-effect">
                                        <div class="icon">
                                            <i class="material-icons">domain</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Gedung</div>
                                            <div class="number count-to" data-from="0" data-to="{{$count_gedung}}" data-speed="15" data-fresh-interval="20">{{$count_gedung}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    @php
                                        $count_ruangan = \App\Models\Ruangan::count();
                                        $last_updated_ruangan = \App\Models\Ruangan::orderBy('updated_at', 'desc')->first();
                                    @endphp
                                    @if($last_updated_ruangan)
                                    <div class="text-center"><b>Last updated: {{date_format(date_create($last_updated_ruangan->updated_at), 'd M Y H:i')}}</b></div>
                                    @else
                                    <div class="text-center"><b>Last updated: -</b></div>
                                    @endif
                                    <div class="info-box bg-cyan hover-expand-effect">
                                        <div class="icon">
                                            <i class="material-icons">casino</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Ruangan</div>
                                            <div class="number count-to" data-from="0" data-to="{{$count_ruangan}}" data-speed="15" data-fresh-interval="20">{{$count_ruangan}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    @php
                                        $count_inventaris = \App\Models\InventarisRuangan::count();
                                        $last_updated_inventaris = \App\Models\InventarisRuangan::orderBy('updated_at', 'desc')->first();
                                    @endphp
                                    @if($last_updated_inventaris)
                                    <div class="text-center"><b>Last updated: {{date_format(date_create($last_updated_inventaris->updated_at), 'd M Y H:i')}}</b></div>
                                    @else
                                    <div class="text-center"><b>Last updated: -</b></div>
                                    @endif
                                    <div class="info-box bg-orange hover-expand-effect">
                                        <div class="icon">
                                            <i class="material-icons">build</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Inventaris</div>
                                            <div class="number count-to" data-from="0" data-to="{{$count_inventaris}}" data-speed="15" data-fresh-interval="20">{{$count_inventaris}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    @php
                                        $count_kondisi_ruangan = \App\Models\KondisiRuangan::count();
                                        $last_updated_kondisi_ruangan = \App\Models\KondisiRuangan::orderBy('updated_at', 'desc')->first();
                                    @endphp
                                    @if($last_updated_kondisi_ruangan)
                                    <div class="text-center"><b>Last updated: {{date_format(date_create($last_updated_kondisi_ruangan->updated_at), 'd M Y H:i')}}</b></div>
                                    @else
                                    <div class="text-center"><b>Last updated: -</b></div>
                                    @endif
                                    <div class="info-box bg-teal hover-expand-effect">
                                        <div class="icon">
                                            <i class="material-icons">settings</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Kondisi Ruangan</div>
                                            <div class="number count-to" data-from="0" data-to="{{$count_kondisi_ruangan}}" data-speed="15" data-fresh-interval="20">{{$count_kondisi_ruangan}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    @php
                                        $count_buku_alat = \App\Models\BukuAlat::count();
                                        $last_updated_buku_alat = \App\Models\BukuAlat::orderBy('updated_at', 'desc')->first();
                                    @endphp
                                    @if($last_updated_buku_alat)
                                    <div class="text-center"><b>Last updated: {{date_format(date_create($last_updated_buku_alat->updated_at), 'd M Y H:i')}}</b></div>
                                    @else
                                    <div class="text-center"><b>Last updated: -</b></div>
                                    @endif
                                    <div class="info-box bg-blue hover-expand-effect">
                                        <div class="icon">
                                            <i class="material-icons">book</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Buku Alat</div>
                                            <div class="number count-to" data-from="0" data-to="{{$count_buku_alat}}" data-speed="15" data-fresh-interval="20">{{$count_buku_alat}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    @php
                                        $count_komplain_sarpras = \App\Models\KomplainSarpras::where('is_sudah_perbaikan', 1)->count();
                                        $last_updated_komplain_sarpras = \App\Models\KomplainSarpras::where('is_sudah_perbaikan', 1)->orderBy('updated_at', 'desc')->first();
                                    @endphp
                                    @if($last_updated_komplain_sarpras)
                                    <div class="text-center"><b>Last updated: {{date_format(date_create($last_updated_komplain_sarpras->updated_at), 'd M Y H:i')}}</b></div>
                                    @else
                                    <div class="text-center"><b>Last updated: -</b></div>
                                    @endif
                                    <div class="info-box bg-black hover-expand-effect">
                                        <div class="icon">
                                            <i class="material-icons">inbox</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Komplain Sarpras</div>
                                            <div class="number count-to" data-from="0" data-to="{{$count_komplain_sarpras}}" data-speed="15" data-fresh-interval="20">{{$count_komplain_sarpras}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    @php
                                        $count_perawatan_sarpras = \App\Models\PerawatanSarpras::count();
                                        $last_updated_perawatan_sarpras = \App\Models\PerawatanSarpras::orderBy('updated_at', 'desc')->first();
                                    @endphp
                                    @if($last_updated_perawatan_sarpras)
                                    <div class="text-center"><b>Last updated: {{date_format(date_create($last_updated_perawatan_sarpras->updated_at), 'd M Y H:i')}}</b></div>
                                    @else
                                    <div class="text-center"><b>Last updated: -</b></div>
                                    @endif
                                    <div class="info-box bg-green hover-expand-effect">
                                        <div class="icon">
                                            <i class="material-icons">gesture</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Perawatan Sarpras</div>
                                            <div class="number count-to" data-from="0" data-to="{{$count_perawatan_sarpras}}" data-speed="15" data-fresh-interval="20">{{$count_perawatan_sarpras}}</div>
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