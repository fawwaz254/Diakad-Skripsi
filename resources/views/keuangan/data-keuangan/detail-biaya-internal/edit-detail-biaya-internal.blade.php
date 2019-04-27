<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#data-keuangan/detail-biaya-internal')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-amber">
                    <h2>
                        EDIT DETAIL BIAYA INTERNAL
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-detail-biaya-internal/edit/'.$data_detail_biaya_internal->id_detail_biaya_internal)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Nama Biaya Internal
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_kelompok_biaya_internal">
                                    <option value="" disabled selected >-- Pilih Nama Biaya Internal --</option>
                                    @foreach($data_biaya_internal as $data)
                                        @if($data->id_kelompok_biaya_internal == $data_detail_biaya_internal->id_kelompok_biaya_internal)
                                            <option value="{{$data->id_kelompok_biaya_internal}}" selected >{{$data->nm_kelompok_biaya_internal}} ({{$data->nm_biaya}})</option>
                                        @else
                                            <option value="{{$data->id_kelompok_biaya_internal}}">{{$data->nm_kelompok_biaya_internal}} ({{$data->nm_biaya}})</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Detail Biaya Internal
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_detail_biaya_internal" required="" aria-required="true" aria-invalid="true" value="{{$data_detail_biaya_internal->nm_detail_biaya_internal}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Besar Biaya
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="besar_biaya" required="" aria-required="true" aria-invalid="true" value="{{$data_detail_biaya_internal->besar_biaya}}">
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