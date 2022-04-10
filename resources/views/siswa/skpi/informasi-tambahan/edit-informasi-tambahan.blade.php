<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{url(Request::segment(1).'#skpi/informasi_tambahan')}}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        TAMBAH INFORMASI TAMBAHAN
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="post"
                        action="{{url(Request::segment(1).'/'.Request::segment(2).'/informasi_tambahan/action/edit/'.$informasi_tambahan->id_informasi_tambahan)}}"
                        enctype="multipart/form-data">
                        {{csrf_field()}}

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Jenis Informasi
                                </h2>

                                <select class="form-control show-tick" name="jenis_informasi_tambahan" required="">
                                    <option value="ekstrakurikuler"{{$informasi_tambahan->jenis_informasi_tambahan == 'ekstrakurikuler' ? 'selected' : ''}}>Ekstrakurikuler</option>
                                    <option value="produk_lomba"{{$informasi_tambahan->jenis_informasi_tambahan == 'produk_lomba' ? 'selected' : ''}}>Produk Lomba</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <h2 class="card-inside-title">
                                    Nama
                                </h2>
                                <input type="text" class="form-control" name="nm_informasi_tambahan" required=""
                                    aria-required="true" aria-invalid="true" value="{{ $informasi_tambahan->nm_informasi_tambahan }}">
                            </div>

                            <div class="col-md-6">
                                <h2 class="card-inside-title">
                                    Name (tulis dalam bahasa inggris)
                                </h2>
                                <input type="text" class="form-control" name="nm_informasi_tambahan_eng" required=""
                                    aria-required="true" aria-invalid="true" value="{{ $informasi_tambahan->nm_informasi_tambahan_eng }}">
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                        class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('scriptjs')



