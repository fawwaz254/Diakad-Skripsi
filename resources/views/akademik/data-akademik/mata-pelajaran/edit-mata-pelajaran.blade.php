    <div class="container-fluid">
        <div class="block-header">
            <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#data-akademik/mata-pelajaran')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header bg-light-green">
                        <h2>
                            EDIT KURIKULUM
                        </h2>
                    </div>
                    <div class="body">
                        <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-mata-pelajaran/edit/'.$data_mata_pelajaran->id_mata_pelajaran)}}">
                            {{csrf_field()}}
                            <h2 class="card-inside-title">
                                Jurusan
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="id_jurusan">
                                        @foreach($data_jurusan as $data)
                                        @if($data->id_jurusan == $data_mata_pelajaran->id_jurusan)
                                        <option value="{{$data->id_jurusan}}" selected>{{$data->nm_jurusan}}</option>
                                        @else
                                        <option value="{{$data->id_jurusan}}">{{$data->nm_jurusan}}</option>
                                        @endif
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
                                        aria-invalid="true" value="{{$data_mata_pelajaran->kd_mata_pelajaran}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Nama Mapel
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="nm_mata_pelajaran" required="" aria-required="true"
                                        aria-invalid="true" value="{{$data_mata_pelajaran->nm_mata_pelajaran}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Jenis Mapel
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="id_jenis_mata_pelajaran">
                                        @foreach($jenis_mapel as $data)
                                            @if($data->id_jenis_mata_pelajaran == $data_mata_pelajaran->id_jenis_mata_pelajaran)
                                                <option value="{{$data->id_jenis_mata_pelajaran}}" selected="">{{$data->nm_jenis_mata_pelajaran}}</option>
                                            @else
                                                <option value="{{$data->id_jenis_mata_pelajaran}}">{{$data->nm_jenis_mata_pelajaran}}</option>
                                            @endif
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
                                        aria-invalid="true" value="{{$data_mata_pelajaran->nm_mata_pelajaran_en}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Jam KBM
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" class="form-control" name="kredit_semester" required="" aria-required="true"
                                        aria-invalid="true" value="{{$data_mata_pelajaran->kredit_semester}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Jam Tatap Muka
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" class="form-control" name="kredit_tatap_muka" required="" aria-required="true"
                                        aria-invalid="true" value="{{$data_mata_pelajaran->kredit_tatap_muka}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Jam Praktikum
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" class="form-control" name="kredit_praktikum" required="" aria-required="true"
                                        aria-invalid="true" value="{{$data_mata_pelajaran->kredit_praktikum}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Jam Tutor
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" class="form-control" name="kredit_tutor" required="" aria-required="true" aria-invalid="true"
                                        value="{{$data_mata_pelajaran->kredit_tutor}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Jam Prak. Lapangan
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" class="form-control" name="kredit_prak_lapangan" required="" aria-required="true"
                                        aria-invalid="true" value="{{$data_mata_pelajaran->kredit_prak_lapangan}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Jam Simulasi
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" class="form-control" name="kredit_simulasi" required="" aria-required="true"
                                        aria-invalid="true" value="{{$data_mata_pelajaran->kredit_simulasi}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Tingkat Semester
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" class="form-control" name="tingkat_semester" required="" aria-required="true"
                                        aria-invalid="true" value="{{$data_mata_pelajaran->tingkat_semester}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Nilai KKM
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" class="form-control" name="nilai_kkm" required="" aria-required="true" aria-invalid="true"
                                        value="{{$data_mata_pelajaran->nilai_kkm}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Ada SAP?
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    @if($data_mata_pelajaran->ada_sap == 1)
                                    <input class="with-gap radio-col-light-green form-control validate" type="radio" name="ada_sap" value="1"
                                        id="sap_1" required="required" data-error="Error msg here" checked>
                                    <label for="sap_1">Ya</label>
                                    <input class="with-gap radio-col-light-green form-control validate" type="radio" name="ada_sap" value="0"
                                        id="sap_0" required="required" data-error="Error msg here">
                                    <label for="sap_0">Tidak</label>
                                    @else
                                    <input class="with-gap radio-col-light-green form-control validate" type="radio" name="ada_sap" value="1"
                                        id="sap_1" required="required" data-error="Error msg here">
                                    <label for="sap_1">Ya</label>
                                    <input class="with-gap radio-col-light-green form-control validate" type="radio" name="ada_sap" value="0"
                                        id="sap_0" required="required" data-error="Error msg here" checked>
                                    <label for="sap_0">Tidak</label>
                                    @endif
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Ada Silabus?
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    @if($data_mata_pelajaran->ada_silabus == 1)
                                    <input class="with-gap radio-col-light-green form-control validate" type="radio" name="ada_silabus" value="1"
                                        id="silabus_1" required="required" data-error="Error msg here" checked>
                                    <label for="silabus_1">Ya</label>
                                    <input class="with-gap radio-col-light-green form-control validate" type="radio" name="ada_silabus" value="0"
                                        id="silabus_0" required="required" data-error="Error msg here">
                                    <label for="silabus_0">Tidak</label>
                                    @else
                                    <input class="with-gap radio-col-light-green form-control validate" type="radio" name="ada_silabus" value="1"
                                        id="silabus_1" required="required" data-error="Error msg here">
                                    <label for="silabus_1">Ya</label>
                                    <input class="with-gap radio-col-light-green form-control validate" type="radio" name="ada_silabus" value="0"
                                        id="silabus_0" required="required" data-error="Error msg here" checked>
                                    <label for="silabus_0">Tidak</label>
                                    @endif
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Ada Bahan Ajar?
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    @if($data_mata_pelajaran->ada_bahan_ajar == 1)
                                    <input class="with-gap radio-col-light-green form-control validate" type="radio" name="ada_bahan_ajar"
                                        value="1" id="bahan_ajar_1" required="required" data-error="Error msg here" checked>
                                    <label for="bahan_ajar_1">Ya</label>
                                    <input class="with-gap radio-col-light-green form-control validate" type="radio" name="ada_bahan_ajar"
                                        value="0" id="bahan_ajar_0" required="required" data-error="Error msg here">
                                    <label for="bahan_ajar_0">Tidak</label>
                                    @else
                                    <input class="with-gap radio-col-light-green form-control validate" type="radio" name="ada_bahan_ajar"
                                        value="1" id="bahan_ajar_1" required="required" data-error="Error msg here">
                                    <label for="bahan_ajar_1">Ya</label>
                                    <input class="with-gap radio-col-light-green form-control validate" type="radio" name="ada_bahan_ajar"
                                        value="0" id="bahan_ajar_0" required="required" data-error="Error msg here" checked>
                                    <label for="bahan_ajar_0">Tidak</label>
                                    @endif
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Ada Diktat?
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    @if($data_mata_pelajaran->ada_diktat == 1)
                                    <input class="with-gap radio-col-light-green form-control validate" type="radio" name="ada_diktat" value="1"
                                        id="diktat_1" required="required" data-error="Error msg here" checked>
                                    <label for="diktat_1">Ya</label>
                                    <input class="with-gap radio-col-light-green form-control validate" type="radio" name="ada_diktat" value="0"
                                        id="diktat_0" required="required" data-error="Error msg here">
                                    <label for="diktat_0">Tidak</label>
                                    @else
                                    <input class="with-gap radio-col-light-green form-control validate" type="radio" name="ada_diktat" value="1"
                                        id="diktat_1" required="required" data-error="Error msg here">
                                    <label for="diktat_1">Ya</label>
                                    <input class="with-gap radio-col-light-green form-control validate" type="radio" name="ada_diktat" value="0"
                                        id="diktat_0" required="required" data-error="Error msg here" checked>
                                    <label for="diktat_0">Tidak</label>
                                    @endif
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
    <script>
    $(function(){    
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'DD MMMM YYYY',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: false
        });
    });
    </script>
