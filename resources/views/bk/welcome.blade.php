@php
    $today = Carbon\Carbon::today('Asia/Jakarta');
@endphp
<div class="container-fluid">
    <div class="block-header">
        <h2>DASHBOARD | {{$today->format('d M Y')}}</h2>
    </div>
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
    {{-- <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="target-link" href="{{url(Request::segment(1).'#kegiatan-harian/mengisi-form-kesehatan')}}">
                <div class="card">
                    <div class="body bg-red" style="text-align: -webkit-center;">
                        <img class="media-object" src="{{url('media/flaticon/heartbeat.png')}}" width="64" height="64">
                        <h5>
                        Monitoring Kesehatan 
                        </h5>
                        <small>Isi Form monitoring kesehatan Anda setiap hari pukul {{$start_monkes}} - {{$end_monkes}}</small>
                    </div>
                </div>
            </a>
        </div>
    </div> --}}
    <br>
    <div class="row clearfix">

        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h2>Semester {{$semester_aktif->tahun_ajaran}} {{$semester_aktif->nm_semester}}</h2>
                </div>
                <div class="body">

                    <div class="alert bg-pink">
                        Kelas yang Menjadi Tanggung Jawab Saya
                        <hr>
                        @foreach($bk_kelas_nama as $r)
                        @if(count($bk_kelas_nama)>0)
                        {{$r}}
                        @if(!$loop->last)
                        ,
                        @endif
                        @else
                        Penanggung jawab BK untuk tiap kelas belum di setting
                        @endif
                        @endforeach
                    </div>

                    <h5 style="text-align: center;">Pelanggaran Dibawah Tanggung Jawab Saya</h5>
                     <table class="table table-bordered">
                        <thead>
                            <tr class="bg-pink">
                                <th style="text-align: center;">Yang Menginputkan</th>
                                <th style="text-align: center;">Jumlah</th>
                                <th style="text-align: center;">Belum Ditindak</th>
                                <th style="text-align: center;">Sudah Ditindak</th>
                            </tr>
                            <tr>
                                <th style="text-align: center;">BK</th>
                                <th style="text-align: center;">{{$pelanggaran_bk_count}}</th>
                                <th style="text-align: center;">{{$pelanggaran_bk_belum_ditindak}}</th>
                                <th style="text-align: center;">{{$pelanggaran_bk_sudah_ditindak}}</th>
                            </tr>
                            <tr>
                                <th style="text-align: center;">Guru</th>
                                <th style="text-align: center;">{{$pelanggaran_orang_lain_count}}</th>
                                <th style="text-align: center;">{{$pelanggaran_orang_lain_belum_ditindak}}</th>
                                <th style="text-align: center;">{{$pelanggaran_orang_lain_sudah_ditindak}}</th>
                            </tr>
                            <tr>
                                <th style="text-align: center;" class="bg-pink">Total</th>
                                <th style="text-align: center;">{{$pelanggaran_bk_count +  $pelanggaran_orang_lain_count}}</th>
                                <th style="text-align: center;">{{$pelanggaran_bk_belum_ditindak +  $pelanggaran_orang_lain_belum_ditindak}}</th>
                                <th style="text-align: center;">{{$pelanggaran_bk_sudah_ditindak +  $pelanggaran_orang_lain_sudah_ditindak}}</th>
                            </tr>
                        </thead>
                    </table>

                </div>
            </div>
        </div>

    </div>
</div>

@include('rilis-note')