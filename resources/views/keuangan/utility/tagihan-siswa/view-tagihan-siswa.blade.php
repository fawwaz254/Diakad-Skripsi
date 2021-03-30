<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        GENERATE TAGIHAN SISWA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-view-tagihan-siswa')}}">
                        {{csrf_field()}}
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Tahun Masuk Siswa
                                </h2>
                                <select class="form-control show-tick" name="thn_masuk_siswa" >
                                  <option value="" disabled selected >-- Pilih Tahun Masuk --</option>
                                    @foreach($data_thn_masuk_siswa as $data)
                                        <option value="{{$data->thn_masuk_siswa}}">{{$data->thn_masuk_siswa}}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Semester
                                </h2>
                                <select class="form-control show-tick" name="id_semester">
                                  <option value="" disabled selected >-- Pilih Semester --</option>
                                    @foreach($data_semester as $data)
                                        @if($data->is_aktif_semester == 1)
                                            <option value="{{$data->id_semester}}" selected>{{$data->tahun_ajaran}} {{$data->nm_semester}} (Aktif)</option>
                                        @else
                                            <option value="{{$data->id_semester}}">{{$data->tahun_ajaran}} {{$data->nm_semester}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Kelompok Biaya
                                </h2>
                                <select class="form-control show-tick" name="id_kelompok_biaya" >
                                  <option value="0" >-- Semua --</option>
                                    @foreach($data_kelompok_biaya as $data)
                                        @if($data->status_kelompok_biaya == 1)
                                            <option value="{{$data->id_kelompok_biaya}}">{{$data->nm_kelompok_biaya}} (Reguler)</option>
                                        @else
                                            <option value="{{$data->id_kelompok_biaya}}">{{$data->nm_kelompok_biaya}} (Khusus)</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Jalur
                                </h2>
                                <select class="form-control show-tick" name="id_jalur" >
                                  <option value="0" >-- Semua --</option>
                                    @foreach($data_jalur as $data)
                                        <option value="{{$data->id_jalur}}">{{$data->nm_jalur}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Insert/Replace Tagihan 
                                    <small>* REPLACE digunakan untuk menghapus Tagihan Lama dan mengganti dengan Tagihan Baru <br>
                                    * UPDATE digunakan untuk memperbarui Tagihan yang BELUM TERBAYAR</small>
                                </h2>
                                <select class="form-control show-tick" name="is_insert_replace" >
                                  <option value="1">Insert Tagihan</option>
                                  <!-- <option value="2">Replace Tagihan</option> -->
                                  <option value="3">Update Tagihan</option>
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Tampilkan</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
