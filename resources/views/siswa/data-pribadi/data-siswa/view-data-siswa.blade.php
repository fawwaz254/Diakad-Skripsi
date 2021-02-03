<div class="container-fluid">
    <div class="block-header">
        <!-- <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#magang-siswa/nama-magang')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2> -->
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        UPDATE DATA SISWA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/data-siswa/'.$siswa->nis_siswa)}}">
                        {{csrf_field()}}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="demo-color-box bg-success">
                                DATA PRIBADI
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Nama Lengkap
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="nm_pengguna" required="" aria-required="true" aria-invalid="true" value="{{$siswa->nm_pengguna}}">
                                <input type="hidden" class="form-control" name="id_c_siswa" aria-required="true" aria-invalid="true" value="{{$siswa->id_c_siswa}}">
                                <input type="hidden" class="form-control" name="id_siswa" aria-required="true" aria-invalid="true" value="{{$siswa->id_siswa}}">
                                <input type="hidden" class="form-control" name="id_pengguna" aria-required="true" aria-invalid="true" value="{{$siswa->id_pengguna}}">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Jenis Kelamin
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <select class="form-control show-tick" name="jenis_kelamin" id="jenis_kelamin">
                                    <option value="1" {{$siswa->jenis_kelamin == 1 ? 'selected' : ''}}>Laki-Laki</option>
                                    <option value="2" {{$siswa->jenis_kelamin == 2 ? 'selected' : ''}}>Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    NISN
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="nisn_siswa" aria-required="true" aria-invalid="true" value="{{$siswa->nisn_siswa}}">
                                <input type="hidden" class="form-control" name="nis_siswa" aria-required="true" aria-invalid="true" value="{{$siswa->nis_siswa}}">
                                <input type="hidden" class="form-control" name="id_status_pengguna" aria-required="true" aria-invalid="true" value="{{$siswa->id_status_pengguna}}">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    NIK / No. KITAS <small>Nomor Induk Kependudukan yang tercantum pada kartu keluarga, kartu identitas anak, atau KTP (jika sudah memiliki) bagi WNI. Bagi WNA, diisi dengan nomor Kartu Izin Tinggal Terbatas (KITAS)</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="nik_siswa" aria-invalid="true" value="{{$siswa->nik_siswa}}">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Tempat Lahir
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <select class="form-control show-tick" name="id_kota_lahir" id="id_kota_lahir">
                                    <option value="{{ isset($kotaLahir->id_kota) ? $kotaLahir->id_kota : '' }}" selected="">{{ isset($kotaLahir->id_kota) ? $kotaLahir->nm_kota : '' }}</option>
                                        @foreach($kota as $kota)
                                            <option value="{{$kota->id_kota}}">{{$kota->nm_kota}}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Tanggal Lahir
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                 @if($siswa->tgl_lahir)
                                 <input type="text" class="datepicker form-control" name="tgl_lahir" aria-required="true" aria-invalid="true" value="{{date('d F Y', strtotime($siswa->tgl_lahir))}}" >
                                @else
                                 <input type="text" class="datepicker form-control" name="tgl_lahir" aria-required="true" aria-invalid="true">
                                @endif
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    No. Registasi Akta Lahir <br>
                                    <small>Nomor registrasi yang dimaksud umumnya tercantum pada bagian tengah atas lembar kutipan akta kelahiran</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="nomor_akta_lahir" aria-required="true" aria-invalid="true" value="{{$siswa->nomor_akta_lahir}}">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Agama & Kepercayaan
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <select class="form-control show-tick" name="id_agama" id="id_agama">
                                    @foreach($agama as $agama)
                                        <option value="{{$agama->id_agama}}" {{$siswa->id_agama == $agama->id_agama ? 'selected' : ''}}>{{$agama->nm_agama}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>

                        <!-- Bahasa sehari hari yang digunakan -->
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Bahasa <br>
                                    <small>Bahasa sehari-hari yang digunakan</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" value="{{$siswa->bahasa_sehari_hari}}" name="bahasa_sehari_hari" aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <br>
                        <!-- Bahasa sehari hari yang digunakan -->

                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Kewarganegaraan
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <select class="form-control show-tick" name="kewarganegaraan" id="kewarganegaraan">
                                    <option value="1" {{$siswa->kewarganegaraan==1 ? 'selected' : ''}}>WNI</option>
                                    <option value="2" {{$siswa->kewarganegaraan==2 ? 'selected' : ''}}>WNA</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Nama Negara <br>
                                   <small>Diisi jika Kewarganegaraan WNA</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="nm_kewarganegaraan" aria-required="true" aria-invalid="true" value="{{ isset($siswa->nm_kewarganegaraan) ? $siswa->nm_kewarganegaraan : '' }}">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Kebutuhan Khusus
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <select class="form-control show-tick" name="id_kebutuhan_khusus" id="id_kebutuhan_khusus">
                                    @foreach($kebutuhanKhusus as $kebutuhan)
                                        <option value="{{$kebutuhan->id_kebutuhan_khusus}}" {{$siswa->id_kebutuhan_khusus == $kebutuhan->id_kebutuhan_khusus ? 'selected' : ''}}>{{$kebutuhan->nm_kebutuhan_khusus}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Alamat Jalan
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="alamat_jalan" aria-required="true" aria-invalid="true" value="{{$siswa->alamat_jalan}}">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   RT <br>
                                   <small>Nomor RT tempat tinggal peserta didik saat ini</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="alamat_rt" aria-required="true" aria-invalid="true" value="{{$siswa->alamat_rt}}">
                            </div>
                        </div>
                         <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   RW <br>
                                   <small>Nomor RW tempat tinggal peserta didik saat ini</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="alamat_rw" aria-required="true" aria-invalid="true" value="{{ isset($siswa->alamat_rw) ? $siswa->alamat_rw : '' }}">
                            </div>
                        </div>
                         <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Nama Dusun <br>
                                   <small>Nama Dusun tempat tinggal peserta didik saat ini</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="alamat_dusun" aria-required="true" aria-invalid="true" value="{{$siswa->alamat_dusun}}">
                            </div>
                        </div>
                         <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Nama Kelurahan/Desa <br>
                                   <small>Nama Kelurahan atau desa tempat tinggal peserta didik saat ini</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="alamat_kelurahan" aria-required="true" aria-invalid="true" value="{{$siswa->alamat_kelurahan}}">
                            </div>
                        </div>
                         <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Kecamatan <br>
                                   <small>Nama kecamatan tempat tinggal peserta didik saat ini</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="alamat_kecamatan" aria-required="true" aria-invalid="true" value="{{$siswa->alamat_kecamatan}}">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Kode Pos <br>
                                   <small>Kode Pos tempat tinggal peserta didik saat ini</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="alamat_kodepos" aria-required="true" aria-invalid="true" value="{{$siswa->alamat_kodepos}}">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Alamat Kota
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <select class="form-control show-tick" name="alamat_kota" id="alamat_kota">
                                    <option value="{{ isset($siswa->alamat_kota) ? $siswa->alamat_kota : '' }}" selected="">{{ isset($siswa->nm_kota) ? $siswa->nm_kota : '' }}</option>
                                    @foreach($kotaTinggal as $kota)
                                        <option value="{{$kota->id_kota}}">{{$kota->nm_kota}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Alamat Provinsi
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <select class="form-control show-tick" name="alamat_provinsi" id="alamat_provinsi">
                                    <option value="{{ isset($siswa->alamat_provinsi) ? $siswa->alamat_provinsi : '' }}" selected="">{{ isset($siswa->nm_kota) ? $siswa->nm_kota : '' }}</option>
                                        @foreach($provinsi as $r)
                                            <option value="{{$r->id_provinsi}}">{{$r->nm_provinsi}}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Garis Lintang <br>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="alamat_latitude" aria-required="true" aria-invalid="true" value="{{$siswa->alamat_latitude}}">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Garis Bujur <br>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="alamat_longitude" aria-required="true" aria-invalid="true" value="{{$siswa->alamat_longitude}}">
                            </div>
                        </div>
                        <br>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Tempat Tinggal <br>
                                   <small>Kepemilikan tempat tinggal peserta didik saat ini</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <select class="form-control show-tick" name="id_jenis_tinggal" id="id_jenis_tinggal">
                                    <option value="">Pilih Tempat Tinggal</option>
                                    @foreach($jenisTinggal as $jenis)
                                        <option value="{{$jenis->id_jenis_tinggal}}" {{$siswa->id_jenis_tinggal == $jenis->id_jenis_tinggal ? 'selected' : ''}}>{{$jenis->nm_jenis_tinggal}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Moda Transportasi <br>
                                   <small>Jenis transportasi utama atau yang paling sering digunakan peserta didik untuk berangkat ke sekolah</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <select class="form-control show-tick" name="id_jenis_transportasi" id="id_jenis_transportasi">
                                    <option value="">Pilih Mode Transportasi</option>
                                    @foreach($jenisTransportasi as $tranportasi)
                                        <option value="{{$tranportasi->id_jenis_transportasi}}" {{$siswa->id_jenis_transportasi == $tranportasi->id_jenis_transportasi ? 'selected' : ''}}>{{$tranportasi->nm_jenis_transportasi}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Nomor KKS <br>
                                   <small>Nomor Kartu Keluarga Sejahtera (jika memiliki)</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="nomor_kks" aria-required="true" aria-invalid="true" value="{{$siswa->nomor_kks}}">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Anak ke-berapa
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="anak_ke" aria-required="true" aria-invalid="true" value="{{$siswa->anak_ke}}">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Penerima KPS/PKH
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <select class="form-control show-tick" name="is_penerima_kps" id="is_penerima_kps">
                                    <option value="1" {{$siswa->is_penerima_kps ==1 ? 'selected' : ''}}>Ya</option>
                                    <option value="0" {{$siswa->is_penerima_kps ==0 ? 'selected' : ''}}>Tidak</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   No. KPS/KPH <br>
                                   <small>apabila menerima</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="nomor_kps" aria-required="true" aria-invalid="true" value="{{$siswa->nomor_kps}}">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Apakah Punya KIP
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <select class="form-control show-tick" name="is_punya_kip" id="is_punya_kip">
                                    <option value="1" {{$siswa->is_punya_kip == 1 ? 'selected' : ''}}>Ya</option>
                                    <option value="0" {{$siswa->is_punya_kip == 0 ? 'selected' : ''}}>Tidak</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Nomor KIP <br>
                                   <small>apabila memiliki</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="nomor_kip" aria-required="true" aria-invalid="true" value="{{$siswa->nomor_kip}}">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Nama tertera di KIP <br>
                                   <small>apabila memiliki</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="nm_tertera_kip" aria-required="true" aria-invalid="true" value="{{$siswa->nm_tertera_kip}}">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Apakah Layak PIP
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <select class="form-control show-tick" name="is_layak_pip" id="is_layak_pip">
                                    <option value="1" {{$siswa->is_punya_pip == 1 ? 'selected' : ''}}>Ya</option>
                                    <option value="0" {{$siswa->is_punya_pip == 0 ? 'selected' : ''}}>Tidak</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Alasan layak PIP
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <select class="form-control show-tick" name="id_jenis_layak_pip" id="id_jenis_layak_pip">
                                    <option value="{{ isset($siswa->id_jenis_layak_pip) ? $siswa->id_jenis_layak_pip : '' }}" selected>
                                        {{ isset($siswa->nm_jenis_layak_pip) ? $siswa->nm_jenis_layak_pip : '' }}
                                    </option>
                                    @foreach($jenisPip as $pip)
                                        <option value="{{$pip->id_jenis_layak_pip}}">{{$pip->nm_jenis_layak_pip}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="demo-color-box bg-success">
                                DATA AYAH KANDUNG
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Nama Ayah Kandung
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="nm_ayah" aria-required="true" aria-invalid="true" value="{{$siswa->nm_ayah}}">
                            </div>
                        </div>
                        <br>
                        <!-- Status Ayah -->
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Status Ayah <br>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                               <select class="form-control show-tick" name="status_ayah" id="status_ayah">
                                    <option value="1" {{$siswa->status_ayah == 1 ? 'selected' : ''}}>Masih Hidup</option>
                                    <option value="2" {{$siswa->status_ayah == 2 ? 'selected' : ''}}>Wafat</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <!-- Status Ayah -->
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    NIK Ayah Kandung
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="nik_ayah" aria-invalid="true" value="{{$siswa->nik_ayah}}">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Tahun Lahir
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                @if($siswa->tgl_lahir_ayah)
                                 <input type="text" class="datepicker form-control" name="tgl_lahir_ayah" aria-required="true" aria-invalid="true"  value="{{date('d F Y', strtotime($siswa->tgl_lahir_ayah))}}">
                                @else
                                 <input type="text" class="datepicker form-control" name="tgl_lahir_ayah" aria-required="true" aria-invalid="true">
                                @endif
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Pendidikan
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                 <select class="form-control show-tick" name="id_jenis_pendidikan_ayah" id="id_jenis_pendidikan_ayah">
                                    @foreach($jenisPendidikan as $pendidikan)
                                        <option value="{{$pendidikan->id_jenis_pendidikan}}">{{$pendidikan->nm_jenis_pendidikan}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Pekerjaan
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                 <select class="form-control show-tick" name="id_jenis_pekerjaan_ayah" id="id_jenis_pekerjaan_ayah">
                                    @foreach($jenisPekerjaan as $pekerjaan)
                                        <option value="{{$pekerjaan->id_jenis_pekerjaan}}">{{$pekerjaan->nm_jenis_pekerjaan}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Penghasilan
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                 <select class="form-control show-tick" name="id_jenis_penghasilan_ayah" id="id_jenis_penghasilan_ayah">
                                    @foreach($jenisPenghasilan as $penghasilan)
                                        <option value="{{$penghasilan->id_jenis_penghasilan}}">{{$penghasilan->nm_jenis_penghasilan}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Kebutuhan Khusus
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <select class="form-control show-tick" name="id_kebutuhan_khusus_ayah" id="id_kebutuhan_khusus_ayah">
                                    @foreach($kebutuhanKhusus as $kebutuhan)
                                        <option value="{{$kebutuhan->id_kebutuhan_khusus}}" {{$siswa->id_kebutuhan_khusus_ayah == $kebutuhan->id_kebutuhan_khusus ? 'selected' : ''}}>{{$kebutuhan->nm_kebutuhan_khusus}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>

                        <!-- Alamat Ayah -->
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Alamat Jalan
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="alamat_jalan_ayah" aria-required="true" aria-invalid="true"  value="{{$siswa->alamat_jalan_ayah}}">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   RT <br>
                                   <small>Nomor RT tempat tinggal ayah saat ini</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="alamat_rt_ayah" aria-required="true" aria-invalid="true"  value="{{$siswa->almat_rt_ayah}}">
                            </div>
                        </div>
                         <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   RW <br>
                                   <small>Nomor RW tempat tinggal ayah saat ini</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="alamat_rw_ayah" aria-required="true" aria-invalid="true"  value="{{$siswa->alamat_rw_ayah}}">
                            </div>
                        </div>
                         <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Nama Dusun <br>
                                   <small>Nama Dusun tempat tinggal ayah saat ini</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="alamat_dusun_ayah" aria-required="true" aria-invalid="true"  value="{{$siswa->alamat_dusun_ayah}}">
                            </div>
                        </div>
                         <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Nama Kelurahan/Desa <br>
                                   <small>Nama Kelurahan atau desa tempat tinggal ayah saat ini</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="alamat_kelurahan_ayah" aria-required="true" aria-invalid="true"  value="{{$siswa->alamat_kelurahan_ayah}}">
                            </div>
                        </div>
                         <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Kecamatan <br>
                                   <small>Nama kecamatan tempat tinggal ayah saat ini</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="alamat_kecamatan_ayah" aria-required="true" aria-invalid="true"  value="{{$siswa->alamat_kecamatan_ayah}}">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Kode Pos <br>
                                   <small>Kode Pos tempat tinggal ayah saat ini</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="alamat_kodepos_ayah" aria-required="true" aria-invalid="true"  value="{{$siswa->alamat_kodepos_ayah}}">
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Alamat Kota
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <select class="form-control show-tick" name="alamat_kota_ayah" id="alamat_kota_ayah">
                                    @foreach($kotaTinggal as $kota)
                                        <option value="{{$kota->id_kota}}" {{$siswa->alamat_kota_ayah == $kota->id_kota ? 'selected' : ''}}>{{$kota->nm_kota}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Alamat Provinsi
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <select class="form-control show-tick" name="alamat_provinsi_ayah" id="alamat_provinsi_ayah">
                                        @foreach($provinsi as $r)
                                            <option value="{{$r->id_provinsi}}" {{$siswa->alamat_provinsi_ayah == $r->id_provinsi ? 'selected' : ''}}>{{$r->nm_provinsi}}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        
                        <!-- Alamat Ayah -->

                        <br>
                        <div class="demo-color-box bg-success">
                                DATA IBU KANDUNG
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Nama Ibu Kandung
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="nm_ibu" aria-required="true" aria-invalid="true" value="{{$siswa->nm_ibu}}">
                            </div>
                        </div>
                        <br>
                        <!-- Status Ibu -->
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Status Ibu <br>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                               <select class="form-control show-tick" name="status_ibu" id="status_ibu">
                                    <option value="1" {{$siswa->status_ibu == 1 ? 'selected' : ''}}>Masih Hidup</option>
                                    <option value="2" {{$siswa->status_ibu == 2 ? 'selected' : ''}}>Wafat</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <!-- Status Ibu -->
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    NIK Ibu Kandung
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="nik_ibu" aria-invalid="true" value="{{$siswa->nik_ibu}}">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Tahun Lahir
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                @if($siswa->tgl_lahir_ibu)
                                 <input type="text" class="datepicker form-control" name="tgl_lahir_ibu" aria-required="true" aria-invalid="true"  value="{{date('d F Y', strtotime($siswa->tgl_lahir_ibu))}}">
                                @else
                                 <input type="text" class="datepicker form-control" name="tgl_lahir_ibu" aria-required="true" aria-invalid="true">
                                @endif
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Pendidikan
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                 <select class="form-control show-tick" name="id_jenis_pendidikan_ibu" id="id_jenis_pendidikan_ibu">
                                    @foreach($jenisPendidikan as $pendidikan)
                                        <option value="{{$pendidikan->id_jenis_pendidikan}}">{{$pendidikan->nm_jenis_pendidikan}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Pekerjaan
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                 <select class="form-control show-tick" name="id_jenis_pekerjaan_ibu" id="id_jenis_pekerjaan_ibu">
                                    @foreach($jenisPekerjaan as $pekerjaan)
                                        <option value="{{$pekerjaan->id_jenis_pekerjaan}}">{{$pekerjaan->nm_jenis_pekerjaan}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Penghasilan
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                 <select class="form-control show-tick" name="id_jenis_penghasilan_ibu" id="id_jenis_penghasilan_ibu">
                                    @foreach($jenisPenghasilan as $penghasilan)
                                        <option value="{{$penghasilan->id_jenis_penghasilan}}">{{$penghasilan->nm_jenis_penghasilan}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Kebutuhan Khusus
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <select class="form-control show-tick" name="id_kebutuhan_khusus_ibu" id="id_kebutuhan_khusus_ibu">
                                    @foreach($kebutuhanKhusus as $kebutuhan)
                                        <option value="{{$kebutuhan->id_kebutuhan_khusus}}" {{$siswa->id_kebutuhan_khusus_ibu == $kebutuhan->id_kebutuhan_khusus ? 'selected' : ''}}>{{$kebutuhan->nm_kebutuhan_khusus}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>

                        <!-- Alamat Ibu -->
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Alamat Jalan
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="alamat_jalan_ibu" aria-required="true" aria-invalid="true"  value="{{$siswa->alamat_jalan_ibu}}">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   RT <br>
                                   <small>Nomor RT tempat tinggal ibu saat ini</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="alamat_rt_ibu" aria-required="true" aria-invalid="true" value="{{$siswa->almat_rt_ibu}}">
                            </div>
                        </div>
                         <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   RW <br>
                                   <small>Nomor RW tempat tinggal ibu saat ini</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="alamat_rw_ibu" aria-required="true" aria-invalid="true" value="{{$siswa->alamat_rw_ibu}}">
                            </div>
                        </div>
                         <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Nama Dusun <br>
                                   <small>Nama Dusun tempat tinggal ibu saat ini</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="alamat_dusun_ibu" aria-required="true" aria-invalid="true" value="{{$siswa->alamat_dusun_ibu}}">
                            </div>
                        </div>
                         <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Nama Kelurahan/Desa <br>
                                   <small>Nama Kelurahan atau desa tempat tinggal ibu saat ini</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="alamat_kelurahan_ibu" aria-required="true" aria-invalid="true" value="{{$siswa->alamat_kelurahan_ibu}}">
                            </div>
                        </div>
                         <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Kecamatan <br>
                                   <small>Nama kecamatan tempat tinggal ibu saat ini</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="alamat_kecamatan_ibu" aria-required="true" aria-invalid="true" value="{{$siswa->alamat_kecamatan_ibu}}">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Kode Pos <br>
                                   <small>Kode Pos tempat tinggal ibu saat ini</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="alamat_kodepos_ibu" aria-required="true" aria-invalid="true" value="{{$siswa->alamat_kodepos_ibu}}">
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Alamat Kota
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <select class="form-control show-tick" name="alamat_kota_ibu" id="alamat_kota_ibu">
                                    @foreach($kotaTinggal as $kota)
                                        <option value="{{$kota->id_kota}}" {{$siswa->alamat_kota_ibu == $kota->id_kota ? 'selected' : ''}}>{{$kota->nm_kota}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Alamat Provinsi
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <select class="form-control show-tick" name="alamat_provinsi_ibu" id="alamat_provinsi_ibu">
                                        @foreach($provinsi as $r)
                                            <option value="{{$r->id_provinsi}}"  {{$siswa->alamat_provinsi_ibu == $r->id_provinsi ? 'selected' : ''}}>{{$r->nm_provinsi}}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        
                        <!-- Alamat Ibu -->

                        <div class="demo-color-box bg-success">
                                DATA WALI
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Nama Wali
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="nm_wali" aria-required="true" aria-invalid="true" value="{{$siswa->nm_wali}}">
                            </div>
                        </div>
                        <br>
                        <!-- Status Wali -->
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Status Wali <br>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                               <select class="form-control show-tick" name="status_wali" id="status_wali">
                                    <option value="1" {{$siswa->status_wali == 1 ? 'selected' : ''}}>Masih Hidup</option>
                                    <option value="2" {{$siswa->status_wali == 2 ? 'selected' : ''}}>Wafat</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <!-- Status Wali -->
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    NIK Wali
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="nik_wali"aria-invalid="true" value="{{$siswa->nik_wali}}">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Tahun Lahir
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                               @if($siswa->tgl_lahir_wali)
                                 <input type="text" class="datepicker form-control" name="tgl_lahir_wali" aria-required="true" aria-invalid="true"  value="{{date('d F Y', strtotime($siswa->tgl_lahir_wali))}}">
                                @else
                                  <input type="text" class="datepicker form-control" name="tgl_lahir_wali" aria-required="true" aria-invalid="true">
                                @endif
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Pendidikan
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                 <select class="form-control show-tick" name="id_jenis_pendidikan_wali" id="id_jenis_pendidikan_wali">
                                    @foreach($jenisPendidikan as $pendidikan)
                                        <option value="{{$pendidikan->id_jenis_pendidikan}}">{{$pendidikan->nm_jenis_pendidikan}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Pekerjaan
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                 <select class="form-control show-tick" name="id_jenis_pekerjaan_wali" id="id_jenis_pekerjaan_wali">
                                    @foreach($jenisPekerjaan as $pekerjaan)
                                        <option value="{{$pekerjaan->id_jenis_pekerjaan}}">{{$pekerjaan->nm_jenis_pekerjaan}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Penghasilan
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                 <select class="form-control show-tick" name="id_jenis_penghasilan_wali" id="id_jenis_penghasilan_wali">
                                    @foreach($jenisPenghasilan as $penghasilan)
                                        <option value="{{$penghasilan->id_jenis_penghasilan}}">{{$penghasilan->nm_jenis_penghasilan}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Kebutuhan Khusus
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <select class="form-control show-tick" name="id_kebutuhan_khusus_wali" id="id_kebutuhan_khusus_wali">
                                    @foreach($kebutuhanKhusus as $kebutuhan)
                                        <option value="{{$kebutuhan->id_kebutuhan_khusus}}">{{$kebutuhan->nm_kebutuhan_khusus}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="demo-color-box bg-success">
                                KONTAK ORTU/WALI
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Nomor Telepon Rumah <br>
                                    <small>Diisi nomor telepon rumah (milik orangtua, atau wali) tanpa tanda baca</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="nomor_telp_ortu" aria-required="true" aria-invalid="true" value="{{$siswa->nomor_telp_ortu}}">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Nomor HP <br>
                                    <small>Diisi nomor telepon selular (milik orangtua, atau wali) tanpa tanda baca</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="nomor_hp_ortu" aria-required="true" aria-invalid="true" value="{{$siswa->nomor_hp_ortu}}">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Email <br>
                                    <small>Diisi alamat surat elektronik(surel) peserta didik yang dapat dihubungi (milik orang tua, atau wali)</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="email_ortu" aria-required="true" aria-invalid="true" value="{{$siswa->email_ortu}}">
                            </div>
                        </div>
                        <br>
                        <div class="demo-color-box bg-success">
                                DATA PRIODIK
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Tinggi Badan <br>
                                    <small>Tinggi badan peserta didik dalam satuan centimeter</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="number" class="form-control" name="tinggi_badan" aria-required="true" aria-invalid="true" value="{{$siswa->tinggi_badan}}">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Berat Badan <br>
                                    <small>Berat badan peserta didik dalam satuan kilogram</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="number" class="form-control" name="berat_badan" aria-required="true" aria-invalid="true" value="{{$siswa->berat_badan}}">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Jarak tempat tinggal ke sekolah <br>
                                    <small>Dalam km</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="number" class="form-control" name="jarak_rumah_sekolah" aria-required="true" aria-invalid="true" value="{{$siswa->jarak_rumah_sekolah}}">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Waktu tempuh ke sekolah <br>
                                    <small>Lama tempuh peserta didik ke sekolah. Kolom kiri adalah jam, kolom kanan adalah menit. Misalnya, peserta didik memerlukan waktu tempuh 1 jam 15 menit, maka kotak kiri diisi 1 sedangkan kanan diisi 15. Apabila memerlukan waktu 25 menit, maka kotak kiri diisi 0 sedangkan kanan diisi 25</small>
                                </h2>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <input type="number" class="form-control" name="waktu_tempuh_sekolah_jam" aria-required="true" aria-invalid="true" value="{{$siswa->waktu_tempuh_sekolah_jam}}">
                            </div>
                             <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <input type="number" class="form-control" name="waktu_tempuh_sekolah_menit" aria-required="true" aria-invalid="true" value="{{$siswa->waktu_tempuh_sekolah_menit}}">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Jumlah Saudara Kandung
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="number" class="form-control" name="dari_x_bersaudara" aria-required="true" aria-invalid="true" value="{{$siswa->dari_x_bersaudara}}">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        {{csrf_field()}}
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<script type="text/javascript">
    $(function(){
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'DD MMMM YYYY',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: false
        });
    });
</script>