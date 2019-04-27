<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#data-akademik/status-siswa')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-green">
                    <h2>
                        TAMBAH STATUS SISWA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-status-siswa/add/'.$id_status_pengguna)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Nama Status
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_status_pengguna" required="" aria-required="true"
                                    aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Status Aktif
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="aktif_status_pengguna">
                                    <option value="1">Aktif</option>
                                    <option value="0">Keluar/Non-Aktif</option>
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kode Status
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="kode_status_pengguna">
                                    <option value="AKTIF">AKTIF</option>
                                    <option value="CUTI">CUTI/NON-AKTIF-SEMENTARA</option>
                                    <option value="CALON_LULUS">CALON_LULUS</option>
                                    <option value="LULUS">LULUS</option>
                                    <option value="MUTASI">MUTASI</option>
                                    <option value="DIKELUARKAN">DIKELUARKAN</option>
                                    <option value="MENGUNDURKAN_DIRI">MENGUNDURKAN_DIRI</option>
                                    <option value="PUTUS_SEKOLAH">PUTUS_SEKOLAH</option>
                                    <option value="WAFAT">WAFAT</option>
                                    <option value="HILANG">HILANG</option>
                                    <option value="LAINNYA">LAINNYA</option>
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