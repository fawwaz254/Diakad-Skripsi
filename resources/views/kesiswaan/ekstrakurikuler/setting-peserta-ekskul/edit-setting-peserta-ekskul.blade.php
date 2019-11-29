<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#ekstrakurikuler/setting-peserta-ekskul/view-ekskul/'.$data_peserta->id_ekskul)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Edit Siswa {{$data_peserta->nm_pengguna}} di {{$data_peserta->nm_ekskul}}
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-setting-peserta-ekskul/edit/'.$data_peserta->id_peserta_ekskul_set)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Status Aktif
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="is_aktif">
                                    <option value="1" @if($data_peserta->is_aktif == "1") selected @endif>Aktif</option>
                                    <option value="0" @if($data_peserta->is_aktif == "0") selected @endif>Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tampil ke Siswa
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="is_tampil">
                                    <option value="1" @if($data_peserta->is_tampil == "1") selected @endif>Tampil</option>
                                    <option value="0" @if($data_peserta->is_tampil == "0") selected @endif>Tidak Tampil</option>
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