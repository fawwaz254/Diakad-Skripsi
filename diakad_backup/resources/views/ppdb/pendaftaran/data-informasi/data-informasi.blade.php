<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    <div class="header">
                        <h2>DATA INFORMASI PPDB</h2>
                    </div>

                    <div class="body" style="padding-bottom:50px;">
                        <h2 class="card-inside-title">
                            Isi Informasi
                        </h2>
                        <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/data-informasi')}}" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <textarea name="isi_informasi" id="editor1" class="editor1" rows="10" cols="80">{{$isi_informasi}}</textarea>
                            <h2><button class="btn bg-green waves-effect" type="submit"><i class="material-icons">save</i><span>Simpan Data Informasi </button></h2>
                        </form>
                        <div id="te"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CKeditor Plugin Js -->
<script src="{{asset('plugins/ckeditor/ckeditor.js')}}"></script>

<script>
CKEDITOR.replace( 'editor1' );

// custom code to key binding ckeditor
timer = setInterval(updateDiv,100);
function updateDiv(){
    var editorText = CKEDITOR.instances.editor1.getData();
    $('#editor1').val(editorText);
}
</script>

@include('scriptjs')