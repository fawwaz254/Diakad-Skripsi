<div class="block-header">
    <h2><a type="button" class="btn bg-grey waves-effect"
            href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/soal') }}">
            <i class="material-icons">keyboard_backspace</i>
            <span>Kembali</span>
        </a>
    </h2>
</div>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-pink">
                <h2>
                    TAMBAH SOAL ESSAY
                </h2>
            </div>
            <div class="body">
                <form class="form-validation" id="form-validation" method="POST"
                    action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/soal/new') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="id_tipe_soal" value="2">
                    <input type="hidden" id="t1" name="text" >
                    <div class="row clearfix">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <label>Mata Pelajaran</label>
                        <select class="form-control show-tick" name="kategori" >
                            <option selected disabled>-- Pilih Mata Pelajaran --</option>
                            @foreach ($kategori as $r)
                                <option value="{{ $r->id_kategori_soal }}">{{ $r->nm_kategori_soal }}</option>
                            @endforeach
                        </select>
                        </div>
                    </div>
                    <h2 class="card-inside-title">Soal</h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <textarea id="q1" class="form-control q1" required="" name="soal" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-pink waves-effect" id="btn-submit"
                                type="submit">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('scriptjs')

<script src="{{asset('plugins/ckeditor/ckeditor.js')}}"></script>
{{-- <script src="{{asset('plugins/ckfinder/ckfinder.js')}}"></script> --}}
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>

<script>
 var options = {
    filebrowserImageBrowseUrl: 'laravel-filemanager?type=Images',
    filebrowserImageUploadUrl: 'laravel-filemanager/upload?type=Images&_token=',
    filebrowserBrowseUrl: 'laravel-filemanager?type=Files',
    filebrowserUploadUrl: 'laravel-filemanager/upload?type=Files&_token='
  };
</script>
<script>
var editor = CKEDITOR.replace('q1',options);
// CKFinder.setupCKEditor(editor);

timer = setInterval(updateDiv,100);
function updateDiv(){
    var editorText = CKEDITOR.instances.q1.getData();
    $('#q1').val(editorText);
    var text = CKEDITOR.instances.q1.document.getBody().getText();
    $('#t1').val(text);

}

</script>
