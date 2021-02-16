<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{url(Request::segment(1).'#'.Request::segment(2).'/'.Request::segment(3))}}">
                <i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        DETAIL LOWONGAN KERJA
                    </h2>
                </div>
                <div class="body">
                        <h2 class="card-inside-title">
                            Judul Lowongan Kerja
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="judul_lowongan_kerja" required=""
                                    aria-required="true" aria-invalid="true" value="{{(!empty($item))? $item->judul_lowongan_kerja : ''}}">
                            </div>
                        </div>

                        @if($item->poster_lowongan_kerja)

                        <h2 class="card-inside-title">
                            Poster Lowongan Kerja
                        </h2>
                        @php
                        $ext = pathinfo($item->poster_lowongan_kerja, PATHINFO_EXTENSION);
                        @endphp

                        @if($ext=='pdf'||$ext=='doc'||$ext=='docx')
                         <a href="{{Storage::disk('spaces')->url($item->poster_lowongan_kerja)}}" target="_blank"> <i class="material-icons" style="font-size: 60px;">insert_drive_file</i></a>
                        @else
                        <a href="{{Storage::disk('spaces')->url($item->poster_lowongan_kerja)}}" target="_blank"><img src="{{Storage::disk('spaces')->url($item->poster_lowongan_kerja)}}" style="width: 300px;height: 300px;"></a>
                        @endif
                        @endif

                        <h2 class="card-inside-title">
                            Deskripsi Lowongan Kerja
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <textarea id="editor1" class="editor1" name="deskripsi_lowongan_kerja" required="">
                          {{(!empty($item))? $item->deskripsi_lowongan_kerja : ''}}
                        </textarea>

                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CKeditor Plugin Js -->
<script src="{{asset('plugins/ckeditor/ckeditor.js')}}"></script>

<script>

CKEDITOR.replace('editor1');

// custom code to key binding ckeditor
timer = setInterval(updateDiv,100);
function updateDiv(){
    var editorText = CKEDITOR.instances.editor1.getData();
    $('#editor1').val(editorText);
}
</script>

