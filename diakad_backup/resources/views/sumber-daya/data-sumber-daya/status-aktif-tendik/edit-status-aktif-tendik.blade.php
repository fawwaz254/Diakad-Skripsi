<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#data-sumber-daya/status-aktif-tendik')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        EDIT STATUS AKTIF TENDIK
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-status-aktif-tendik/edit/'.$data_status_pengguna->id_status_pengguna)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Nama Status Aktif Tendik
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_unit_kerja" required="" aria-required="true" aria-invalid="true"
                                    value="{{$data_status_pengguna->nm_status_pengguna}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Status Aktif
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="aktif_status_pengguna">
                                    @if($data_status_pengguna->aktif_status_pengguna == 1)
                                    <option value="1" selected>Aktif</option>
                                    @else
                                    <option value="1">Aktif</option>
                                    @endif
                                    @if($data_status_pengguna->aktif_status_pengguna == 2)
                                    <option value="0" selected>Keluar/Non-Aktif</option>
                                    @else
                                    <option value="0">Keluar/Non-Aktif</option>
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