<div class="container-fluid">
    <div class="block-header">
        <!-- <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#magang-siswa/nama-magang')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2> -->
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-green">
                    <h2>
                        UPDATE DATA SISWA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-insert-update-siswa/update/'.$siswa->nis_siswa)}}">
                        {{csrf_field()}}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="header bg-green">
                            <h2>
                                DATA PRIBADI
                            </h2>
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
                                    <option value="{{$siswa->jenis_kelamin}}" selected>
                                        @if($siswa->jenis_kelamin == "1")
                                            Laki-Laki
                                        @else
                                            Perempuan
                                        @endif
                                    </option>
                                    <option value="1">Laki-Laki</option>
                                    <option value="2">Perempuan</option>
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
                                <input type="text" class="form-control" name="nik_siswa" aria-required="true" aria-invalid="true" value="{{$siswa->nik_siswa}}">
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
                                    <option value="{{ isset($kotaLahir->id_kota) ? $kotaLahir->id_kota : '' }}" selected="">{{ isset($kotaLahir->id_kota) ? $kotaLahir->id_kota : '' }}</option>
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
                                <input type="text" class="datepicker form-control" name="tgl_lahir" aria-required="true" aria-invalid="true" value="{{$siswa->tgl_lahir}}">
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
                                    <option value="{{ isset($siswa->id_agama) ? $siswa->id_agama : '' }}" selected="">{{ isset($siswa->nm_agama) ? $siswa->nm_agama : '' }}</option>
                                    @foreach($agama as $agama)
                                        <option value="{{$agama->id_agama}}">{{$agama->nm_agama}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Kewarganegaraan
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <select class="form-control show-tick" name="kewarganegaraan" id="kewarganegaraan">
                                    <option value="{{$siswa->kewarganegaraan}}">
                                        @if($siswa->kewarganegaraan == "2")
                                            WNA
                                        @else
                                            WNI
                                        @endif
                                    </option>
                                    <option value="1">WNI</option>
                                    <option value="2">WNA</option>
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
                                    <option value="{{ isset($siswa->id_kebutuhan_khusus) ? $siswa->id_kebutuhan_khusus : '' }}">{{ isset($siswa->nm_kebutuhan_khusus) ? $siswa->nm_kebutuhan_khusus : '' }}</option>
                                    @foreach($kebutuhanKhusus as $kebutuhan)
                                        <option value="{{$kebutuhan->id_kebutuhan_khusus}}">{{$kebutuhan->nm_kebutuhan_khusus}}</option>
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
                                        @foreach($provinsi as $provinsi)
                                            <option value="{{$provinsi->id_provinsi}}">{{$provinsi->nm_provinsi}}</option>
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
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                   Tempat Tinggal <br>
                                   <small>Kepemilikan tempat tinggal peserta didik saat ini</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <select class="form-control show-tick" name="id_jenis_tinggal" id="id_jenis_tinggal">
                                    <option value="{{ isset($siswa->id_jenis_tinggal) ? $siswa->id_jenis_tinggal : '' }}" selected>
                                        {{ isset($siswa->nm_jenis_tinggal) ? $siswa->nm_jenis_tinggal : '' }}
                                    </option>
                                    @foreach($jenisTinggal as $jenis)
                                        <option value="{{$jenis->id_jenis_tinggal}}">{{$jenis->nm_jenis_tinggal}}</option>
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
                                    <option value="{{ isset($siswa->id_jenis_transportasi) ? $siswa->id_jenis_transportasi : '' }}" selected>
                                        {{ isset($siswa->nm_jenis_transportasi) ? $siswa->nm_jenis_transportasi : '' }}
                                    </option>
                                    @foreach($jenisTransportasi as $tranportasi)
                                        <option value="{{$tranportasi->id_jenis_transportasi}}">{{$tranportasi->nm_jenis_transportasi}}</option>
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
                                    <option value="{{$siswa->is_penerima_kps}}" selected>
                                        @if($siswa->is_penerima_kps == '1')
                                            Ya
                                        @else
                                            Tidak
                                        @endif
                                    </option>
                                    <option value="1">Ya</option>
                                    <option value="0">Tidak</option>
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
                                    <option value="{{$siswa->is_punya_kip}}" selected="">
                                        @if($siswa->is_punya_kip == "0")
                                            Tidak
                                        @else
                                            Ya
                                        @endif
                                    </option>
                                    <option value="1">Ya</option>
                                    <option value="0">Tidak</option>
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
                                <select class="form-control show-tick" name="is_layak_pip" id="is_punya_kip">
                                    <option value="{{$siswa->is_punya_pip}}" selected="">
                                        @if($siswa->is_punya_pip == "0")
                                            Tidak
                                        @else
                                            Ya
                                        @endif
                                    </option>
                                    <option value="1">Ya</option>
                                    <option value="0">Tidak</option>
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
                        <div class="header bg-green">
                            <h2>
                                DATA AYAH KANDUNG
                            </h2>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Nama Ayah Kandung
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="nm_ayah" required="" aria-required="true" aria-invalid="true" value="{{$siswa->nm_ayah}}">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    NIK Ayah Kandung
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="nik_ayah" required="" aria-required="true" aria-invalid="true" value="{{$siswa->nik_ayah}}">
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
                               <input type="text" class="datepicker form-control" name="tgl_lahir_ayah" aria-required="true" aria-invalid="true" value="{{$siswa->tgl_lahir_ayah}}">
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
                                        <option value="{{$kebutuhan->id_kebutuhan_khusus}}">{{$kebutuhan->nm_kebutuhan_khusus}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="header bg-green">
                            <h2>
                                DATA IBU KANDUNG
                            </h2>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Nama Ibu Kandung
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="nm_ibu" required="" aria-required="true" aria-invalid="true" value="">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    NIK Ibu Kandung
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="nik_ibu" required="" aria-required="true" aria-invalid="true" value="">
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
                               <input type="text" class="datepicker form-control" name="tgl_lahir_ibu" aria-required="true" aria-invalid="true" value="{{$siswa->tgl_lahir_ibu}}">
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
                                        <option value="{{$kebutuhan->id_kebutuhan_khusus}}">{{$kebutuhan->nm_kebutuhan_khusus}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="header bg-green">
                            <h2>
                                DATA WALI
                            </h2>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Nama Wali
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="nm_wali" aria-required="true" aria-invalid="true" value="">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    NIK Wali
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="nik_wali" aria-required="true" aria-invalid="true" value="">
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
                               <input type="text" class="datepicker form-control" name="tgl_lahir_wali" aria-required="true" aria-invalid="true" value="{{$siswa->tgl_lahir_wali}}">
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
                        <div class="header bg-green">
                            <h2>
                                KONTAK
                            </h2>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Nomor Telepon Rumah <br>
                                    <small>Diisi nomor telepon rumah (milik pribadi, orangtua, atau wali) tanpa tanda baca</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="nomor_telp" aria-required="true" aria-invalid="true" value="">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Nomor HP <br>
                                    <small>Diisi nomor telepon selular (milik pribadi, orangtua, atau wali) tanpa tanda baca</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="nomor_hp" aria-required="true" aria-invalid="true" value="">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Email <br>
                                    <small>Diisi alamat surat elektronik(surel) peserta didik yang dapat dihubungi (milik pribadi, orang tua, atau wali)</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="email" aria-required="true" aria-invalid="true" value="">
                            </div>
                        </div>
                        <br>
                        <div class="header bg-green">
                            <h2>
                                DATA PRIODIK
                            </h2>
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
                                <input type="number" class="form-control" name="tinggi_badan" aria-required="true" aria-invalid="true" value="">
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
                                <input type="number" class="form-control" name="berat_badan" aria-required="true" aria-invalid="true" value="">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Jarak tempat tinggal ke sekolah
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="number" class="form-control" name="jarak_rumah_sekolah" aria-required="true" aria-invalid="true" value="">
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
                                <input type="number" class="form-control" name="waktu_tempuh_sekolah_jam" aria-required="true" aria-invalid="true" value="">
                            </div>
                             <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <input type="number" class="form-control" name="waktu_tempuh_sekolah_menit" aria-required="true" aria-invalid="true" value="">
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
                                <input type="number" class="form-control" name="dari_x_bersaudara" aria-required="true" aria-invalid="true" value="">
                            </div>
                        </div>
                        <br>
                        <div class="header bg-green">
                            <h2>
                                PRESTASI
                            </h2>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Jenis Prestasi
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <select class="form-control show-tick" name="jenis_prestasi" id="jenis_prestasi">
                                    <option value="1">Sains</option>
                                    <option value="2">Seni</option>
                                    <option value="3">Olahraga</option>
                                    <option value="4">Lain-Lain</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Tingkat Prestasi
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <select class="form-control show-tick" name="id_tingkat_prestasi_siswa" id="id_tingkat_prestasi_siswa">
                                    @foreach($tingkatPrestasi as $prestasi)
                                        <option value="{{$prestasi->id_tingkat_prestasi_siswa}}">{{$prestasi->nm_tingkat_prestasi_siswa}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Nama Prestasi
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="number" class="form-control" name="nm_prestasi_c_siswa" aria-required="true" aria-invalid="true" value="">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Tahun
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="datepicker form-control" name="tgl_prestasi_c_siswa" required="" aria-required="true" aria-invalid="true" value="{{$siswa->tgl_lahir}}">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Penyelenggara
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="number" class="form-control" name="penyelenggara_prestasi_c_siswa" aria-required="true" aria-invalid="true" value="">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Peringkat
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="number" class="form-control" name="peringkat_prestasi_c_siswa" aria-required="true" aria-invalid="true" value="">
                            </div>
                        </div>
                        <br>
                        <div class="header bg-green">
                            <h2>
                                BEASISWA
                            </h2>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Jenis Beasiswa
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <select class="form-control show-tick" name="jenis_beasiswa_c_siswa" id="jenis_beasiswa_c_siswa">
                                    <option value="01">Anak Berprestasi</option>
                                    <option value="02">Anak Miskin</option>
                                    <option value="03">Pendidikan</option>
                                    <option value="04">Unggulan</option>
                                    <option value="99">Lain-Lain</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Keterangan
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="keterangan_beasiswa_c_siswa" aria-required="true" aria-invalid="true" value="">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Tahun Mulai
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="number" class="form-control" name="tahun_mulai_beasiswa_c_siswa" aria-required="true" aria-invalid="true" value="">
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    Tahun Selesai
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="number" class="form-control" name="tahun_selesai_beasiswa_c_siswa" aria-required="true" aria-invalid="true" value="">
                            </div>
                        </div>
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