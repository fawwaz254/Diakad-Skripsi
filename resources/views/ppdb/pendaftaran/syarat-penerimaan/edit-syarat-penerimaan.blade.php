<style>
    .card .card-inside-title {
        margin-top: 10px;
        margin-bottom: 10px;
    }
</style>
<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#pendaftaran/syarat-penerimaan/'.$penerimaan->id_penerimaan)}}"><i class="material-icons">keyboard_backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    <div class="header">
                        <h2>SYARAT PENERIMAAN - EDIT SYARAT PENERIMAAN {{strtoupper($request->type)}}</h2>
                    </div>

                    <div class="body">
                        <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/syarat-penerimaan/'.$penerimaan->id_penerimaan.'/edit/'.$penerimaan_syarat->id_penerimaan_syarat)}}">
                            {{csrf_field()}}
                            <input name="id_penerimaan" type="hidden" value="{{$penerimaan->id_penerimaan}}">
                            <input name="type" type="hidden" value="{{$request->type}}">
                            <h2 class="card-inside-title">
                                Penerimaan
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="penerimaan"  aria-required="true" aria-invalid="true" value="{{$penerimaan->nm_penerimaan}}" disabled>
                                </div>
                            </div>
                            @if($request->type == "khusus")
                            <h2 class="card-inside-title">
                                Jurusan
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="id_jurusan" required="">
                                        @foreach($jurusan as $jur)
                                            <option value="{{$jur->id_jurusan}}" {{($penerimaan_syarat->id_jurusan==$jur->id_jurusan?"selected":"")}} >{{$jur->nm_jurusan}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif
                            <h2 class="card-inside-title">
                                Syarat
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="nm_penerimaan_syarat"  aria-required="true" aria-invalid="true" value="{{$penerimaan_syarat->nm_penerimaan_syarat}}" required="true">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Keterangan
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <textarea name="keterangan_penerimaan_syarat" cols="30" rows="5" class="form-control" required="true" aria-required="true">{{$penerimaan_syarat->keterangan_penerimaan_syarat}}</textarea>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Status Wajib
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="is_wajib" required="">
                                        @if($penerimaan_syarat->is_wajib == "0")
                                        <option value="0" selected>Tidak Wajib</option>
                                        @else
                                        <option value="0">Tidak Wajib</option>
                                        @endif

                                        @if($penerimaan_syarat->is_wajib == "1")
                                        <option value="1" selected>Wajib</option>
                                        @else
                                        <option value="1">Wajib</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Upload File
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="is_upload_file" >
                                        @if($penerimaan_syarat->is_upload_file == "0")
                                        <option value="0" selected>Tidak</option>
                                        @else 
                                        <option value="0">Tidak</option>
                                        @endif

                                        @if($penerimaan_syarat->is_upload_file == "1")
                                        <option value="1" selected>Ya</option>
                                        @else
                                        <option value="1">Ya</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Urutan
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" min="0" class="form-control" name="urutan"  aria-required="true" aria-invalid="true" value="{{$penerimaan_syarat->urutan}}" required="true">
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <a class="btn bg-blue btn-block waves-effect target-link" href="{{url(Request::segment(1).'#pendaftaran/syarat-penerimaan/'.$penerimaan->id_penerimaan)}}"><i class="material-icons">cancel</i><span>Cancel</span></a>
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