<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#guru/input-guru')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        INPUT GURU BARU
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-input-guru/add/'.$id_guru)}}">
                        {{csrf_field()}}
                         <div class="demo-color-box bg-success">
                                Identitas Pendidik dan Tenaga Pendidik
                        </div>
                        <h2 class="card-inside-title">
                            Nama Guru
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_pengguna" required="" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Gelar Depan <small><strong>Gelar depan dari guru, misal: Ir., Dr., dr., dll</strong></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="gelar_depan" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Gelar Belakang <small><strong>Gelar belakang dari guru, misal: S.Pd., S.Si., S.T., dll</strong></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="gelar_belakang" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            NIK Guru
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="nik_ptk" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Jenis Kelamin
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="jenis_kelamin">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="1">Laki-Laki</option>
                                    <option value="2">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tempat Lahir
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                               <select class="form-control show-tick" name="id_kota_lahir">
                                    <option value="">-- Pilih Kota Lahir --</option>
                                    @foreach($kota as $data)
                                    <option value="{{$data->id_kota}}">{{$data->nm_kota}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal Lahir
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_lahir"
                                        aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Ibu Kandung
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_ibu_kandung" aria-invalid="true">
                            </div>
                        </div>
                        <div class="demo-color-box bg-success">
                                Data Pribadi
                        </div>
                        <h2 class="card-inside-title">
                            Alamat Jalan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="alamat_jalan" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Alamat RT
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="alamat_rt" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Alamat RW
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="alamat_rw" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Alamat Dusun
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="alamat_dusun" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Alamat Kelurahan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="alamat_kelurahan" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Alamat Kecamatan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="alamat_kecamatan" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Alamat Kodepos
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="alamat_kodepos" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Alamat Provinsi
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                               <select class="form-control show-tick" name="alamat_provinsi" id="alamat_provinsi">
                                    <option value="">-- Pilih Alamat Provinsi --</option>
                                    @foreach($provinsi as $data)
                                    <option value="{{$data->id_provinsi}}">{{$data->nm_provinsi}}</option>
                                    @endforeach
                                </select>
                                 <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Alamat Kota
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                               <select class="form-control show-tick" name="alamat_kota" id="alamat_kota">
                                    <option value="">-- Pilih Alamat Kota --</option>
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Alamat Latitude
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="alamat_latitude" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Alamat Longitude
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="alamat_longitude" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Agama dan Kepercayaan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                               <select class="form-control show-tick" name="id_agama">
                                    <option value="">-- Pilih Agama dan Kepercayaan --</option>
                                    @foreach($agama as $data)
                                    <option value="{{$data->id_agama}}">{{$data->nm_agama}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            NPWP <small><strong>Nomor Pokok Wajib Pajak</strong></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="npwp_ptk" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Wajib Pajak
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_wajib_pajak_ptk" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kewarganegaraan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="kewarganegaraan">
                                    <option value="">-- Pilih Kewarganegaraan --</option>
                                    <option value="1">WNI</option>
                                    <option value="2">WNA</option>
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Status Perkawinan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="status_kawin" id="status_kawin">
                                    <option value="">-- Pilih Status Kawin --</option>
                                    <option value="1">Kawin</option>
                                    <option value="2">Belum Kawin</option>
                                    <option value="3">Janda/Duda</option>
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title" id="title-pasangan" style="display: none">
                            Nama Pasangan PTK
                        </h2>
                        <div class="row clearfix" id="pasangan" style="display: none">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_pasangan_ptk" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title" id="title-pekerjaan-pasangan" style="display: none">
                            Jenis Pekerjaan Pasangan PTK
                        </h2>
                        <div class="row clearfix" id="pekerjaan-pasangan" style="display: none">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                               <select class="form-control show-tick" name="id_jenis_pekerjaan_pasangan_ptk">
                                    <option value="">-- Pilih Jenis Pekerjaan  --</option>
                                    @foreach($pekerjaan as $data)
                                    <option value="{{$data->id_jenis_pekerjaan}}">{{$data->nm_jenis_pekerjaan}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title" id="title_nip_pasangan" style="display: none">
                            NIP Pasangan PTK <small><strong>Jika Pasangan PTK Bekerja Sebagai PNS</strong></small>
                        </h2>
                        <div class="row clearfix" id="nip_pasangan" style="display: none">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nip_pasangan_ptk" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Unit Kerja
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_unit_kerja" required="">
                                    <option value="">-- Pilih Unit Kerja --</option>
                                    @foreach($data_unit_kerja as $data)
                                    <option value="{{$data->id_unit_kerja}}">{{$data->nm_unit_kerja}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="demo-color-box bg-success">
                                Kepegawaian
                        </div>
                        <h2 class="card-inside-title">
                            Jenis Kepegawaian
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                               <select class="form-control show-tick" name="id_jenis_kepegawaian">
                                    <option value="">-- Pilih Jenis Kepegawaian  --</option>
                                    @foreach($pegawai as $data)
                                    <option value="{{$data->id_jenis_kepegawaian}}">{{$data->nm_jenis_kepegawaian}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            NIP <small><strong>Nomor Induk Pegawai</strong></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nip_guru" required="" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            NIY/NIGK <small><strong>Nomot Induk Yayasan / Nomor Induk Guru Kontrak</strong></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="niy_nigk_ptk" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            NUPTK <small><strong>*Nomor Unik Pendidik dan Tenaga Kependidikan</strong></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nuptk" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Jenis PTK
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                               <select class="form-control show-tick" name="id_jenis_ptk">
                                    <option value="">-- Pilih Jenis PTK  --</option>
                                    @foreach($ptk as $data)
                                    <option value="{{$data->id_jenis_ptk}}">{{$data->nm_jenis_ptk}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nomor SK Pengangkatan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nomor_sk_pengangkatan" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal SK Pengangkatan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_sk_pengangkatan"
                                        aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                           Lembaga Pengangkat
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_jenis_lembaga_pengangkat">
                                    <option value="">-- Pilih Jenis Lembaga Pengakat  --</option>
                                    @foreach($pengangkat as $data)
                                    <option value="{{$data->id_jenis_lembaga_pengangkat}}">{{$data->nm_jenis_lembaga_pengangkat}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nomor SK CPNS
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nomor_sk_cpns" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal Mulai PNS
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_mulai_pns"
                                        aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Pangkat/Golongan <small><strong>Diisi dengan pangkat dan golongan yang terbaru</strong></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="golongan_ptk" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                           Sumber Gaji
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_jenis_sumber_gaji">
                                    <option value="">-- Pilih Jenis Sumber Gaji  --</option>
                                    @foreach($gaji as $data)
                                    <option value="{{$data->id_jenis_sumber_gaji}}">{{$data->nm_jenis_sumber_gaji}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kartu Pegawai <small><strong>Nomor kartu pegawai bagi PNS</strong></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nomor_kartu_pegawai" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kartu Istri (KARIS) atau Kartu Suami (KARSU) 
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nomor_kartu_pasangan" aria-invalid="true">
                            </div>
                        </div>
                        <div class="demo-color-box bg-success">
                                Kompetensi Khusus
                        </div>
                        <h2 class="card-inside-title">
                           Punya Lisensi Kepala Sekolah
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="is_lisensi_kepsek">
                                    <option value="0">Tidak Punya Lisensi</option>
                                    <option value="1">Punya Lisensi</option>
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                           Keahlian Laboratorium <small><strong>Spesifikasi keahlian laboratorium yang dimiliki oleh PTK, dibuktikan dengan adanya sertifikat sebagai laboran</strong></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_jenis_keahlian_lab">
                                    <option value="">-- Pilih Jenis Keahlian Lab  --</option>
                                    @foreach($lab as $data)
                                    <option value="{{$data->id_jenis_keahlian_lab}}">{{$data->nm_jenis_keahlian_lab}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                           Keahlian Braile
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="is_keahlian_braile">
                                    <option value="0">Tidak</option>
                                    <option value="1">Ya</option>
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                           Keahlian Bahasa Isyarat
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="is_keahlian_bahasa_isyarat">
                                    <option value="0">Tidak</option>
                                    <option value="1">Ya</option>
                                </select>
                            </div>
                        </div>
                        <div class="demo-color-box bg-success">
                                Kontak
                        </div>
                        <h2 class="card-inside-title">
                           Nomor Telepon
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="nomor_telp" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                           Nomor HP
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="nomor_hp" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                           Email
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="email" class="form-control" name="email" aria-invalid="true">
                            </div>
                        </div>
                        <div class="demo-color-box bg-success">
                                Penugasan
                        </div>
                        <h2 class="card-inside-title">
                           Nomor SK Penugasan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nomor_sk_penugasan" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal SK Penugasan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_sk_penugasan"
                                        aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                           Apakah Sekolah Induk
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="is_sekolah_induk">
                                    <option value="0">Tidak</option>
                                    <option value="1">Ya</option>
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Status Aktif
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_status_pengguna" required="">
                                    <option value="">-- Pilih Status Aktif --</option>
                                    @foreach($data_status_aktif_guru as $data)
                                    <option value="{{$data->id_status_pengguna}}">{{$data->nm_status_pengguna}} -
                                        @if($data->aktif_status_pengguna == 0) Keluar/Non-Aktif @else Aktif @endif</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
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
<script>
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
<script type="text/javascript">
    $(document).ready(function() {
        $('select').select();
    });

    var modul_url       = 'guru';

    $('#alamat_provinsi').on('change', function(e){
    console.log(e);
    var id_provinsi = e.target.value;
        $.get(base_url + '/' + role_url + '/' + modul_url + '/' + 'input-guru/get-kota/' + id_provinsi,function(data) {
            console.log(data);
            $('#alamat_kota').empty();


            $('#alamat_kota').append($("<option>")
                .attr("value", 0)
                .text("-- Semua --")
            );
            $.each(data, function(index, kotaObj){
                $('#alamat_kota').append($("<option>")
                    .attr("value", kotaObj.id_kota)
                    .text(kotaObj.nm_kota)
                );
            })

            $('select').select();
        });
    });
</script>
<script>  
$(document).ready(function(){
    $('#status_kawin').on('change', function() {
      if ( this.value == '1')
      {
        $("#title-pasangan").show();
        $("#title_nip_pasangan").show();
        $("#title-pekerjaan-pasangan").show();
        $("#pasangan").show();
        $("#nip_pasangan").show();
        $("#pekerjaan-pasangan").show();

      }
    });
});
</script>