    <div class="container-fluid">
        <div class="block-header">
            <h2><a class="btn bg-blue waves-effect target-link"
                    href="{{ url(Request::segment(1) . '#mpmp/data-sub-folder-kategori-mapel/') }}"><i
                        class="material-icons">backspace</i><span>Kembali</span></a></h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            EDIT DATA SUB KATEGORI
                        </h2>
                    </div>
                    <div class="body">
                        <form id="form-validation" method="POST"
                            action="{{ url(Request::segment(1) .'/' .Request::segment(2) .'/data-sub-folder-kategori-mapel/action-data-sub-kategori/edit/' .$sub_data_kategori->sub_category_file_id) }}">
                            {{ csrf_field() }}
                            <h2 class="card-inside-title">
                                Nama Sub Kategori File
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="sub_category_file_name" required=""
                                        aria-required="true" aria-invalid="true"
                                        value="{{ $sub_data_kategori->sub_category_file_name }}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Penjelasan Sub Kategori File
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <textarea rows="4" class="form-control no-resize" name="sub_category_file_explanation" required="" aria-required="true"
                                                aria-invalid="true"
                                                value="">{{ $sub_data_kategori->sub_category_file_explanation }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Nama Kategori File
                            </h2>
                            <select class="form-control" name="category_file_id">
                                @foreach ($data_kategori as $value)
                                    @if ($sub_data_kategori->sub_category_file_id == $value->category_file_mgmp_id)
                                        <option value="{{ $value->category_file_mgmp_id }}" selected="">
                                            {{ $value->category_file_name }}</option>
                                    @else
                                        <option value="{{ $value->category_file_mgmp_id }}">
                                            {{ $value->category_file_name }}
                                        </option>
                                    @endif
                                @endforeach
                                {{-- @foreach ($sub_data_kategori as $value)
                                    @if ($sub_data_kategori->category_file_id == $value->category_file_id)
                                        <option value="{{ $value->category_file_id }}" selected="">
                                            {{ $value->category_file_name }}</option>
                                    @else
                                        <option value="{{ $value->category_file_id }}">
                                            {{ $value->category_file_name }}
                                        </option>
                                    @endif
                                @endforeach --}}
                            </select>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
