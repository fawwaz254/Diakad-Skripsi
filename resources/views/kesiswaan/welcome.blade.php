@php
    $today = Carbon\Carbon::today('Asia/Jakarta');
@endphp
<div class="container-fluid">
    <div class="card">
        <div class="header">
            <h2>DASHBOARD | {{$today->format('d M Y')}}</h2>
        </div>
        <div class="body">
            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box bg-pink hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">person</i>
                        </div>
                        <div class="content">
                            <div class="text">Total Siswa Aktif</div>
                            <div class="number count-to" data-from="0" data-to="{{$count_siswa}}" data-speed="15" data-fresh-interval="20">{{number_format($count_siswa)}}</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box bg-pink hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">person</i>
                        </div>
                        <div class="content">
                            <div class="text">Siswa Laki-laki</div>
                            <div class="number count-to" data-from="0" data-to="{{ $jenis_kelamin->where('jenis_kelamin', 1)->first()->user_count }}" data-speed="15" data-fresh-interval="20">{{number_format( $jenis_kelamin->where('jenis_kelamin', 1)->first()->user_count) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box bg-pink hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">person</i>
                        </div>
                        <div class="content">
                            <div class="text">Siswa Perempuan</div>
                            <div class="number count-to" data-from="0" data-to="{{ $jenis_kelamin->where('jenis_kelamin', 2)->first()->user_count }}" data-speed="15" data-fresh-interval="20">{{ number_format($jenis_kelamin->where('jenis_kelamin', 2)->first()->user_count) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                @foreach($data_tingkat as $tingkat)
                @php
                    $count_siswa_tingkat = \App\Models\Siswa::with('pengguna')->whereHas('pengguna.status_pengguna', function($q){
                                    $q->where('aktif_status_pengguna', 1);
                                })->whereHas('kelas', function($q) use ($tingkat) { $q->where('tingkat', $tingkat->tingkat); })->whereNotNull('id_kelas')->count();
                @endphp
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box bg-teal hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">person</i>
                        </div>
                        <div class="content">
                            <div class="text">Siswa kelas {{$tingkat->tingkat}}</div>
                            <div class="number count-to" data-from="0" data-to="{{$count_siswa_tingkat}}" data-speed="15" data-fresh-interval="20">{{number_format($count_siswa_tingkat)}}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@include('rilis-note')