<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card is-gap">

                <div class="header">
                    <h2>FILTER PRESENSI SISWA</h2>
                </div>

                <div class="body">

                     <div class="row clearfix">

                         @php 
                         $curYear = date('Y'); 
                         $year = (range($curYear, $curYear - 10));
                         @endphp

                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <h2 class="card-inside-title">
                                Pilih Tahun
                            </h2>
                            <select class="form-control show-tick" name="tahun" id="tahun">
                                 @foreach($year as $year)
                                    <option value='{{$year}}'>{{$year}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <h2 class="card-inside-title">
                                Pilih Bulan
                            </h2>
                            <select class="form-control show-tick" name="id_bulan" id="bulan">
                                @foreach($data_bulan as $data)
                                    <option {{($bulan->id_bulan == $data->id_bulan)? 'selected' : ''}} value="{{$data->id_bulan}}">{{$data->nm_bulan}}</option>
                                @endforeach     
                            </select>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-btn-submit waves-effect" id="button_filter" onclick="filterAction()"><i class="material-icons">save</i><span>Filter</span></button>
                        </div>

                     </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card is-gap">

                <div class="header">
                    <h2>DATA PRESENSI SISWA</h2>
                </div>

                <div class="body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display" id="primary_table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Hari</th>
                                    <th>Status Kehadiran</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($dates as $date)
                                    <tr>

                                        <td>{{$date->format('d')}}</td>
                                        <td>{{$date->format('l')}}</td>

                                        @php

                                        $cek = \App\Models\PresensiHarianSiswa::select('presensi_harian_siswa.*','presensi_harian.tgl_entry')
                                         ->join('presensi_harian','presensi_harian_siswa.id_presensi_harian','presensi_harian.id_presensi_harian')
                                         ->where('presensi_harian_siswa.id_siswa',$siswa->id_siswa)
                                         ->whereDate('tgl_entry',$date->format('Y-m-d'))
                                         ->first();

                                        @endphp
                                       
                                        @if($cek)
                                        @if($cek->kehadiran == 1)
                                        <td class="is-center bg-light-green">H</td>
                                        @elseif($cek->kehadiran == 2)
                                        <td class="is-center bg-amber">S</td>
                                        @elseif($cek->kehadiran == 3)
                                        <td class="is-center bg-cyan">I</td>
                                        @elseif($cek->kehadiran == 4)
                                        <td class="is-center bg-red">A</td>
                                        @endif
                                        @else
                                        <td></td>
                                        @endif

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                </div>

            </div>
        </div>
    </div>

</div>

<script type="text/javascript">
    
function filterAction(){
    loadURI('{{Request::segment(2)}}/{{Request::segment(3)}}/' + $('select[name=id_bulan]').val() + '/' + $('select[name=tahun]').val());
}

</script>

