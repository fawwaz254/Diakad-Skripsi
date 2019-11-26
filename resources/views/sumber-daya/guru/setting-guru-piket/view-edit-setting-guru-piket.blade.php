    <div class="container-fluid">
       <div class="block-header">
            <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#guru/setting-guru-piket')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header bg-pink">
                        <h2>
                            Edit Guru Piket
                        </h2>
                    </div>
                    <div class="body">
                        <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-setting-guru-piket/edit/'.$guru->id_guru_piket)}}">
                            {{csrf_field()}}
                            <h2 class="card-inside-title">
                                Nama Guru Piket
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="nm_pengguna" required="" aria-required="true"
                                        aria-invalid="true" readonly="" value="{{$guru->nm_pengguna}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                NIP Guru Piket
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                @if(!empty($guru->nip_guru))
                                    <input type="text" class="form-control" name="nip_pengguna" required="" aria-required="true"
                                        aria-invalid="true" readonly="" value="{{$guru->nip_guru}}">
                                @else
                                    <input type="text" class="form-control" name="nip_pengguna" required="" aria-required="true"
                                        aria-invalid="true" readonly="" value="{{$guru->nip_staff}}">
                                @endif
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Status
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="is_aktif">
                                            <option value="2" @if($guru->is_aktif == 2) selected @endif>Non-Aktif</option>
                                            <option value="1" @if($guru->is_aktif == 1) selected @endif>Aktif</option>
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
