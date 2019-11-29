<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#wali-kelas/home-visit')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        EDIT HOME VISIT
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-home-visit/edit/'.$data_home_visit->id_home_visit)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Nama Siswa <small><b>* Pilih Siswa Kelas {{$wali_kelas->nm_kelas}}</b></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_siswa">
                                    <option value="">-- Pilih Siswa --</option>
                                    @foreach($data_siswa as $data)
                                        @if($data->id_siswa == $data_home_visit->id_siswa)
                                            <option value="{{$data->id_siswa}}" selected >{{$data->nm_pengguna}} - {{$data->nis_siswa}}</option>
                                        @else
                                            <option value="{{$data->id_siswa}}">{{$data->nm_pengguna}} - {{$data->nis_siswa}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nomor HP Wali Murid
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nomor_hp_wali_murid" required="" aria-required="true" aria-invalid="true" value="{{$data_home_visit->nomor_hp_wali_murid}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Alamat Wali Murid
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="alamat_wali_murid" required="" aria-required="true" aria-invalid="true" value="{{$data_home_visit->alamat_wali_murid}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Rangkuman Home Visit
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="rangkuman_home_visit" required="" aria-required="true" aria-invalid="true" value="{{$data_home_visit->rangkuman_home_visit}}">
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