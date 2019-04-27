<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#data-akademik/kegiatan')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-green">
                    <h2>
                        EDIT KEGIATAN
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-kegiatan/edit/'.$data_kegiatan->id_kegiatan)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Nama Kegiatan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_kegiatan" required="" aria-required="true" aria-invalid="true"
                                    value="{{$data_kegiatan->nm_kegiatan}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kode Kegiatan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="kode_kegiatan">
                                    @foreach($data_kode_kegiatan as $data)
                                    @if($data->kode_kegiatan == $data_kegiatan->kode_kegiatan)
                                    <option value="{{$data->kode_kegiatan}}" selected>{{$data->kode_kegiatan}}</option>
                                    @else
                                    <option value="{{$data->kode_kegiatan}}">{{$data->kode_kegiatan}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Deskripsi
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="deskripsi_kegiatan" required="" aria-required="true"
                                    aria-invalid="true" value="{{$data_kegiatan->deskripsi_kegiatan}}">
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