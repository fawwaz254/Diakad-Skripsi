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
                    TAMBAH SOAL PILIHAN GANDA
                </h2>
            </div>
            <div class="body">
                <form class="form-validation" id="form-validation" method="POST"
                    action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/soal/new') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="id_tipe_soal" value="1">
                    <input type="hidden" id="t1" name="text">

                    <div class="row clearfix">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label>Mata Pelajaran</label>
                            <select class="form-control show-tick" name="kategori">
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
                    @for ($i = 0; $i < 5; $i++)
                        <h2 class="card-inside-title">Jawaban {{ $i + 1 }}</h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <textarea id="a{{ $i }}" class="form-control a0" required="" name="jawaban[]"></textarea>
                            </div>
                        </div>
                    @endfor
                    <h2 class="card-inside-title">Jawaban Benar</h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <select class="form-control show-tick" name="jawaban_benar" required="">
                                @for ($i = 0; $i < 5; $i++)
                                    <option value="{{ $i }}">Jawaban {{ $i + 1 }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
<!-- CKeditor Plugin Js -->
<script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
{{-- <script src="{{ asset('plugins/ckfinder/ckfinder.js') }}"></script> --}}
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>

<script>
     var options = {
    filebrowserImageBrowseUrl: 'laravel-filemanager?type=Images',
    filebrowserImageUploadUrl: 'laravel-filemanager/upload?type=Images&_token=',
    filebrowserBrowseUrl: 'laravel-filemanager?type=Files',
    filebrowserUploadUrl: 'laravel-filemanager/upload?type=Files&_token='
  };

    var editorq1 = CKEDITOR.replace('q1',options);
    // CKFinder.setupCKEditor(editorq1);

    // custom code to key binding ckeditor
    timer = setInterval(updateDiv, 100);

    function updateDiv() {
        var editorText = CKEDITOR.instances.q1.getData();
        $('#q1').val(editorText);
        var text = CKEDITOR.instances.q1.document.getBody().getText();
        $('#t1').val(text);

    }
</script>

<script>
    var editora0 = CKEDITOR.replace('a0',options);
    // CKFinder.setupCKEditor(editora0);

    // custom code to key binding ckeditor
    timer = setInterval(updateDiv, 100);

    function updateDiv() {
        var editorText = CKEDITOR.instances.a0.getData();
        $('#a0').val(editorText);
    }
</script>

<script>
    var editora1 = CKEDITOR.replace('a1',options);
    // custom code to key binding ckeditor
    timer = setInterval(updateDiv, 100);

    function updateDiv() {
        var editorText = CKEDITOR.instances.a1.getData();
        $('#a1').val(editorText);
    }
</script>

<script>
    var editora2 = CKEDITOR.replace('a2',options);


    // custom code to key binding ckeditor
    timer = setInterval(updateDiv, 100);

    function updateDiv() {
        var editorText = CKEDITOR.instances.a2.getData();
        $('#a2').val(editorText);
    }
</script>

<script>
    var editora3 = CKEDITOR.replace('a3',options);

    // custom code to key binding ckeditor
    timer = setInterval(updateDiv, 100);

    function updateDiv() {
        var editorText = CKEDITOR.instances.a3.getData();
        $('#a3').val(editorText);
    }
</script>

<script>
    var editora4 = CKEDITOR.replace('a4',options);

    // custom code to key binding ckeditor
    timer = setInterval(updateDiv, 100);

    function updateDiv() {
        var editorText = CKEDITOR.instances.a4.getData();
        $('#a4').val(editorText);
    }
</script>
