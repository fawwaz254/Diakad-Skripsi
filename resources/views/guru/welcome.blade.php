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
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="target-link" href="{{url(Request::segment(1).'#jadwal/kalender-akademik')}}">
                <div class="card">
                    <div class="body bg-blue" style="text-align: -webkit-center;">
                        <img class="media-object" src="{{url('media/flaticon/calendar.png')}}" width="64" height="64">
                        <h5>
                        Kalender Akademik 
                        </h5>
                        <small>Cek jadwal kegiatan sekolah</small>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a class="target-link" href="{{url(Request::segment(1).'#presensi/absensi-tanpa-jadwal')}}">
                <div class="card">
                    <div class="body bg-teal" style="text-align: -webkit-center;">
                        <img class="media-object" src="{{url('media/flaticon/clipboard.png')}}" width="64" height="64">
                        <h5>
                        Absensi Tanpa Jadwal
                        </h5>
                        <small>Absensi langsung tanpa setting jadwal sebelumnya, berlaku saat jadwal belum valid</small>
                    </div>
                </div>
            </a>
        </div>
    </div> --}}
    
</div>
    <div class="row">
        @foreach (get_moduls() as $modul)
                    @php
                        $featuremenu = App\Models\Featuremenu::where('id_modul',$modul->id_modul)->first();
                    @endphp
                    @if ($featuremenu->is_aktif == 1)
                        
                    @if (auth_data()->sekolah_data->nm_singkat_sekolah !== 'smpypm2' && $modul->nm_modul=="Ketidaksesuaian SOP")
                    
                    @else
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 " data-toggle="modal" data-target=".bd-modal-lg-{{ $modul->route }}">
                        <div class="card" style="margin: 1rem;">
                            <div class="body bg-teal" style="text-align: -webkit-center;min-height: 26rem;">
                                <img class="media-object" src="{{url('media/flaticon/'. $modul->route .'.png')}}" width="64" height="64">
                                <h5>
                                    {{ $modul->nm_modul }}
                                </h5>
                                <small>{{$featuremenu->deskripsi}}</small>
                            </div>
                        </div>
                    </div>
                
                    <div class="modal fade bd-modal-lg-{{ $modul->route }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="row col-lg-12 d-flex justify-content-center" style="background-color:white;border-radus:1rem;">
                                <div class=" d-flex justify-content-center">
                                    @foreach ($modul->menus as $menu)
                                        {{-- @if ($menu->nm_menu == 'Tracer Alumni' && $detail_wali_kelas == null && $role_aktif !== 19 && $role_aktif !== 12) --}}
                                        {{-- @else --}}
                                            <div id="menu-item-{{ $modul->route }}-{{ $menu->page }}" class="d-flex mx-auto submenu col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                                <a class="target-link d-flex mx-auto" href="{{ url(Request::segment(1) . '#' . $modul->route . '/' . $menu->page) }}">
                                                    <div class="card" style="margin: 1rem;">
                                                        <div class="body bg-teal" style="text-align: -webkit-center;">
                                                            <h5>
                                                                {{ $menu->nm_menu }}
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        {{-- @endif --}}
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    @endif
                    @else
                    
                    @endif
                @endforeach
    </div>
</div>
<script>
    $(document).ready(function() {
        $('div.submenu').click(function() {
            $( "body" ).removeClass( "modal-open" );
            $('.modal-backdrop').remove();
        });
    });
</script>

@include('rilis-note')