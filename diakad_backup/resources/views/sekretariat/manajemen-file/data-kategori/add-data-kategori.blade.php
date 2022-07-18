    <div class="container-fluid">
        <div class="block-header">
            <h2><a class="btn bg-blue waves-effect target-link"
                    href="{{url(Request::segment(1).'#manajemen-file/data-kategori/')}}"><i
                        class="material-icons">backspace</i><span>Kembali</span></a></h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            TAMBAH DATA KATEGORI
                        </h2>
                    </div>
                    <div class="body">
                        <form id="form-validation" method="POST"
                            action="{{url(Request::segment(1).'/'.Request::segment(2).'/data-kategori/action-data-kategori/add/0')}}">
                            {{csrf_field()}}
                            <h2 class="card-inside-title">
                                Nama Kategori File
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="category_file_name" required=""
                                        aria-required="true" aria-invalid="true" value="">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Penjelasan File Kategori
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <textarea rows="4" class="form-control no-resize"
                                                name="category_file_explanation" required="" aria-required="true"
                                                aria-invalid="true" value=""></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Pilih Role yang diizinkan mengakses
                            </h2>
                            @foreach($role as $r)
                            <div class="form-check">
                                <input class="form-check-input" name="allowed_role[{{$r->id_role}}]" type="checkbox"
                                    value={{$r->id_role}} id="role-checkbox[{{$r->id_role}}]">
                                <label class="form-check-label"
                                    for="role-checkbox[{{$r->id_role}}]">{{$r->nm_role}}</label>
                            </div>
                            @endforeach
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