<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        PILIH PERIODE WISUDA ATAU NIS/NAMA SISWA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-view-pengajuan-wisuda')}}">
                            {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Periode Wisuda <small><b>* Periode Wisuda Yang Akan Diajukan</b></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_periode_wisuda">
                                    <option value="0">-- Pilih Periode Wisuda --</option>
                                    @foreach($data_periode_wisuda as $data)
                                        <option value="{{$data->id_periode_wisuda}}">{{$data->nm_periode_wisuda}} ({{$data->tahun_ajaran}} {{$data->nm_semester}})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            NIS atau Nama Siswa <small><b>* Diisi Untuk Pencarian Lebih Spesifik</b></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nis_nama_siswa" aria-invalid="true">
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