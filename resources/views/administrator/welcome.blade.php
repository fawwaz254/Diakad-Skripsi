@php
    $today = Carbon\Carbon::today('Asia/Jakarta');
@endphp
<div class="container-fluid">
    <div class="card">
        <div class="header">
            <h2>DASHBOARD | {{ $today->format('d M Y') }}</h2>
        </div>
        <div class="body">
            <div class="row">
                @if ($role_dashboard)
                    @if ($role_dashboard->isi_dashboard!=null)    
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
            <div class="row">
                {{-- <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a href="{{ route('report.pimpinan') }}">
                        <div class="card">
                            <div class="body bg-red" style="text-align: -webkit-center;">
                                <img class="media-object" src="{{ url('media/flaticon/man.png') }}" width="64" height="64">
                                <h5>
                                    Report Pimpinan
                                </h5>
                                <small>Data Penggunaan Diakad Untuk Setiap Role Semester
                                    {{ $semester_aktif->tahun_ajaran }} {{ $semester_aktif->nm_semester }}</small>
                            </div>
                        </div>
                    </a>
                </div> --}}
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a href="{{ url(Request::segment(0) . Request::segment(1) . '#device/fingerprint') }}">
                        <div class="card">
                            <div class="body bg-green" style="text-align: -webkit-center;">
                                <img class="media-object" src="{{ url('media/flaticon/clipboard.png') }}" width="64"
                                    height="64">
                                <h5>
                                    FingerPrint
                                </h5>
                                <small>Informasi alat Fingerprint</small>
                            </div>
                        </div>
                    </a>
                </div>


                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a href="{{ route('report.walikelas') }}"  target="_blank">
                        <div class="card">
                            <div class="body bg-red" style="text-align: -webkit-center;">
                                <img class="media-object" src="{{ url('media/flaticon/man.png') }}" width="64"
                                    height="64">
                                <h5>
                                    Report Wali Kelas
                                </h5>
                                <small>Data Menu Wali Kelas
                                    {{ $semester_aktif->tahun_ajaran }} {{ $semester_aktif->nm_semester }}</small>
                            </div>
                        </div>
                    </a>
                </div>


                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a href="{{ route('report.guru') }}" target="_blank">
                        <div class="card">
                            <div class="body bg-blue" style="text-align: -webkit-center;">
                                <img class="media-object" src="{{ url('media/flaticon/man.png') }}" width="64"
                                    height="64">
                                <h5>
                                    Report Guru
                                </h5>
                                <small>Repor Guru
                                    {{ $semester_aktif->tahun_ajaran }} {{ $semester_aktif->nm_semester }}</small>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            @include('rilis-note')
        </div>
