<style>
    table th, .is-center{
        text-align:center; 
        vertical-align:middle !important;
    }
</style>
<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#'. $auth_data->modul_url .'/'. $auth_data->menu_url)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect " href="{{url(Request::segment(1).'/'. $auth_data->modul_url .'/'. $auth_data->menu_url. '/print/'.$id_semester.'/'.$id_ekskul)}}" target="_blank"><i class="material-icons">print</i><span>Cetak</span></a></h2>
    </div>
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect" onclick="show()" ><i class="material-icons">remove_red_eye</i><span>Tampilkan Detail Pertemuan Ekskul</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        REKAP ABSEN EKSKUL {{$data_ekskul->nm_ekskul}} Semester {{$semester_aktif->tahun_ajaran}} {{$semester_aktif->nm_semester}}</h2>
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" style="overflow-x:auto;" id="primary_table">
                            <thead>
                                <tr>
                                    <th rowspan="2">No. </th>
                                    <th rowspan="2">NIS</th>
                                    <th rowspan="2">NISN</th>
                                    <th rowspan="2">Nama</th>
                                    <th rowspan="2">Kelas</th>
                                    <th colspan="{{$data_presensi->count()}}">Tanggal</th>
                                    <th rowspan="2">Action</th>
                                </tr>
                                <tr>
                                    @foreach($data_presensi as $presensi_ekskul)
                                    <th>
                                        {{$presensi_ekskul->pertemuan_ke}}
                                        <br>
                                        {{$presensi_ekskul->convertDateFormat('tgl_entry', 'd/m/y')}}
                                    </th>
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
                                    <td>{{$siswa->siswa->nis_siswa}}</td>
                                    <td>{{$siswa->siswa->nisn_siswa}}</td>
                                    <td>{{$siswa->siswa->pengguna->nm_pengguna}}</td>
                                    <td>{{$siswa->kelas->nm_kelas}}</td>
                                    @foreach($data_presensi as $presensi_ekskul)
                                        @if($presensi_ekskul_peserta = $presensi_ekskul->presensi_ekskul_peserta->firstWhere('id_siswa', $siswa->id_siswa))
                                            @if($presensi_ekskul_peserta->kehadiran == 1)
                                            <td class="is-center bg-light-green"></td>
                                            @elseif($presensi_ekskul_peserta->kehadiran == 2)
                                            <td class="is-center bg-amber">S</td>
                                            @elseif($presensi_ekskul_peserta->kehadiran == 3)
                                            <td class="is-center bg-cyan">I</td>
                                            @elseif($presensi_ekskul_peserta->kehadiran == 4)
                                            <td class="is-center bg-red">A</td>
                                            @else
                                            <td></td>
                                            @endif
                                        @else
                                            <td></td>
                                        @endif
                                    @endforeach
                                        <td class="is-center">
                                            <a class=" btn btn-success btn-circle waves-effect waves-circle waves-float justify-content-center align-items-center" href="{{url(Request::segment(1).'/'. $auth_data->modul_url .'/'.  $auth_data->menu_url. '/print-detail/'.$id_semester.'/'.$id_ekskul.'/'. $siswa->id_siswa)}}" target="_blank">
                                                <i class="material-icons">picture_as_pdf</i>
                                            </a>
                                        </td>
                                    </tr>
                                </tr>
                                @endforeach
                                <tr>
                                    <th colspan="5">Persentase Absen</th>
                                    @foreach($data_presensi as $presensi_ekskul)
                                    <td>{{round(($presensi_ekskul->persentase_presensi_ekskul * 100), 2)}}%</td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="field2" style="display:none;">
                    <div class="header">
                        <h2>
                            DETAIL PERTEMUAN EKSKUL {{$data_ekskul->nm_ekskul}}
                        </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" style="overflow-x:auto;" id="primary_table">
                                <thead>
                                    <th>No</th>
                                    <th>Pertemuan Ke</th>
                                    <th>Tanggal Entry</th>
                                    <th>Materi Ekskul</th>
                                    
                                    <th>Foto</th>
                                </thead>
                                <tbody>
                                    @php
                                        $number=1;
                                    @endphp
                                    @foreach($data_presensi as $presensi_ekskul)
                                        <tr>
                                            <td>{{$number++}}</td>
                                            <td>{{$presensi_ekskul->pertemuan_ke}}</td>
                                            <td>{{$presensi_ekskul->convertDateFormat('tgl_entry', 'd/m/y')}}</td>
                                            <td>{{$presensi_ekskul->materi_ekskul}}</td>
                                            
                                            <td><a href="{{ Storage::disk('spaces')->url($presensi_ekskul->image) }}"
                                            target="_blank"><img
                                            src="{{ Storage::disk('spaces')->url($presensi_ekskul->image) }}"
                                            style="width: 300px;height: 300px;"></a></td>
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
</div>
<script>
    function show() {
        var x = document.getElementById("field2");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }
    function deleteAbsensiAction(delete_url, element){
        var item = $(element);
        $('button').attr('disabled', 'disabled');

        
    }
</script>