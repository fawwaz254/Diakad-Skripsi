<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#data-sarpras-gedung/gedung')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        EDIT GEDUNG
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-gedung/edit/'.$data_gedung->id_gedung)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Jenis Gedung
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_jenis_gedung">
                                    @foreach($data_jenis_gedung as $data)
                                    @if($data->id_jenis_gedung == $data_gedung->id_jenis_gedung)
                                    <option value="{{$data->id_jenis_gedung}}" selected>{{$data->nm_jenis_gedung}}</option>
                                    @else
                                    <option value="{{$data->id_jenis_gedung}}">{{$data->nm_jenis_gedung}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kode Gedung
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="kode_gedung" required="" aria-required="true" aria-invalid="true"
                                    value="{{$data_gedung->kode_gedung}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Gedung
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_gedung" required="" aria-required="true" aria-invalid="true"
                                    value="{{$data_gedung->nm_gedung}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Lokasi Gedung
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="lokasi_gedung" required="" aria-required="true" aria-invalid="true"
                                    value="{{$data_gedung->lokasi_gedung}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Deskripsi Gedung
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <textarea rows="4" cols="50" class="form-control" name="deskripsi_gedung" required="" aria-required="true"
                                    aria-invalid="true">{{$data_gedung->deskripsi_gedung}}</textarea>
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