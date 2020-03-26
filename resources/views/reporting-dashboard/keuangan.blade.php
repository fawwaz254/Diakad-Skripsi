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
                                DATA KEUANGAN
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    @php
                                        $count_biaya = \App\Models\DetailBiaya::with('biaya_sekolah')->whereHas('biaya_sekolah', function($q) use ($semester_aktif){
                                            $q->where('id_semester', $semester_aktif->id_semester);
                                        })->count();
                                        $last_updated_biaya = \App\Models\DetailBiaya::with('biaya_sekolah')->whereHas('biaya_sekolah', function($q) use ($semester_aktif){
                                            $q->where('id_semester', $semester_aktif->id_semester);
                                        })->orderBy('updated_at', 'desc')->first();
                                    @endphp
                                    @if($last_updated_biaya)
                                    <div class="text-center"><b>Last updated: {{date_format(date_create($last_updated_biaya->updated_at), 'd M Y H:i')}}</b></div>
                                    @else
                                    <div class="text-center"><b>Last updated: -</b></div>
                                    @endif
                                    <div class="info-box bg-orange hover-expand-effect">
                                        <div class="icon">
                                            <i class="material-icons">attach_money</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Biaya Sekolah</div>
                                            <div class="number count-to" data-from="0" data-to="{{$count_biaya}}" data-speed="15" data-fresh-interval="20">{{$count_biaya}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    @php
                                        $count_kelompok_biaya_siswa = \App\Models\Siswa::get();
                                        $last_updated_kelompok_biaya_siswa = \App\Models\Siswa::orderBy('updated_at', 'desc')->first();
                                    @endphp
                                    @if($last_updated_kelompok_biaya_siswa)
                                    <div class="text-center"><b>Last updated: {{date_format(date_create($last_updated_kelompok_biaya_siswa->updated_at), 'd M Y H:i')}}</b></div>
                                    @else
                                    <div class="text-center"><b>Last updated: -</b></div>
                                    @endif
                                    <div class="info-box bg-cyan hover-expand-effect">
                                        <div class="icon">
                                            <i class="material-icons">group_add</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Kelompok Biaya Siswa</div>
                                            <div class="number">{{$count_kelompok_biaya_siswa->where('id_kelompok_biaya', null)->count()}} / {{$count_kelompok_biaya_siswa->count()}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    @php
                                        $count_tagihan = \App\Models\TagihanBiaya::with('detail_biaya', 'detail_biaya.biaya_sekolah')->whereHas('detail_biaya.biaya_sekolah', function($q) use ($semester_aktif){
                                            $q->where('id_semester', $semester_aktif->id_semester);
                                        })->count();
                                        $last_updated_tagihan = \App\Models\TagihanBiaya::with('detail_biaya', 'detail_biaya.biaya_sekolah')->whereHas('detail_biaya.biaya_sekolah', function($q) use ($semester_aktif){
                                            $q->where('id_semester', $semester_aktif->id_semester);
                                        })->orderBy('updated_at', 'desc')->first();
                                    @endphp
                                    @if($last_updated_tagihan)
                                    <div class="text-center"><b>Last updated: {{date_format(date_create($last_updated_tagihan->updated_at), 'd M Y H:i')}}</b></div>
                                    @else
                                    <div class="text-center"><b>Last updated: -</b></div>
                                    @endif
                                    <div class="info-box bg-pink hover-expand-effect">
                                        <div class="icon">
                                            <i class="material-icons">view_array</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Tagihan</div>
                                            <div class="number count-to" data-from="0" data-to="{{$count_tagihan}}" data-speed="15" data-fresh-interval="20">{{$count_tagihan}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    @php
                                        $count_pembayaran = \App\Models\PembayaranBiaya::where('id_semester_bayar', $semester_aktif->id_semester)->count();
                                        $last_updated_pembayaran = \App\Models\PembayaranBiaya::where('id_semester_bayar', $semester_aktif->id_semester)->orderBy('updated_at', 'desc')->first();
                                    @endphp
                                    @if($last_updated_pembayaran)
                                    <div class="text-center"><b>Last updated: {{date_format(date_create($last_updated_pembayaran->updated_at), 'd M Y H:i')}}</b></div>
                                    @else
                                    <div class="text-center"><b>Last updated: -</b></div>
                                    @endif
                                    <div class="info-box bg-teal hover-expand-effect">
                                        <div class="icon">
                                            <i class="material-icons">payment</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Pembayaran</div>
                                            <div class="number count-to" data-from="0" data-to="{{$count_pembayaran}}" data-speed="15" data-fresh-interval="20">{{$count_pembayaran}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    @php
                                        $count_pemasukan = \App\Models\PemasukanBiaya::where('id_semester', $semester_aktif->id_semester)->count();
                                        $last_updated_pemasukan = \App\Models\PemasukanBiaya::where('id_semester', $semester_aktif->id_semester)->orderBy('updated_at', 'desc')->first();
                                    @endphp
                                    @if($last_updated_pemasukan)
                                    <div class="text-center"><b>Last updated: {{date_format(date_create($last_updated_pemasukan->updated_at), 'd M Y H:i')}}</b></div>
                                    @else
                                    <div class="text-center"><b>Last updated: -</b></div>
                                    @endif
                                    <div class="info-box bg-blue hover-expand-effect">
                                        <div class="icon">
                                            <i class="material-icons">arrow_upward</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Pemasukan Sekolah</div>
                                            <div class="number count-to" data-from="0" data-to="{{$count_pemasukan}}" data-speed="15" data-fresh-interval="20">{{$count_pemasukan}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    @php
                                        $count_pengeluaran = \App\Models\PengeluaranBiaya::where('id_semester', $semester_aktif->id_semester)->count();
                                        $last_updated_pengeluaran = \App\Models\PengeluaranBiaya::where('id_semester', $semester_aktif->id_semester)->orderBy('updated_at', 'desc')->first();
                                    @endphp
                                    @if($last_updated_pengeluaran)
                                    <div class="text-center"><b>Last updated: {{date_format(date_create($last_updated_pengeluaran->updated_at), 'd M Y H:i')}}</b></div>
                                    @else
                                    <div class="text-center"><b>Last updated: -</b></div>
                                    @endif
                                    <div class="info-box bg-green hover-expand-effect">
                                        <div class="icon">
                                            <i class="material-icons">arrow_downward</i>
                                        </div>
                                        <div class="content">
                                            <div class="text">Pengeluaran Sekolah</div>
                                            <div class="number count-to" data-from="0" data-to="{{$count_pengeluaran}}" data-speed="15" data-fresh-interval="20">{{$count_pengeluaran}}</div>
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