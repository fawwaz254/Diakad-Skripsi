<style>
    table th, .is-center{
        text-align:center; 
        vertical-align:middle !important;
    }
</style>
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        FILTER BULAN
                    </h2>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <label>Bulan</label>
                                    <select class="form-control show-tick" name="id_bulan">
                                        @foreach($data_bulan as $data)
                                            <option {{($bulan->id_bulan == $data->id_bulan)? 'selected' : ''}} value="{{$data->id_bulan}}">{{$data->nm_bulan}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-btn-submit waves-effect" onclick="filterAction()"><i class="material-icons">save</i><span>Filter</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        REKAP MONITORING KESEHATAN SISWA KELAS {{$data_kelas->nm_kelas}} BULAN {{$bulan->nm_bulan}}
                        <a target="_blank" href="{{url(Request::segment(1).'/'.Request::segment(2).'/'.Request::segment(3).'/'.$data_kelas->id_kelas.'/'.$bulan->id_bulan.'/download')}}" class="btn btn-success waves-effect"><i class="material-icons">print</i><span>Download Excel</span></a>
                    </h2>
                </div>
                <div class="body">
                    <table class="table table-bordered table-striped table-hover dataTable" id="primary_table">
                        <thead>
                            <tr>
                                <th rowspan="2">No. </th>
                                <th rowspan="2">NIS</th>
                                <th rowspan="2">Nama</th>
                                <th colspan="{{$dates->count()}}">Tanggal</th>
                            </tr>
                            <tr>
                                @foreach($dates as $date)
                                <th>
                                    {{substr($date->format('l'), 0, 3)}}
                                    <br>
                                    {{$date->format('d')}}
                                </th>

                                @php
                                    $total_pengisi[$date->format('d')] = 0;
                                    $total_normal[$date->format('d')] = 0;
                                    $total_warning[$date->format('d')] = 0;
                                @endphp
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach($data_siswa as $siswa)
                            <tr>
                                <td>{{$no++}}</td>
                                <td>{{$siswa->nis_siswa}}</td>
                                <td>{{$siswa->nm_pengguna}}</td>
                                @foreach($dates as $date)
                                    @php
                                        $all_pengisian = $data_pengisian->where('tgl_pengisian', $date->format('Y-m-d'))->where('id_pengguna_pengisi', $siswa->id_pengguna)->sortByDesc('status_pengisian')->all();
                                    @endphp
                                    @if(count($all_pengisian) > 0)
                                    <td style="background: #{{collect($all_pengisian)->first()->warna_keadaan}}; text-align:center; vertical-align:middle !important;">
                                        <b><a class="target-link" href="{{url(Request::segment(1).'#'.Request::segment(2).'/'.Request::segment(3).'/user/'.$siswa->id_pengguna.'/'.$date->format('Y-m-d'))}}">{{count($all_pengisian)}}x</a></b>
                                    </td>
                                        @php
                                            $total_pengisi[$date->format('d')]++;
                                        @endphp
                                        @if(collect($all_pengisian)->first()->status_pengisian == 1)
                                            @php
                                                $total_normal[$date->format('d')]++;
                                            @endphp
                                        @elseif(collect($all_pengisian)->first()->status_pengisian == 2)
                                            @php
                                                $total_warning[$date->format('d')]++;
                                            @endphp
                                        @endif
                                    @else
                                    <td></td>
                                    @endif
                                @endforeach
                            </tr>
                            @endforeach
                            <tr>
                                <td>Total Normal</td>
                                <td></td>
                                <td></td>
                                @foreach($dates as $date)
                                <td>{{$total_normal[$date->format('d')]}}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>Total Warning</td>
                                <td></td>
                                <td></td>
                                @foreach($dates as $date)
                                <td>{{$total_warning[$date->format('d')]}}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>Total pengisi</td>
                                <td></td>
                                <td></td>
                                @foreach($dates as $date)
                                <td>{{$total_pengisi[$date->format('d')]}}</td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function filterAction(){
    loadURI('{{Request::segment(2)}}/{{Request::segment(3)}}/{{$data_kelas->id_kelas}}/' + $('select[name=id_bulan]').val());
}

var primary_table = $('#primary_table').DataTable({
    ordering: false,
    scrollX: true,
    fixedColumns:   {
        leftColumns: 3
    },
    scrollCollapse: true,
    paging: false
});
</script>