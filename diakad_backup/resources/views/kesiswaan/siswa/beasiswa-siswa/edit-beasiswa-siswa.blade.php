    <div class="container-fluid">
        <div class="block-header">
            <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#data-kesiswaan/beasiswa-siswa')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            EDIT BEASISWA SISWA
                        </h2>
                    </div>
                    <div class="body">
                        <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-beasiswa-siswa/edit/'.$beasiswa->id_beasiswa_siswa)}}">
                            {{csrf_field()}}
                            <h2 class="card-inside-title">
                                Nama Siswa
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="nm_pengguna" required="" aria-required="true"
                                    aria-invalid="true" readonly="" value="{{$beasiswa->nm_pengguna}}">
                                    <input type="hidden" class="form-control" name="id_siswa" required="" aria-required="true"
                                    aria-invalid="true" value="{{$beasiswa->id_siswa}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Jenis Beasiswa
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="jenis_beasiswa_siswa">
                                        <option value="">-- Pilih Jenis Beasiswa --</option>
                                        <option value="1" @if($beasiswa->jenis_beasiswa_siswa == 1) selected @endif>Anak Berprestasi</option>
                                        <option value="2" @if($beasiswa->jenis_beasiswa_siswa == 2) selected @endif>Anak Miskin</option>
                                        <option value="3" @if($beasiswa->jenis_beasiswa_siswa == 3) selected @endif>Pendidikan</option>
                                        <option value="4" @if($beasiswa->jenis_beasiswa_siswa == 4) selected @endif>Unggulan</option>
                                        <option value="99" @if($beasiswa->jenis_beasiswa_siswa == 99) selected @endif>Lain-Lain</option>
                                    </select>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Tahun Mulai Beasiswa
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" class="form-control" name="tahun_mulai_beasiswa_siswa" required="" aria-required="true"
                                    aria-invalid="true" value="{{$beasiswa->tahun_mulai_beasiswa_siswa}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Tahun Selesai Beasiswa <small><b>Jika waktu beasiswa hanya satu tahun atau kurang. Tahun Selesai diisi sama dengan tahun mulai</b></small>
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" class="form-control" name="tahun_selesai_beasiswa_siswa" required="" aria-required="true"
                                    aria-invalid="true" value="{{$beasiswa->tahun_selesai_beasiswa_siswa}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Keterangan
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="keterangan" class="form-control" name="keterangan_beasiswa_siswa" aria-required="true"
                                    aria-invalid="true" value="{{$beasiswa->keterangan_beasiswa_siswa}}">
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