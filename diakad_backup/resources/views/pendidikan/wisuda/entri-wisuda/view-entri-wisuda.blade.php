<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-green waves-effect target-link " href="{{url(Request::segment(1).'#'.Request::segment(2).'/import_nomor_ijazah')}}"><i class="material-icons">cloud_upload</i><span>Upload Nomor Ijazah</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        PILIH PERIODE WISUDA DAN KELAS
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-view-entri-wisuda')}}">
                            {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Periode Wisuda <small><b>* Periode Wisuda Yang Akan Dilakukan Entri Data</b></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_periode_wisuda" required="">
                                    <option value="0">-- Pilih Periode Wisuda --</option>
                                    @foreach($data_periode_wisuda as $data)
                                        <option value="{{$data->id_periode_wisuda}}">{{$data->nm_periode_wisuda}} ({{$data->tahun_ajaran}} {{$data->nm_semester}})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_kelas" required="">
                                    <option value="0">-- Pilih Kelas --</option>
                                    @foreach($data_kelas as $data)
                                        <option value="{{$data->id_kelas}}">{{$data->nm_kelas}}</option>
                                    @endforeach
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