<div class="container-fluid">
    <div class="block-header">
        <h2><a type="button" class="btn bg-grey waves-effect"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/paket-soal/bank-soal') }}">
                <i class="material-icons">keyboard_backspace</i>
                <span>Kembali</span>
            </a>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-pink">
                    @if ($item->id_tipe_soal == 2)
                        <h2>EDIT JAWABAN ESSAY</h2>
                    @else
                        <h2>EDIT JAWABAN FILE</h2>
                    @endif
                    {{-- <div class="header-dropdown m-r-15" style="top:12px">
                        <a class="btn bg-orange waves-effect"
                            href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/soal/test/' . $item->id_soal) }}"
                            target="_blank">
                            <i class="material-icons">open_in_new</i>
                            <span>To Test Page</span>
                        </a>
                    </div> --}}
                </div>
                <div class="body">
                    <form class="form-validation" method="POST" id="form-validation"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/paket-soal/bank-soal/new') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="id_soal" value="{{ $item->id_soal }}">
                        <input type="hidden" name="id_tipe_soal" value="{{ $item->id_tipe_soal }}">
                        <input type="hidden" id="t1" name="text">
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label>Mata Pelajaran</label>
                                <select class="form-control show-tick" name="kategori">
                                    <option disabled>-- Pilih Mata Pelajaran --</option>
                                    @foreach ($kategori as $r)
                                        <option value="{{ $r->id_kategori_soal }}"
                                            @if ($r->id_kategori_soal == $item->id_kategori_soal) selected @endif>{{ $r->nm_kategori_soal }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">Soal</h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <textarea id="q1" class="form-control q1" name="soal" data-sample-short>{!! $item->content !!}</textarea>
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
</div>
@include('scriptjs')
<!-- CKeditor Plugin Js -->
<script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>

<script>
    var options = {
        filebrowserImageBrowseUrl: 'laravel-filemanager?type=Images',
        filebrowserImageUploadUrl: 'laravel-filemanager/upload?type=Images&_token=',
        filebrowserBrowseUrl: 'laravel-filemanager?type=Files',
        filebrowserUploadUrl: 'laravel-filemanager/upload?type=Files&_token='
    };

    CKEDITOR.replace('q1', options);

    // custom code to key binding ckeditor
    timer = setInterval(updateDiv, 100);

    function updateDiv() {
        var editorText = CKEDITOR.instances.q1.getData();
        $('#q1').val(editorText);
        var text = CKEDITOR.instances.q1.document.getBody().getText();
        $('#t1').val(text);

    }
</script>
