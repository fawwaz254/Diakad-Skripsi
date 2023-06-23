@php
    $today = Carbon\Carbon::today('Asia/Jakarta');
@endphp
<div class="container-fluid">
    <div class="block-header">
        <h2>DASHBOARD | {{$today->format('d M Y')}}</h2>
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
    </div>
</div>

@include('rilis-note')