<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#data-keuangan/biaya-sekolah/detail-biaya/detail-biaya-internal/'.$detail_biaya->id_detail_biaya)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        EDIT DETAIL BIAYA INTERNAL
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/biaya-sekolah/detail-biaya/detail-biaya-internal/action-detail-biaya-internal/'.$detail_biaya->id_detail_biaya.'/edit/'.$data_detail_biaya_internal->id_detail_biaya_internal)}}">
                    {{csrf_field()}}

                        <div class="row">

                            <div class="col-md-6">
                                <h2 class="card-inside-title">
                                    Nama Detail Biaya Internal
                                </h2>
                                <input type="text" class="form-control" value="{{$data_detail_biaya_internal->nm_detail_biaya_internal}}" name="nm_detail_biaya_internal" required="" aria-required="true" aria-invalid="true">   
                            </div>

                            <div class="col-md-6">
                                <h2 class="card-inside-title">
                                    Besar Biaya
                                </h2>
                                <input type="number" class="form-control" value="{{$data_detail_biaya_internal->besar_biaya}}" name="besar_biaya" required="" aria-required="true" aria-invalid="true">
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
