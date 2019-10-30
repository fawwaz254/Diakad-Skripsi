<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#data-kesiswaan/admisi-siswa')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{csrf_field()}}
                <div class="header bg-green">
                    <h2>
                        GENERATE ADMISI
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-generate-admisi-siswa')}}">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                                <tr>
                                    <th colspan="2" style="text-align: center;">ACTION GENERATE ADMISI</th>
                                </tr>
                                <tr>
                                    <td style="width: 30%">Semester</td>
                                    <td style="width: 70%">
                                        <select class="form-control show-tick" name="id_semester">
                                            <option value="" disabled selected >-- Pilih Semester --</option>
                                            @foreach($data_semester as $data)
                                                @if($data->is_aktif_semester == 1)
                                                    <option value="{{$data->id_semester}}" selected >{{$data->tahun_ajaran}} {{$data->nm_semester}} (Aktif)</option>
                                                @else
                                                    <option value="{{$data->id_semester}}">{{$data->tahun_ajaran}} {{$data->nm_semester}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 30%">Kelas</td>
                                    <td style="width: 70%">
                                        <select class="form-control show-tick" name="id_kelas">
                                            <option value="" disabled selected >-- Pilih Kelas --</option>
                                            @foreach($data_kelas as $data)
                                                <option value="{{$data->id_kelas}}">{{$data->nm_kelas}} ({{$data->nm_jurusan}})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 30%">Timpa Data <br><small>*Jika dipilih "Tidak", maka hanya <br>  melakukan generate admisi siswa <br> yang belum ada saja.</small></td>
                                    <td style="width: 70%">
                                        <select class="form-control show-tick" name="is_override">
                                            <option value="0" >Tidak</option>
                                            <option value="1" >Ya</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        Kegiatan Generate Admisi pada Semester terpilih akan melakukan proses berikut :

                                        <u1>
                                            <li>Siswa yang sudah diplotting mapel dan sudah terapprove, akan diberikan status AKTIF</li>
                                            <li>Siswa yang belum diplotting mapel atau belum terapprove, maka akan diberikan status CUTI/NON-AKTIF-SEMENTARA</li>
                                            <li>Siswa yang terdapat usulan CUTI/NON-AKTIF-SEMENTARA (pada semester terpilih) di admisi, maka plotting mapelnya akan diset belum terapprove</li>
                                            <li>Siswa yang sedang CUTI/NON-AKTIF-SEMENTARA di semester sebelumnya tidak akan digenerate, harus diaktifkan melalui menu Admisi</li>
                                            <li>Siswa yang telah LULUS/KELUAR pada semester sebelumnya, tidak akan di proses</li>
                                        </u1>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>GENERATE ADMISI</span></button>
                                    </td>
                                </tr>
                            </table>

                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                                <tr>
                                    <td colspan="2">
                                    </td>
                                </tr>
                            </table>

                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                                <tr>
                                    <th colspan="2" style="text-align: center;">LIHAT LAPORAN</th>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <a class="btn btn-block bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#data-kesiswaan/admisi-siswa/generate/laporan')}}"><i class="material-icons">description</i><span>Laporan Admisi Per Kelas</span></a>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')