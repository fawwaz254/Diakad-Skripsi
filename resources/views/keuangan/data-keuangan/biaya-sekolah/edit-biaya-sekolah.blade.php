<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#data-keuangan/biaya-sekolah')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-amber">
                    <h2>
                        EDIT BIAYA SEKOLAH
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-biaya-sekolah/edit/'.$data_biaya_sekolah->id_biaya_sekolah)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Nama Kelompok Biaya
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_kelompok_biaya">
                                    <option value="" disabled selected >-- Pilih Kelompok Biaya --</option>
                                    @foreach($data_kelompok_biaya as $data)
                                        @if($data->id_kelompok_biaya == $data_biaya_sekolah->id_kelompok_biaya)
                                            <option value="{{$data->id_kelompok_biaya}}" selected >{{$data->nm_kelompok_biaya}}</option>
                                        @else
                                            <option value="{{$data->id_kelompok_biaya}}">{{$data->nm_kelompok_biaya}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Semester
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_semester">
                                    <option value="" disabled selected >-- Pilih Semester --</option>
                                    @foreach($data_semester as $data)
                                        @if($data->id_semester == $data_biaya_sekolah->id_semester)
                                            <option value="{{$data->id_semester}}" selected >{{$data->tahun_ajaran}} {{$data->nm_semester}} </option>
                                        @else
                                            <option value="{{$data->id_semester}}" >{{$data->tahun_ajaran}} {{$data->nm_semester}} </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Jalur
                            <small>*Opsional Khusus Jalur Tertentu</small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_Jalur">
                                    <option value="">-- Semua Jalur --</option>
                                    @foreach($data_jalur as $data)
                                        @if($data->id_jalur == $data_biaya_sekolah->id_jalur)
                                            <option value="{{$data->id_jalur}}" selected >{{$data->nm_jalur}}</option>
                                        @else
                                            <option value="{{$data->id_jalur}}">{{$data->nm_jalur}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Besar Biaya
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="besar_biaya_sekolah" required="" aria-required="true" aria-invalid="true" value="{{$data_biaya_sekolah->besar_biaya_sekolah}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Validasi
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="validasi_biaya_sekolah">
                                    @if($data_biaya_sekolah->validasi_biaya_sekolah == 0)
                                        <option value="0" selected >Belum</option>
                                        <option value="1">Sudah</option>
                                    @else
                                        <option value="0">Belum</option>
                                        <option value="1" selected >Sudah</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Keterangan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="keterangan_biaya_sekolah" required="" aria-required="true" aria-invalid="true" value="{{$data_biaya_sekolah->keterangan_biaya_sekolah}}">
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