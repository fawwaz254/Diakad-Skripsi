    <div class="container-fluid">
        <div class="block-header">
            <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#ekstrakurikuler/setting-pelatih-ekskul/')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header bg-indigo">
                        <h2>
                            EDIT {{$pelatih->nm_pengguna}} di {{$pelatih->nm_ekskul}}
                        </h2>
                    </div>
                    <div class="body">
                        <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-setting-pelatih-ekskul/edit-status/'.$pelatih->id_pelatih_ekskul_set)}}">
                            {{csrf_field()}}
                            <h2 class="card-inside-title">
                                Nama Pelatih
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="nm_pengguna" required="" aria-required="true"
                                    aria-invalid="true" value="{{$pelatih->nm_pengguna}}" readonly="">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Ekstrakurikuler
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="nm_ekskul" required="" aria-required="true"
                                    aria-invalid="true" value="{{$pelatih->nm_ekskul}}" readonly="">
                                </div>
                            </div>
                            
                             <h2 class="card-inside-title">
                                Status
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                    <select class="form-control show-tick" name="is_aktif">
                                        @if($pelatih->is_aktif == "0")
                                        <option value="1">Aktif</option>
                                        <option value="0" selected="">Tidak Aktif</option>
                                        @else
                                        <option value="1" selected="">Aktif</option>
                                        <option value="0">Tidak Aktif</option>
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
