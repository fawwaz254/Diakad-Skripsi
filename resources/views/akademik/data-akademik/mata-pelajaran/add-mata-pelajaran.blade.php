    <div class="container-fluid">
        <div class="block-header">
            <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#data-akademik/mata-pelajaran')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            TAMBAH MATA PELAJARAN
                        </h2>
                    </div>
                    <div class="body">
                        <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-mata-pelajaran/add/'.$id_mata_pelajaran)}}">
                            {{csrf_field()}}
                            <h2 class="card-inside-title">
                                Jurusan
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="id_jurusan">
                                        @foreach($data_jurusan as $data)
                                        <option value="{{$data->id_jurusan}}">{{$data->nm_jurusan}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Kode Mapel
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="kd_mata_pelajaran" required="" aria-required="true"
                                        aria-invalid="true">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Nama Mapel
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="nm_mata_pelajaran" required="" aria-required="true"
                                        aria-invalid="true">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Jenis Mapel
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="id_jenis_mata_pelajaran">
                                        @foreach($jenis_mapel as $data)
                                            <option value="{{$data->id_jenis_mata_pelajaran}}">{{$data->nm_jenis_mata_pelajaran}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Nama Mapel English
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="nm_mata_pelajaran_en" required="" aria-required="true"
                                        aria-invalid="true">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Jam KBM
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" class="form-control" name="kredit_semester" required="" aria-required="true"
                                        aria-invalid="true">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Jam Tatap Muka
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" class="form-control" name="kredit_tatap_muka" required="" aria-required="true"
                                        aria-invalid="true" value="0">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Jam Praktikum
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" class="form-control" name="kredit_praktikum" required="" aria-required="true"
                                        aria-invalid="true" value="0">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Jam Tutor
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" class="form-control" name="kredit_tutor" required="" aria-required="true" aria-invalid="true"
                                        value="0">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Jam Prak. Lapangan
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" class="form-control" name="kredit_prak_lapangan" required="" aria-required="true"
                                        aria-invalid="true" value="0">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Jam Simulasi
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" class="form-control" name="kredit_simulasi" required="" aria-required="true"
                                        aria-invalid="true" value="0">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Tingkat Semester
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" class="form-control" name="tingkat_semester" required="" aria-required="true"
                                        aria-invalid="true">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Nilai KKM
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" class="form-control" name="nilai_kkm" required="" aria-required="true" aria-invalid="true">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Ada SAP?
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input class="with-gap radio-col-light-green form-control validate" type="radio" name="ada_sap" value="1"
                                        id="sap_1" required="required" data-error="Error msg here" checked>
                                    <label for="sap_1">Ya</label>
                                    <input class="with-gap radio-col-light-green form-control validate" type="radio" name="ada_sap" value="0"
                                        id="sap_0" required="required" data-error="Error msg here">
                                    <label for="sap_0">Tidak</label>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Ada Silabus?
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input class="with-gap radio-col-light-green form-control validate" type="radio" name="ada_silabus" value="1"
                                        id="silabus_1" required="required" data-error="Error msg here" checked>
                                    <label for="silabus_1">Ya</label>
                                    <input class="with-gap radio-col-light-green form-control validate" type="radio" name="ada_silabus" value="0"
                                        id="silabus_0" required="required" data-error="Error msg here">
                                    <label for="silabus_0">Tidak</label>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Ada Bahan Ajar?
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input class="with-gap radio-col-light-green form-control validate" type="radio" name="ada_bahan_ajar"
                                        value="1" id="bahan_ajar_1" required="required" data-error="Error msg here" checked>
                                    <label for="bahan_ajar_1">Ya</label>
                                    <input class="with-gap radio-col-light-green form-control validate" type="radio" name="ada_bahan_ajar"
                                        value="0" id="bahan_ajar_0" required="required" data-error="Error msg here">
                                    <label for="bahan_ajar_0">Tidak</label>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Ada Diktat?
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input class="with-gap radio-col-light-green form-control validate" type="radio" name="ada_diktat" value="1"
                                        id="diktat_1" required="required" data-error="Error msg here" checked>
                                    <label for="diktat_1">Ya</label>
                                    <input class="with-gap radio-col-light-green form-control validate" type="radio" name="ada_diktat" value="0"
                                        id="diktat_0" required="required" data-error="Error msg here">
                                    <label for="diktat_0">Tidak</label>
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
