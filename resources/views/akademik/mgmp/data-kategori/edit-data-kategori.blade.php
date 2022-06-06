<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link "
                href="{{ url(Request::segment(1) . '#mpmp/data-kategori-mapel/') }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        EDIT DATA KATEGORI MAPEL
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) .'/' .Request::segment(2) .'/data-kategori-mapel/action-data-kategori/edit/' .$name->category_file_mgmp_id) }}">
                        {{ csrf_field() }}
                        <h2 class="card-inside-title">
                            Nama Mapel
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="category_file_name" required="">
                                    @foreach($mata_pelajaran as $r)
                                    @if($r->nm_mata_pelajaran == $name->category_file_name )
                                        <option value="{{$r->nm_mata_pelajaran}}" selected>{{$r->nm_mata_pelajaran}}</option>
                                        @else
                                        <option value="{{$r->nm_mata_pelajaran}}">{{$r->nm_mata_pelajaran}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                        Jenjang Kelas
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="category_file_explanation" required=""
                                aria-required="true" aria-invalid="true" value="{{$name->category_file_explanation}}">

                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>

                        <h2 class="card-inside-title">
                            Guru yang diizinkan mengakses:
                        </h2>

                        @foreach($pengguna as $guru)
                        <div class="form-check">

                            @if(in_array($guru->id_pengguna, $allowed_role_pengguna))
                        
                            <input class="form-check-input" name="allowed_guru[{{$guru->id_pengguna}}]" type="checkbox"
                            value={{$guru->id_pengguna}} id="role-checkbox[{{$guru->id_pengguna}}]" checked>
                        <label class="form-check-label"
                            for="role-checkbox[{{$guru->id_pengguna}}]">{{$guru->nm_pengguna}}</label>
                            @else
                            <input class="form-check-input" name="allowed_guru[{{$guru->id_pengguna}}]" type="checkbox"
                            value={{$guru->id_pengguna}} id="role-checkbox[{{$guru->id_pengguna}}]">
                        <label class="form-check-label"
                            for="role-checkbox[{{$guru->id_pengguna}}]">{{$guru->nm_pengguna}}</label>
                            @endif

                      
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
