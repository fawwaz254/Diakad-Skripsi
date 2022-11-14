<style>
    table th, .is-center{
        text-align:center; 
        vertical-align:middle !important;
    }
</style>
<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#guru-piket/absensi-harian-siswa/'.$semester_aktif->id_semester.'/'.$data_kelas->id_kelas)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        ABSEN HARIAN KELAS {{$data_kelas->nm_kelas}} Semester {{$semester_aktif->tahun_ajaran}} {{$semester_aktif->nm_semester}} Bulan {{$bulan->nm_bulan}}</h2>
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
                                    <th colspan="{{$data_presensi->count()}}">Tanggal</th>
                                    <th rowspan="2">Rekap</th>
                                </tr>
                                <tr>
                                    @foreach($data_presensi as $presensi_harian)
                                    <th>
                                        {{$presensi_harian->jadwal_hari->nm_jadwal_hari}}
                                        <br>
                                        {{$presensi_harian->convertDateFormat('tgl_entry', 'd/m/y')}}
                                    </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                    $rekap_presensi = [];
                                @endphp
                                @foreach($data_siswa as $siswa)
                                <tr>
                                    <td>{{$no++}}</td>
                                    <td>{{$siswa->nis_siswa}}</td>
                                    <td>{{$siswa->nisn_siswa}}</td>
                                    <td>{{$siswa->nm_pengguna}}</td>
                                    @foreach($data_presensi as $presensi_harian)
                                        @if($presensi_harian_siswa = $presensi_harian->presensi_harian_siswa->firstWhere('id_siswa', $siswa->id_siswa))
                                            @if($presensi_harian_siswa->kehadiran == 1)
                                            <td class="is-center bg-light-green"></td>
                                            @elseif($presensi_harian_siswa->kehadiran == 2)
                                            <td class="is-center bg-amber">S</td>
                                            @elseif($presensi_harian_siswa->kehadiran == 3)
                                            <td class="is-center bg-cyan">I</td>
                                            @elseif($presensi_harian_siswa->kehadiran == 4)
                                            <td class="is-center bg-red">A</td>
                                            @else
                                            <td></td>
                                            @endif
                                        @else
                                            <td></td>
                                        @endif
                                    @endforeach
                                        <td class="is-center">
                                            <a class=" btn btn-success btn-circle waves-effect waves-circle waves-float justify-content-center align-items-center" href="{{url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/print-detail/' . $semester_aktif->id_semester . '/' . $data_kelas->id_kelas . '/' . $siswa->id_pengguna . '/' .  $id_bulan . '/' . $tahun)}}" target="_blank">
                                                <i class="material-icons">picture_as_pdf</i>
                                            </a>
                                        </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <th colspan="4">Persentase Absen</th>
                                    @foreach($data_presensi as $presensi_harian)
                                    <td>{{round(($presensi_harian->persentase_presensi_harian * 100), 2)}}%</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <th colspan="4">Action Edit</th>
                                    @foreach($data_presensi as $presensi_harian)
                                    <td>
                                        <a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="{{url(Request::segment(1).'#guru-piket/absensi-harian-siswa/manage/'.$semester_aktif->id_semester.'/'.$data_kelas->id_kelas.'/'.$presensi_harian->id_presensi_harian)}}">
                                            <i class="material-icons">edit</i>
                                        </a> 
                                        <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAbsensiAction('{{url(Request::segment(1).'/guru-piket/absensi-harian-siswa/action/delete')}}', this)" data-id="{{$presensi_harian->id_presensi_harian}}">
                                            <i class="material-icons">delete_forever</i>
                                        </button>
                                    </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function deleteAbsensiAction(delete_url, element){
        var item = $(element);
        $('button').attr('disabled', 'disabled');

        swal({
            title: "Are you sure?",
            text: "You won't be able to delete this!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (result) {
            if (result) {
                $.ajax({
                    type: "POST",
                    url: delete_url + '/' + item.attr('data-id'),
                    success: function (response) {
                        if(response.status == 200){
                            vex.dialog.alert(response.message);
                            loadContent('guru-piket/absensi-harian-siswa/detail/{{$semester_aktif->id_semester}}/{{$data_kelas->id_kelas}}/{{$tahun}}/{{$bulan->id_bulan}}');
                        }else if(response.status == 300){
                            vex.dialog.alert(response.message);
                        }
                    },
                    complete: function() {
                        $('button').removeAttr('disabled', 'disabled');
                    }
                });
            } else {
                $('button').removeAttr('disabled', 'disabled');
            }
        });
    }
</script>