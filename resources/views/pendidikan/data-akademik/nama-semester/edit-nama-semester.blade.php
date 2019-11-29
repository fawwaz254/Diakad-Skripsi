<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#data-akademik/nama-semester')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        EDIT NAMA SEMESTER
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-nama-semester/edit/'.$data_semester->id_semester)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Tahun Ajaran <small><b>* Contoh: 2015/2016 atau 2016/2017</b></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="tahun_ajaran" required="" aria-required="true" aria-invalid="true"
                                    value="{{$data_semester->tahun_ajaran}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Semester <small><b>* Contoh: Ganjil atau Genap</b></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="nm_semester">
                                    @if($data_semester->nm_semester == "Ganjil")
                                    <option value="Ganjil" selected >Ganjil</option>
                                    <option value="Genap">Genap</option>
                                    @else
                                    <option value="Ganjil">Ganjil</option>
                                    <option value="Genap" selected >Genap</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tahun Akademik
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="thn_akademik_semester" required="" aria-required="true"
                                    aria-invalid="true" value="{{$data_semester->thn_akademik_semester}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kode Semester
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="kode_semester" required="" aria-required="true" aria-invalid="true"
                                    value="{{$data_semester->kode_semester}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Status Aktif
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="is_aktif_semester">
                                    @if($data_semester->is_aktif_semester == 0)
                                    <option value="0" selected>Tidak</option>
                                    <option value="1">Ya</option>
                                    @else
                                    <option value="0">Tidak</option>
                                    <option value="1" selected>Ya</option>
                                    @endif
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