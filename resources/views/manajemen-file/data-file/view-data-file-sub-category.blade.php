<style type="text/css">
    .folder:hover{
        transform: scale(1.2);
    }
    .folder{
        cursor: pointer;
    }
    i.folder.material-icons{
        width:45px;
    }
</style>

<div class="container-fluid">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#manajemen-file/data-file/add')}}"><i class="material-icons">note_add</i><span>Tambah File</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    <div class="header">
                        <h2>Data File Sub Kategori {{$sub_category->sub_category_file_name}}</h2>
                    </div>
                    <div class="body">

                        @foreach ($file as $f)
                        @if (!$f->file_pengguna->isEmpty())
                        <p>{{$f->nm_pengguna}}</p>
                        <hr> 
                        @endif
                        <div class="row">
                            @foreach($f->file_pengguna as $r)
                            @if($r->is_google_drive == 1)
                            <a href="{{$r->link_file}}" target="_blank" style="color: inherit;text-decoration: inherit; ">
                            @else
                            <a href="{{Storage::disk('spaces')->url($r->link_file)}}" target="_blank" style="color: inherit;text-decoration: inherit; ">
                            @endif
                                <div class="col-md-3 folder">
                                    <center>
                                        @if($r->extension_file == 'pdf')
                                        <i class="material-icons" style="color:red;font-size: 45px;">picture_as_pdf</i>
                                        @elseif($r->extension_file == 'png' || $r->extension_file == 'jpg' || $r->extension_file == 'jpeg')
                                        <i class="material-icons" style="color:blue;font-size: 45px">collections_icon</i>
                                        @elseif($r->extension_file == 'pptx' || $r->extension_file == 'docx' || $r->extension_file == 'xlsx')
                                        <i class="material-icons" style="color:blue;font-size: 45px;">description</i>
                                        @else
                                        <i class="material-icons" style="color:green;font-size: 45px;">add_to_drive</i>
                                        @endif                
                                    </center>

                                    <center>
                                        <span>{{$r->judul}}</span>
                                    </center>
                                </div>
                            </a>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>