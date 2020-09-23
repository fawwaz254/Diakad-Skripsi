@php
    $today = Carbon\Carbon::today('Asia/Jakarta');
@endphp
<div class="container-fluid">
    <div class="block-header">
        <h2>DASHBOARD | {{$today->format('d M Y')}}</h2>
    </div>
    <div class="row">
        @if($role_dashboard)
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        PENGUMUMAN
                    </h2>
                </div>
                <div class="body">
                {!!$role_dashboard->isi_dashboard!!}
                </div>
            </div>
        </div>
        @endif
    </div>
    <br>
    <div class="block-header">
        <h2>FEATURE MENU</h2>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="target-link" href="{{url(Request::segment(1).'#kegiatan-harian/mengisi-form-kesehatan')}}">
                <div class="card">
                    <div class="body bg-red" style="text-align: -webkit-center;">
                        <img class="media-object" src="{{url('media/flaticon/heartbeat.png')}}" width="64" height="64">
                        <h5>
                        Monitoring Kesehatan 
                        </h5>
                        <small>Isi Form monitoring kesehatan Anda setiap hari pukul 19:00 - 07:00</small>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>