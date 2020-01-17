<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#siswa/cari-siswa/view-detail/'.$nis_nama_siswa_asli)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{csrf_field()}}
                <div class="header">
                    <h2>
                        BIODATA SISWA NIS/NISN : {{$nis_siswa}}
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
            <div class="card">
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                            <tr>
                                <th colspan="2" style="text-align: center;">BIODATA SISWA</th>
                            </tr>
                            <tr>
                                <th colspan="2" style="text-align: center;">
                                    <img src="" style="height: 270px; width: 180px">
                                </th>
                            </tr>
                            <tr>
                                <td style="width: 50%">Nama Lengkap</td>
                                <td style="width: 50%">{{$siswa->nm_pengguna}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Nomor Pendaftaran</td>
                                <td style="width: 50%">{{$siswa->kode_voucher}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">NIS</td>
                                <td style="width: 50%">{{$siswa->nis_siswa}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">NISN</td>
                                <td style="width: 50%">{{$siswa->nisn_siswa}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Jenis Kelamin</td>
                                <td style="width: 50%">{{$jenis_kelamin}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Tempat, Tanggal Lahir</td>
                                <td style="width: 50%">{{$kota_lahir}},{{$siswa->tgl_lahir}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Tahun Masuk</td>
                                <td style="width: 50%">{{$siswa->thn_masuk_siswa}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Jalur Masuk</td>
                                <td style="width: 50%">{{$siswa->nm_jalur}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Sekolah Asal</td>
                                <td style="width: 50%">{{$siswa->asal_sekolah}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Jurusan</td>
                                <td style="width: 50%">{{$siswa->nm_jurusan}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Kelas</td>
                                <td style="width: 50%">{{$siswa->nm_kelas}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Status Siswa</td>
                                <td style="width: 50%">{{$siswa->nm_status_pengguna}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Nama Ayah</td>
                                <td style="width: 50%">{{$siswa->nm_ayah}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Nama Ibu</td>
                                <td style="width: 50%">{{$siswa->nm_ibu}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Alamat Siswa</td>
                                <td style="width: 50%">{{$alamat_jalan_siswa}} {{$alamat_dusun_siswa}} {{$alamat_kelurahan_siswa}} {{$alamat_rt_siswa}} {{$alamat_rw_siswa}} {{$alamat_kecamatan_siswa}} {{$alamat_kodepos_siswa}} {{$siswa->nm_kota}} {{$siswa->nm_provinsi}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Alamat Ortu</td>
                                <td style="width: 50%">{{$alamat_jalan_ortu}} {{$alamat_dusun_ortu}} {{$alamat_kelurahan_ortu}} {{$alamat_rt_ortu}} {{$alamat_rw_ortu}} {{$alamat_kecamatan_ortu}} {{$alamat_kodepos_ortu}} {{$alamat_kota_ortu}} {{$alamat_provinsi_ortu}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">No. HP Siswa</td>
                                <td style="width: 50%">{{$siswa->nomor_hp}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">No. Telepon Ortu</td>
                                <td style="width: 50%">{{$siswa->nomor_telp_ortu}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">No. HP Ortu</td>
                                <td style="width: 50%">{{$siswa->nomor_hp_ortu}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
            <div class="card">
                <div class="body">
                    <div class="table-responsive">
                        @foreach($grup_semester_kelas as $tahun_ajaran => $grup_kelas)
                            @foreach($grup_kelas as $nm_semester => $datapergrup)
                            <h2 class="card-inside-title">{{$nm_semester}} ({{$tahun_ajaran}})</h2>
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                                <tr>
                                    <th>No.</th>
                                    <th>Kode</th>
                                    <th>Mata<br> Pelajaran</th>
                                    <th>Jam <br>Pelajaran</th>
                                    <th>KKM</th>
                                    <th>Nilai<br> Angka</th>
                                    <th>Nilai <br>Huruf</th>
                                </tr>
                                @foreach($datapergrup as $key => $data)
                                <tr>
                                    <td>{{$key + 1}}</td>
                                    <td>{{$data->kd_mata_pelajaran}}</td>
                                    <td>{{$data->nm_mata_pelajaran}}</td>
                                    <td>{{$data->kredit_semester}}</td>
                                    <td>{{$data->nilai_kkm}}</td>
                                    <td>{{$data->nilai_angka}}</td>
                                    <td>{{$data->nilai_huruf}}</td>
                                </tr>
                                @endforeach
                            </table>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
            <div class="card">
                <div class="body">
                    <div class="table-responsive">
                        <h2 class="card-inside-title">Aktivitas</h2>
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                            <tr>
                                <th>Semester</th>
                                <th>Status Siswa</th>
                                <th>IPS</th>
                                <th>IPK</th>
                            </tr>
                            @foreach($aktivitas as $aktivitas)
                            <tr>
                                <td>{{$aktivitas->nm_semester}} {{$aktivitas->tahun_ajaran}}</td>
                                <td>{{$aktivitas->nm_status_pengguna}}</td>
                                <td>{{$aktivitas->ips}}</td>
                                <td>{{$aktivitas->ipk}}</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
