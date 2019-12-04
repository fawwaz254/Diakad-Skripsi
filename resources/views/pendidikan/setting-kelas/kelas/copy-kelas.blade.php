<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#setting-kelas/kelas')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        COPY SEKRETARIS, RUANGAN, WALI KELAS
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-kelas/copy/0')}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Semester Copy
                            <small><strong>Semester Asal Data</strong></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_semester_copy" required >
                                    <option value="" disabled selected >-- Pilih Semester --</option>
                                    @foreach($data_semester as $data)
                                        <option value="{{$data->id_semester}}">{{$data->tahun_ajaran}} ({{$data->nm_semester}})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Semester Paste
                            <small><strong>Semester Tujuan Data</strong></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_semester_paste" required >
                                    <option value="" disabled selected >-- Pilih Semester --</option>
                                    @foreach($data_semester as $data)
                                        <option value="{{$data->id_semester}}">{{$data->tahun_ajaran}} ({{$data->nm_semester}})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Elemen Copy
                            <small><strong>Pilihan Data yang Akan di-Copy</strong></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="checkbox" id="checkbox-sekretaris" name="sekretaris" class="filled-in" value="1">
                                <label for="checkbox-sekretaris">Sekretaris Kelas</label> <br>
                                <input type="checkbox" id="checkbox-is-aktif-sekretaris" name="is_aktif_sekretaris" class="filled-in" value="11">
                                <label for="checkbox-is-aktif-sekretaris">Data Aktif (Default Aktif Untuk Data Baru)</label> <br> <br> <br>
                                <input type="checkbox" id="checkbox-ruangan" name="ruangan" class="filled-in" value="2">
                                <label for="checkbox-ruangan">Ruangan Kelas</label> <br>
                                <input type="checkbox" id="checkbox-is-aktif-ruangan" name="is_aktif_ruangan" class="filled-in" value="22">
                                <label for="checkbox-is-aktif-ruangan">Data Aktif (Default Aktif Untuk Data Baru)</label> <br> <br> <br>
                                <input type="checkbox" id="checkbox-wali-kelas" name="wali_kelas" class="filled-in" value="3">
                                <label for="checkbox-wali-kelas">Wali Kelas</label> <br>
                                <input type="checkbox" id="checkbox-is-aktif-wali-kelas" name="is_aktif_wali_kelas" class="filled-in" value="33">
                                <label for="checkbox-is-aktif-wali-kelas">Data Aktif (Default Aktif Untuk Data Baru)</label>
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