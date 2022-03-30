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
                href="{{ url(Request::segment(1) . '#manajemen-file/data-file/add') }}"><i
                    class="material-icons">note_add</i><span>Tambah File</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Data File Sub Kategori {{ $sub_category->sub_category_file_name }}</h2>
                </div>
                <div class="body">
                    @foreach ($files as $file)
                        <p>{{ $file->nm_pengguna }}</p>
                        <hr>
                        <div class="row">
                            @foreach ($data_file as $f)
                                <a href="{{ $f->is_google_drive == 1 ? $f->link_file : Storage::disk('spaces')->url($f->link_file) }}"
                                    target="_blank" style="color: inherit;text-decoration: inherit; ">
                                    @php
                                        $total_file++;
                                    @endphp
                                    <div class="col-md-3 file">
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
                            @endforeach
                        </div>
                    @endforeach
                    <p>TOTAL FILE : <b>{{ $total_file }}</b> </p>
                </div>
            </div>
        </div>
    </div>
</div>
