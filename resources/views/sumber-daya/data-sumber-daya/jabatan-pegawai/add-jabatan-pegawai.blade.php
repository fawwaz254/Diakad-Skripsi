<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#data-sumber-daya/jabatan-pegawai')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-pink">
                    <h2>
                        TAMBAH JABATAN PEGAWAI
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-jabatan-pegawai/add/'.$id_jabatan_pegawai)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Nama Jabatan Pegawai
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_jabatan_pegawai" required="" aria-required="true"
                                    aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Deskripsi
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="deskripsi_jabatan_pegawai" required="" aria-required="true"
                                    aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tipe Jabatan Pegawai
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="tipe_jabatan_pegawai" required="" aria-required="true"
                                    aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kode Jabatan Pegawai
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="kode_jabatan_pegawai" required="" aria-required="true"
                                    aria-invalid="true">
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