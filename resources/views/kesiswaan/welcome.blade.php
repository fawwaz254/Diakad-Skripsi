@php
    $today = Carbon\Carbon::today('Asia/Jakarta');
    // dd($$count_siswa);
@endphp
<div class="container-fluid">
    <div class="card">
        <div class="header">
            <h2>DASHBOARD | {{ $today->format('d M Y') }}</h2>
        </div>
        <div class="body">
            {{-- <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="target-link"
                        href="{{ url(Request::segment(1) . '#kegiatan-harian/mengisi-form-kesehatan') }}">
                        <div class="card">
                            <div class="body bg-red" style="text-align: -webkit-center;">
                                <img class="media-object" src="{{ url('media/flaticon/heartbeat.png') }}" width="64"
                                    height="64">
                                <h5>
                                    Monitoring Kesehatan
                                </h5>
                                <small>Isi Form monitoring kesehatan Anda setiap hari pukul {{ $start_monkes }} -
                                    {{ $end_monkes }}</small>
                            </div>
                        </div>
                    </a>
                </div>
            </div> --}}
            <br>
            @if ($count_siswa)
                <div class="row clearfix">
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <div class="info-box bg-pink hover-expand-effect">
                            <div class="icon">
                                <i class="material-icons">person</i>
                            </div>
                            <div class="content">
                                <div class="text">Total Siswa Aktif</div>
                                <div class="number count-to" data-from="0" data-to="{{ $count_siswa }}"
                                    data-speed="15" data-fresh-interval="20">{{ number_format($count_siswa) }}</div>
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
                                <div class="number count-to" data-from="0"
                                    data-to="{{ $jenis_kelamin->where('jenis_kelamin', 1)->first()->user_count }}"
                                    data-speed="15" data-fresh-interval="20">
                                    {{ number_format($jenis_kelamin->where('jenis_kelamin', 1)->first()->user_count) }}
                                </div>
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
                                <div class="number count-to" data-from="0"
                                    data-to="{{ $jenis_kelamin->where('jenis_kelamin', 2)->first()->user_count }}"
                                    data-speed="15" data-fresh-interval="20">
                                    {{ number_format($jenis_kelamin->where('jenis_kelamin', 2)->first()->user_count) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table
                                class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                                <tr>
                                    <th rowspan="2" style="text-align:center; vertical-align:middle;">Jurusan</th>
                                    <th colspan="{{ $data_tingkat->count() }}" style="text-align: center;">Kelas</th>
                                </tr>
                                <tr>

                                    @foreach ($data_tingkat as $tingkat)
                                        <td style="text-align:center">Kelas {{ $tingkat->tingkat }}</td>
                                    @endforeach
                                </tr>
                                @php
                                    $total = [];
                                @endphp
                                @foreach ($data_jurusan as $jurusan)
                                    <tr>
                                        <td>{{ $jurusan->nm_jurusan }}</td>
                                        @foreach ($data_tingkat as $tingkat)
                                            @php
                                                $count_siswa_tingkat = \App\Models\Siswa::query()
                                                    ->whereHas('kelas', function ($q) use ($tingkat, $jurusan) {
                                                        $q->where('tingkat', $tingkat->tingkat)->where('id_jurusan', $jurusan->id_jurusan);
                                                    })
                                                    ->whereHas('pengguna.status_pengguna', function ($q) {
                                                        $q->where('aktif_status_pengguna', 1)->where('nm_status_pengguna', 'AKTIF');
                                                        // $q->where('aktif_status_pengguna', 1)->where('kode_status_pengguna', 'AKTIF');
                                                        // $q->where('aktif_status_pengguna', 1)->where('kode_status_pengguna', '!=', 'CUTI');
                                                    })
                                                    ->whereNotNull('id_kelas')
                                                    ->count();
                                            @endphp
                                            <td style="text-align:center">{{ $count_siswa_tingkat }} Siswa</td>
                                            @if (!empty($total[$tingkat->tingkat]))
                                                @php
                                                    $total[$tingkat->tingkat] += $count_siswa_tingkat;
                                                @endphp
                                            @else
                                                @php
                                                    $total[$tingkat->tingkat] = $count_siswa_tingkat;
                                                @endphp
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                                <tr style="background-color: #8bc34a;">
                                    <td>TOTAL</td>
                                    @foreach ($data_tingkat as $tingkat)
                                        <td style="text-align:center">{{ $total[$tingkat->tingkat] }} Siswa</td>
                                    @endforeach
                                </tr>
                            </table>
                        </div>
                        {{-- @foreach ($data_tingkat as $tingkat)
                    @php
                        $count_siswa_tingkat = \App\Models\Siswa::with('pengguna')
                            ->whereHas('pengguna.status_pengguna', function ($q) {
                                $q->where('aktif_status_pengguna', 1)->where('nm_status_pengguna', 'AKTIF');
                            })
                            ->whereHas('kelas', function ($q) use ($tingkat) {
                                $q->where('tingkat', $tingkat->tingkat);
                            })
                            ->whereNotNull('id_kelas')
                            ->count();
                    @endphp
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <div class="info-box bg-teal hover-expand-effect">
                            <div class="icon">
                                <i class="material-icons">person</i>
                            </div>
                            <div class="content">
                                <div class="text">Siswa kelas {{ $tingkat->tingkat }}</div>
                                <div class="number count-to" data-from="0" data-to="{{ $count_siswa_tingkat }}"
                                    data-speed="15" data-fresh-interval="20">{{ number_format($count_siswa_tingkat) }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach --}}
                    </div>
                </div>
            @endif
        </div>
    </div>
    <br>
    <div class="row">
        @if ($role_dashboard)
            @if ($role_dashboard->isi_dashboard != null)
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                PENGUMUMAN
                            </h2>
                        </div>
                        <div class="body">
                            {!! $role_dashboard->isi_dashboard !!}
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>

@include('rilis-note')
