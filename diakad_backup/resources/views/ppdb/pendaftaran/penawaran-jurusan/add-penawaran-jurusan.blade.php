<style>
    .card .card-inside-title {
        margin-top: 10px;
        margin-bottom: 10px;
    }
</style>
<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#pendaftaran/penawaran-jurusan/edit-penawaran-jurusan/'.$penerimaan->id_penerimaan)}}"><i class="material-icons">keyboard_backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    <div class="header">
                        <h2>PENAWARAN JURUSAN - TAMBAH JURUSAN</h2>
                    </div>

                    <div class="body">
                        <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/penawaran-jurusan/edit-penawaran-jurusan/'.$penerimaan->id_penerimaan.'/add')}}">
                            {{csrf_field()}}
                            <input name="id_penerimaan" type="hidden" value="{{$penerimaan->id_penerimaan}}">
                            <h2 class="card-inside-title">
                                Penerimaan
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="penerimaan"  aria-required="true" aria-invalid="true" value="{{$penerimaan->nm_penerimaan}}" disabled>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Semester
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="semester"  aria-required="true" aria-invalid="true" value="{{$penerimaan->nm_semester_penerimaan . ', ' . $penerimaan->tahun_penerimaan}}" disabled>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Jurusan
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="id_jurusan" required="">
                                        <option value="">-</option>
                                        @foreach($jurusan as $jur)
                                            <option value="{{$jur->id_jurusan}}">{{$jur->nm_jurusan}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <a class="btn bg-blue btn-block waves-effect target-link" href="{{url(Request::segment(1).'#pendaftaran/penawaran-jurusan/edit-penawaran-jurusan/'.$penerimaan->id_penerimaan)}}"><i class="material-icons">cancel</i><span>Cancel</span></a>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
