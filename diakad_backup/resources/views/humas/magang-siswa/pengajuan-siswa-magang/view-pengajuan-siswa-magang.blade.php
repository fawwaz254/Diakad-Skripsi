<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        PENGAJUAN SISWA MAGANG
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-view-pengajuan-magang')}}">
                            {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Rekanan Magang<small><b>* Rekanan Magang Yang Akan Diajukan (Wajib Diisi)</b></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_rekanan_magang" required>
                                    @foreach($data_rekanan_magang as $data)
                                      <option value="{{$data->id_rekanan_magang}}">{{$data->nm_rekanan_magang}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Periode Magang <small><b>* Periode Magang Yang Akan Diajukan</b></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_periode_magang">
                                    <option value="0">-- Pilih Periode Magang --</option>
                                    @foreach($data_periode_magang as $data)
                                      <option value="{{$data->id_periode_magang}}">{{$data->nm_magang}} - {{$data->nm_periode_magang}}</option>
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
