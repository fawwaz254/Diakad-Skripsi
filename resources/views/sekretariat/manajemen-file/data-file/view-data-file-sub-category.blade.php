<style type="text/css">
    .folder:hover{
        transform: scale(1.2);
    }
    .folder{
        cursor: pointer;
    }
</style>

<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    <div class="header">
                        <h2>Data File Sub Kategori {{$sub_category->sub_category_file_name}}</h2>
                    </div>
                    <div class="body">

                        <div class="row">
                                
                            @foreach($file as $r)
                            <a href="{{Storage::disk('spaces')->url($r->link_file)}}" target="_blank" style="color: inherit;text-decoration: inherit; ">
                            <div class="col-md-3 folder">
                                <center>
                                  @if($r->extension_file == 'pdf')
                                  <i class="material-icons" style="color:red;font-size: 45px;">picture_as_pdf</i>
                                  @elseif($r->extension_file == 'docx')
                                  <i class="material-icons" style="color:blue;font-size: 45px;">description</i>
                                  @else
                                  <i class="material-icons" style="color:#DAA520;font-size: 45px;">folder</i>
                                  @endif                
                                </center>
                                <center>
                                    <span>{{$r->judul}}</span>
                                </center>
                            </div>
                            </a>
                            @endforeach

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>