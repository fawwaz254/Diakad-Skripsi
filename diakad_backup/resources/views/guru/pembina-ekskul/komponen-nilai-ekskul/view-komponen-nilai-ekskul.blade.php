<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        PILIH EKSKUL
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-view-komponen-nilai')}}">
                            {{csrf_field()}}
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label for="id_semester">Ekstrakurikuler</label>
                                        <select class="form-control show-tick" name="id_ekskul" required>
                                            <option value="" selected disabled>-- Pilih Ekstrakurikuler --</option>
                                            @foreach($data_ekskul as $data)
                                                <option value="{{$data->id_ekskul}}">{{$data->ekskul->nm_ekskul}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clear-fix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label for="id_semester">Semester</label>
                                        <select name="id_semester" id="id_semester" required class="form-control show-tick">
                                            @foreach($data_semester as $semester)
                                                <option value="{{$semester->id_semester}}" 
                                                @if($semester->is_aktif_semester == 1) selected @endif>
                                                    {{$semester->tahun_ajaran}}
                                                    {{$semester->nm_semester}} 
                                                @if($semester->is_aktif_semester == 1)
                                                    (Aktif)
                                                @endif
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
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