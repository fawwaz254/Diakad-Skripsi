@php
$total_file = 0;
@endphp
<style type="text/css">
    .file:hover {
        transform: scale(1.2);
    }

    .file {
        cursor: pointer;
    }

    i.file.material-icons {
        width: 45px;
    }

</style>

<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#mgmp/data-file-mapel/add') }}"><i
                    class="material-icons">note_add</i><span>Tambah File</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Data File Sub Kategori {{ $sub_category->sub_category_file_name }}</h2>
                </div>
                <div class="body">
                    <form
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/data-file-mapel/action-data-file/delete-many/0') }}"
                        id="form-validation" method="POST">
                        @foreach ($files as $file)
                            <div class="row">
                                <p>{{ $file->nm_pengguna }}</p>
                                <hr>
                            </div>
                            <div class="row">
                                @foreach ($file->file_pengguna as $f)
                                    <div class="col-md-3">
                                        <a href="{{ $f->is_google_drive == 1 ? $f->link_file : Storage::disk('spaces')->url($f->link_file) }}"
                                            target="_blank" style="color: inherit;text-decoration: inherit; ">
                                            @php
                                                $total_file++;
                                            @endphp
                                            <div class="file">
                                                <div style="text-align: center;">
                                                    @if ($f->extension_file == 'pdf')
                                                        <i class="material-icons"
                                                            style="color:red;font-size: 45px;width:45px">picture_as_pdf</i>
                                                    @elseif($f->extension_file == 'png' || $f->extension_file == 'jpg' || $f->extension_file == 'jpeg')
                                                        <i class="material-icons"
                                                            style="color:blue;font-size: 45px;width:45px">collections_icon</i>
                                                    @elseif($f->extension_file == 'pptx' || $f->extension_file == 'docx' || $f->extension_file == 'xlsx')
                                                        <i class="material-icons"
                                                            style="color:blue;font-size: 45px;width:45px">description</i>
                                                    @else
                                                        <i class="material-icons"
                                                            style="color:green;font-size: 45px;width:45px">add_to_drive</i>
                                                    @endif
                                                </div>

                                                <div style="text-align: center">
                                                    <span>{{ $f->judul }}</span>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="form-check delete-many"
                                            style="text-align: center;margin-top: 10px; display: none">
                                            <input type="checkbox" class="form-check-input"
                                                value="{{ $f->file_pengguna_id }}" name="id_file[]"
                                                id="id_file[{{ $f->file_pengguna_id }}]" required>
                                            <label class="form-check-label"
                                                for="id_file[{{ $f->file_pengguna_id }}]"></label>
                                            <input type="hidden" name="sub_category_file_id"
                                                value="{{ $sub_category->sub_category_file_id }}">
                                        </div>
                                        <div style="text-align: center;margin-top: 10px;" class="delete-one">
                                            <a
                                                onclick="deleteFile('{{ $f->file_pengguna_id }}','{{ $sub_category->sub_category_file_id }}')">
                                                <i style="color:red;" class="material-icons">cancel</i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </form>
                </div>
                <p>TOTAL FILE : <b>{{ $total_file }}</b></p>
            </div>
        </div>
    </div>
</div>
