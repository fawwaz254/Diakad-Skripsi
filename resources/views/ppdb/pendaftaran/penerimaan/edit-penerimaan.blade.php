<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#pendaftaran/penerimaan')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        EDIT PENERIMAAN
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-penerimaan/edit/'.$data_penerimaan->id_penerimaan)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Tahun <small><b>* Tahun Yang Ada Pada Data Nama Semester</b></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="tahun_penerimaan">
                                @foreach($data_semester_tahun as $data)
                                    @if($data->thn_akademik_semester == $data_penerimaan->tahun_penerimaan)
                                        <option value="{{$data->thn_akademik_semester}}" selected >{{$data->thn_akademik_semester}}</option>
                                    @else
                                        <option value="{{$data->thn_akademik_semester}}" >{{$data->thn_akademik_semester}}</option>
                                    @endif
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Jalur
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_jalur">
                                @foreach($data_jalur as $data)
                                    @if($data->id_jalur == $data_penerimaan->id_jalur)
                                        <option value="{{$data->id_jalur}}" selected >{{$data->nm_jalur}}</option>
                                    @else
                                        <option value="{{$data->id_jalur}}" >{{$data->nm_jalur}}</option>
                                    @endif
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Penerimaan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_penerimaan" required="" aria-required="true" aria-invalid="true" value="{{$data_penerimaan->nm_penerimaan}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Gelombang
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="gelombang_penerimaan">
                                    @if($data_penerimaan->gelombang_penerimaan == 1)
                                        <option value="1" selected >1</option>
                                    @else
                                        <option value="1">1</option>
                                    @endif
                                    @if($data_penerimaan->gelombang_penerimaan == 2)
                                        <option value="2" selected >2</option>
                                    @else
                                        <option value="2">2</option>
                                    @endif
                                    @if($data_penerimaan->gelombang_penerimaan == 3)
                                        <option value="3" selected >3</option>
                                    @else
                                        <option value="3">3</option>
                                    @endif
                                    @if($data_penerimaan->gelombang_penerimaan == 4)
                                        <option value="4" selected >4</option>
                                    @else
                                        <option value="4">4</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Semester <small><b>* Nama Yang Ada Pada Data Nama Semester</b></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="nm_semester_penerimaan">
                                @foreach($data_semester_nama as $data)
                                    @if($data->nm_semester == $data_penerimaan->nm_semester_penerimaan)
                                        <option value="{{$data->nm_semester}}" selected >{{$data->nm_semester}}</option>
                                    @else
                                        <option value="{{$data->nm_semester}}">{{$data->nm_semester}}</option>
                                    @endif
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Jumlah Pilihan Jurusan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="jml_pilihan_jurusan">
                                    @if($data_penerimaan->jml_pilihan_jurusan == 1)
                                        <option value="1" selected >1</option>
                                    @else
                                        <option value="1">1</option>
                                    @endif
                                    @if($data_penerimaan->jml_pilihan_jurusan == 2)
                                        <option value="2" selected >2</option>
                                    @else
                                        <option value="2">2</option>
                                    @endif
                                    @if($data_penerimaan->jml_pilihan_jurusan == 3)
                                        <option value="3" selected >3</option>
                                    @else
                                        <option value="3">3</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Pembukaan Registrasi
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_awal_registrasi" required="" aria-required="true" aria-invalid="true" value="{{$tgl_awal_registrasi}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Penutupan Registrasi
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_akhir_registrasi" required="" aria-required="true" aria-invalid="true" value="{{$tgl_akhir_registrasi}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Pembukaan Verifikasi
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_awal_verifikasi" required="" aria-required="true" aria-invalid="true" value="{{$tgl_awal_verifikasi}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Penutupan Verifikasi
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_akhir_verifikasi" required="" aria-required="true" aria-invalid="true" value="{{$tgl_akhir_verifikasi}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Pembukaan Voucher
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_awal_voucher" required="" aria-required="true" aria-invalid="true" value="{{$tgl_awal_voucher}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Penutupan Voucher
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_akhir_voucher" required="" aria-required="true" aria-invalid="true" value="{{$tgl_akhir_voucher}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal Penetapan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_penetapan" required="" aria-required="true" aria-invalid="true" value="{{$tgl_penetapan}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal Pengumuman
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker-time form-control" name="tgl_pengumuman" required="" aria-required="true" aria-invalid="true" value="{{$tgl_pengumuman}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Jenis Online
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="is_pendaftaran_online">
                                    @if($data_penerimaan->is_pendaftaran_online == 0)
                                        <option value="0" selected >Manual Voucher</option>
                                    @else
                                        <option value="0">Manual Voucher</option>
                                    @endif
                                    @if($data_penerimaan->is_pendaftaran_online == 1)
                                        <option value="1" selected >Pendaftaran Online</option>
                                    @else
                                        <option value="1">Pendaftaran Online</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Verifikasi Berkas
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="is_verifikasi">
                                    @if($data_penerimaan->is_verifikasi == 0)
                                        <option value="0" selected >Tidak</option>
                                    @else
                                        <option value="0">Tidak</option>
                                    @endif
                                    @if($data_penerimaan->is_verifikasi == 1)
                                        <option value="1" selected >Ya</option>
                                    @else
                                        <option value="1">Ya</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Bayar Sebelum Isi Form
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="is_bayar_voucher">
                                    @if($data_penerimaan->is_bayar_voucher == 0)
                                        <option value="0" selected >Tidak</option>
                                    @else
                                        <option value="0">Tidak</option>
                                    @endif
                                    @if($data_penerimaan->is_bayar_voucher == 1)
                                        <option value="1" selected >Ya</option>
                                    @else
                                        <option value="1">Ya</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nomor Rekening Pembayaran
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nomor_rekening_transfer" required="" aria-required="true" aria-invalid="true" value="{{$data_penerimaan->nomor_rekening_transfer}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Jenis Penerimaan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="jenis_penerimaan">
                                    @if($data_penerimaan->jenis_penerimaan == 1)
                                        <option value="1" selected >PPDB Siswa Baru</option>
                                    @else
                                        <option value="1">PPDB Siswa Baru</option>
                                    @endif
                                    @if($data_penerimaan->jenis_penerimaan == 2)
                                        <option value="2" selected >Penerimaan Siswa Lama</option>
                                    @else
                                        <option value="2">Penerimaan Siswa Lama</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Status Aktif
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="is_aktif">
                                    @if($data_penerimaan->is_aktif == 0)
                                        <option value="0" selected >Tidak Aktif</option>
                                    @else
                                        <option value="0">Tidak Aktif</option>
                                    @endif
                                    @if($data_penerimaan->is_aktif == 1)
                                        <option value="1" selected >Aktif</option>
                                    @else
                                        <option value="1">Aktif</option>
                                    @endif
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
    $('.datepicker-time').bootstrapMaterialDatePicker({
        format: 'DD MMMM YYYY HH:mm:00',
        //lang : 'id',
        clearButton: true,
        weekStart: 1,
        time: true
    });
});
</script>