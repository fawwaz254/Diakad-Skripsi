<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#tendik/input-tendik')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        EDIT DATA TENAGA PENDIDIK
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-input-tendik/edit/'.$tendik->id_staff)}}">
                        {{csrf_field()}}
                        <div class="demo-color-box bg-success">
                                Identitas Pendidik dan Tenaga Pendidik
                        </div>
                        <h2 class="card-inside-title">
                            Nama Tendik
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_pengguna" required="" aria-invalid="true" value="{{$tendik->nm_pengguna}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Gelar Depan <small><strong>Gelar depan dari tendik, misal: Ir., Dr., dr., dll</strong></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="gelar_depan" aria-invalid="true" value="{{$tendik->gelar_depan}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Gelar Belakang <small><strong>Gelar belakang dari tendik, misal: S.Pd., S.Si., S.T., dll</strong></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="gelar_belakang" aria-invalid="true" value="{{$tendik->gelar_belakang}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            NIK Tendik
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="nik_ptk" aria-invalid="true" value="{{$tendik->nik_ptk}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Jenis Kelamin
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="jenis_kelamin">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="1" @if($tendik->jenis_kelamin == 1) selected @endif>Laki-Laki</option>
                                    <option value="2" @if($tendik->jenis_kelamin == 2) selected @endif>Perempuan</option>
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
                                    <option value="{{$data->id_kota}}" @if($data->id_kota == $tendik->id_kota_lahir) selected @endif>{{$data->nm_kota}}</option>
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
                                        aria-invalid="true" value="{{$tendik->tgl_lahir}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Ibu Kandung
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_ibu_kandung" aria-invalid="true" value="{{$tendik->nm_ibu_kandung}}">
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
                                <input type="text" class="form-control" name="alamat_jalan" aria-invalid="true" value="{{$tendik->alamat_jalan}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Alamat RT
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="alamat_rt" aria-invalid="true" value="{{$tendik->alamat_rt}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Alamat RW
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="alamat_rw" aria-invalid="true" value="{{$tendik->alamat_rw}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Alamat Dusun
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="alamat_dusun" aria-invalid="true" value="{{$tendik->alamat_dusun}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Alamat Kelurahan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="alamat_kelurahan" aria-invalid="true" value="{{$tendik->alamat_kelurahan}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Alamat Kecamatan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="alamat_kecamatan" aria-invalid="true" value="{{$tendik->alamat_kecamatan}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Alamat Kodepos
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="alamat_kodepos" aria-invalid="true" value="{{$tendik->alamat_kodepos}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Alamat Provinsi
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                               <select class="form-control show-tick" name="alamat_provinsi">
                                    <option value="">-- Pilih Alamat Provinsi --</option>
                                    @foreach($provinsi as $data)
                                    <option value="{{$data->id_provinsi}}" @if($data->id_provinsi == $tendik->alamat_provinsi) selected @endif>{{$data->nm_provinsi}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Alamat Kota
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                               <select class="form-control show-tick" name="alamat_kota">
                                    <option value="">-- Pilih Alamat Kota --</option>
                                    @foreach($kota as $data)
                                    <option value="{{$data->id_kota}}" @if($tendik->alamat_kota == $data->id_kota) selected @endif>{{$data->nm_kota}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Alamat Latitude
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="alamat_latitude" aria-invalid="true" value="{{$tendik->alamat_latitude}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Alamat Longitude
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="alamat_longitude" aria-invalid="true" value="{{$tendik->alamat_longitude}}">
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
                                    <option value="{{$data->id_agama}}" @if($data->id_agama == $tendik->id_agama) selected @endif>{{$data->nm_agama}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            NPWP <small><strong>Nomor Pokok Wajib Pajak</strong></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="npwp_ptk" aria-invalid="true" value="{{$tendik->npwp_ptk}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Wajib Pajak
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_wajib_pajak_ptk" aria-invalid="true" value="{{$tendik->nm_wajib_pajak_ptk}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kewarganegaraan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="kewarganegaraan">
                                    <option value="">-- Pilih Kewarganegaraan --</option>
                                    <option value="1" @if($tendik->kewarganegaraan == 1) selected @endif>WNI</option>
                                    <option value="2" @if($tendik->kewarganegaraan == 2) selected @endif>WNA</option>
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Status Perkawinan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="status_kawin">
                                    <option value="">-- Pilih Status Kawin --</option>
                                    <option value="1" @if($tendik->status_kawin == 1) selected @endif>Kawin</option>
                                    <option value="2" @if($tendik->status_kawin == 2) selected @endif>Belum Kawin</option>
                                    <option value="3" @if($tendik->status_kawin == 3) selected @endif>Janda/Duda</option>
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Pasangan PTK
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_pasangan_ptk" aria-invalid="true" value="{{$tendik->nm_pasangan_ptk}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Jenis Pekerjaan Pasangan PTK
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                               <select class="form-control show-tick" name="id_jenis_pekerjaan_pasangan_ptk">
                                    <option value="">-- Pilih Jenis Pekerjaan  --</option>
                                    @foreach($pekerjaan as $data)
                                    <option value="{{$data->id_jenis_pekerjaan}}" @if($tendik->id_jenis_pekerjaan_pasangan_ptk == $data->id_jenis_pekerjaan) selected @endif>{{$data->nm_jenis_pekerjaan}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            NIP Pasangan PTK <small><strong>Jika Pasangan PTK Bekerja Sebagai PNS</strong></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nip_pasangan_ptk" aria-invalid="true" value="{{$tendik->nip_pasangan_ptk}}">
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
                                    <option value="{{$data->id_unit_kerja}}" @if($tendik->id_unit_kerja == $data->id_unit_kerja) selected @endif>{{$data->nm_unit_kerja}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Jenis Jabatan Internal
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                               <select class="form-control show-tick" name="jenis_jabatan" >
                                    <option value="" selected >-- Pilih Jenis Jabatan  --</option>
                                    @if($tendik->jenis_jabatan == 1)
                                        <option value="1" selected >Kepala Unit Sarana & Prasarana</option>
                                    @else
                                        <option value="1">Kepala Unit Sarana & Prasarana</option>
                                    @endif
                                    @if($tendik->jenis_jabatan == 2)
                                        <option value="2" selected >Kepala Unit Keuangan</option>
                                    @else
                                        <option value="2">Kepala Unit Keuangan</option>
                                    @endif
                                    @if($tendik->jenis_jabatan == 3)
                                        <option value="3" selected >Staf Unit Sarana & Prasarana</option>
                                    @else
                                        <option value="3">Staf Unit Sarana & Prasarana</option>
                                    @endif
                                    @if($tendik->jenis_jabatan == 4)
                                        <option value="4" selected >Staf Unit Keuangan</option>
                                    @else
                                        <option value="4">Staf Unit Keuangan</option>
                                    @endif
                                    @if($tendik->jenis_jabatan == 5)
                                        <option value="5" selected >Staf Unit Sumber Daya</option>
                                    @else
                                        <option value="5">Staf Unit Sumber Daya</option>
                                    @endif
                                    @if($tendik->jenis_jabatan == 98)
                                        <option value="98" selected >Kepala Unit</option>
                                    @else
                                        <option value="98">Kepala Unit</option>
                                    @endif
                                    @if($tendik->jenis_jabatan == 99)
                                        <option value="99" selected >Staf Unit</option>
                                    @else
                                        <option value="99">Staf Unit</option>
                                    @endif
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
                                    <option value="{{$data->id_jenis_kepegawaian}}" @if($data->id_jenis_kepegawaian == $tendik->id_jenis_kepegawaian) selected @endif>{{$data->nm_jenis_kepegawaian}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            NIP <small><strong>Nomor Induk Pegawai</strong></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nip_staff" required="" aria-invalid="true" value="{{$tendik->nip_staff}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            NIY/NIGK <small><strong>Nomot Induk Yayasan / Nomor Induk Guru Kontrak</strong></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="niy_nigk_ptk" aria-invalid="true" value="{{$tendik->niy_nigk_ptk}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            NUPTK <small><strong>*Nomor Unik Pendidik dan Tenaga Kependidikan</strong></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nuptk" aria-invalid="true" value="{{$tendik->nuptk}}">
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
                                    <option value="{{$data->id_jenis_ptk}}" @if($tendik->id_jenis_ptk == $data->id_jenis_ptk) selected @endif>{{$data->nm_jenis_ptk}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nomor SK Pengangkatan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nomor_sk_pengangkatan" aria-invalid="true" value="{{$tendik->nomor_sk_pengangkatan}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal SK Pengangkatan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_sk_pengangkatan"
                                        aria-invalid="true" value="{{$tendik->tgl_sk_pengangkatan}}">
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
                                    <option value="{{$data->id_jenis_lembaga_pengangkat}}" @if($tendik->id_jenis_lembaga_pengangkat == $data->id_jenis_lembaga_pengangkat) selected @endif>{{$data->nm_jenis_lembaga_pengangkat}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nomor SK CPNS
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nomor_sk_cpns" aria-invalid="true" value="{{$tendik->nomor_sk_cpns}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal Mulai PNS
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_mulai_pns"
                                        aria-invalid="true" value="{{$tendik->tgl_mulai_pns}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Pangkat/Golongan <small><strong>Diisi dengan pangkat dan golongan yang terbaru</strong></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="golongan_ptk" aria-invalid="true" value="{{$tendik->golongan_ptk}}">
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
                                    <option value="{{$data->id_jenis_sumber_gaji}}" @if($tendik->id_jenis_sumber_gaji == $data->id_jenis_sumber_gaji) selected @endif>{{$data->nm_jenis_sumber_gaji}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kartu Pegawai <small><strong>Nomor kartu pegawai bagi PNS</strong></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nomor_kartu_pegawai" aria-invalid="true" value="{{$tendik->nomor_kartu_pegawai}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kartu Istri (KARIS) atau Kartu Suami (KARSU) 
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nomor_kartu_pasangan" aria-invalid="true" value="{{$tendik->nomor_kartu_pasangan}}">
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
                                    <option value="0" @if($tendik->is_lisensi_kepsek == 0) selected @endif>Tidak Punya Lisensi</option>
                                    <option value="1" @if($tendik->is_lisensi_kepsek == 1) selected @endif>Punya Lisensi</option>
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
                                    <option value="{{$data->id_jenis_keahlian_lab}}" @if($tendik->id_jenis_keahlian_lab == $data->id_jenis_keahlian_lab) selected @endif>{{$data->nm_jenis_keahlian_lab}}</option>
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
                                    <option value="0" @if($tendik->is_keahlian_braile == 0) selected @endif>Tidak</option>
                                    <option value="1" @if($tendik->is_keahlian_braile == 1) selected @endif>Ya</option>
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                           Keahlian Bahasa Isyarat
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="is_keahlian_bahasa_isyarat">
                                    <option value="0" @if($tendik->is_keahlian_bahasa_isyarat == 0) selected @endif>Tidak</option>
                                    <option value="1" @if($tendik->is_keahlian_bahasa_isyarat == 1) selected @endif>Ya</option>
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
                                <input type="number" class="form-control" name="nomor_telp" aria-invalid="true" value="{{$tendik->nomor_telp}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                           Nomor HP
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="nomor_hp" aria-invalid="true" value="{{$tendik->nomor_hp}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                           Email
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="email" class="form-control" name="email" aria-invalid="true" value="{{$tendik->email}}">
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
                                <input type="text" class="form-control" name="nomor_sk_penugasan" aria-invalid="true" value="{{$tendik->nomor_sk_penugasan}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal SK Penugasan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_sk_penugasan"
                                        aria-invalid="true" value="{{$tendik->tgl_sk_penugasan}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                           Apakah Sekolah Induk
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="is_sekolah_induk">
                                    <option value="0" @if($tendik->is_sekolah_induk == 0) selected @endif>Tidak</option>
                                    <option value="1" @if($tendik->is_sekolah_induk == 1) selected @endif>Ya</option>
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
                                    @foreach($data_status_aktif_tendik as $data)
                                    <option value="{{$data->id_status_pengguna}}" @if($tendik->id_status_pengguna == $data->id_status_pengguna) selected @endif>{{$data->nm_status_pengguna}} -
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