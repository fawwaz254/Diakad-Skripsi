    <div class="container-fluid">
        <div class="block-header">
            <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#data-kesiswaan/home-visit')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            Edit Data Home Visit
                        </h2>
                    </div>
                    <div class="body">
                        <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-home-visit/edit/'.$homeVisit->id_home_visit)}}">
                            {{csrf_field()}}
                            <h2 class="card-inside-title">
                                Nama Siswa
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="nm_siswa" aria-required="true"
                                    aria-invalid="true" readonly="" value="{{$homeVisit->nm_siswa}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                NIS Siswa
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="nis_siswa" aria-required="true"
                                    aria-invalid="true" readonly="" value="{{$homeVisit->nis_siswa}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                NISN Siswa
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="nisn_siswa" aria-required="true"
                                    aria-invalid="true" readonly="" value="{{$homeVisit->nisn_siswa}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Nama Wali Kelas
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="nm_wali_kelas" aria-required="true"
                                    aria-invalid="true" readonly="" value="{{$homeVisit->nm_wali_kelas}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Semester
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="semester" aria-required="true"
                                    aria-invalid="true" readonly="" value="{{$homeVisit->tahun_ajaran}} {{$homeVisit->nm_semester}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Nomor HP Wali Murid
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="nomor_hp_wali_murid" aria-required="true"
                                    aria-invalid="true" readonly="" value="{{$homeVisit->nomor_hp_wali_murid}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Alamat Wali Murid
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="alamat_wali_murid" aria-required="true"
                                    aria-invalid="true" readonly="" value="{{$homeVisit->alamat_wali_murid}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Rangkuman Home Visit
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="rangkuman_home_visit" aria-required="true"
                                    aria-invalid="true" readonly="" value="{{$homeVisit->rangkuman_home_visit}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Tanggal Home Visit
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="tgl_home_visit" aria-required="true"
                                    aria-invalid="true" readonly="" value="{{$tgl_home_visit}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Berkas Lengkap?
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="is_berkas_lengkap">
                                        <option value="0" @if($homeVisit->is_berkas_lengkap == 0) selected @endif>Belum Lengkap</option>
                                        <option value="1" @if($homeVisit->is_berkas_lengkap == 1) selected @endif>Sudah Lengkap</option>
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