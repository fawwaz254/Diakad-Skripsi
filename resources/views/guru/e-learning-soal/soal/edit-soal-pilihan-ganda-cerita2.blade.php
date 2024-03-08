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
                <div class="body">
                    <form class="form-validation" method="POST" id="form-validation"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/paket-soal/bank-soal/new') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="kategori" value="{{ $item->id_kategori_soal }}">
                        <input type="hidden" name="id_soal" value="{{ $item->id_soal }}">
                        <input type="hidden" name="id_tipe_soal" value="{{ $item->id_tipe_soal }}">
                        <h2 class="card-inside-title">Cerita</h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <textarea id="soal" class="form-control soal" required="" name="soal" rows="10">{{ $item->content }}</textarea>
                            </div>
                        </div>
                        @foreach ($data_pilihan_pertanyaan as $key => $pilihan_pertanyaan)
                            <input type="hidden" name="id_pilihan_pertanyaan[{{ $key + 1 }}]"
                                value="{{ $pilihan_pertanyaan->id_pilihan_pertanyaan }}" />
                            <div id="pertanyaan{{ $key + 1 }}">
                                <div class="row clearfix">
                                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                        <pre>{{ $key + 1 }}. Pertanyaan</pre>

                                        <textarea class="form-control" required="" id="inputPertanyaan{{ $key + 1 }}"
                                            name="pertanyaan[{{ $key + 1 }}]" rows="3">{{ $pilihan_pertanyaan->text }}</textarea>
                                        <br>
                                        <pre>Kunci Jawaban</pre>
                                        <select class="form-control show-tick"
                                            name="jawaban_benar[{{ $key + 1 }}]" required="">
                                            @for ($i = 0; $i < 5; $i++)
                                                <option value="{{ $key }}"
                                                    @if ($pilihan_pertanyaan->jawaban == $i) selected @endif>Jawaban
                                                    {{ $i + 1 }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                        <pre>{{ $key + 1 }}. Jawaban </pre>
                                        @foreach (json_decode($pilihan_pertanyaan->options) as $no_option => $option)
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="row clearfix">
                                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                                        <b> {{ $no_option + 1 }}:</b>
                                                    </div>
                                                    <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11">

                                                        <textarea id="inputJawaban{{ $key + 1 . $no_option }}" class="form-control" required=""
                                                            name="jawaban[{{ $key + 1 }}][{{ $no_option }}]" rows="1">{{ $option }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @if ($key == 0)
                                        <div
                                            class="col-lg-2
                                                col-md-2 col-sm-12 col-xs-12">
                                            <div style="margin-bottom:20px">
                                                <button class="btn btn-success btn-block" id="tambahPertanyaan"
                                                    type="button"><i class="material-icons">add</i>
                                                    <pre>Pertanyaan </pre>
                                                </button>
                                            </div>
                                            <div style="margin-bottom:20px">
                                                <button class="btn btn-danger btn-block" id="hapusPertanyaan"
                                                    type="button"><i class="material-icons">delete</i>
                                                    <pre>Pertanyaan </pre>
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <hr>
                            </div>
                        @endforeach
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-pink waves-effect" id="btn-submit"
                                    type="submit">Save</button>
                            </div>
                        </div>
                </div>
                <br>
                <br>
                <br>
                </form>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script>
    var pertanyaan = '{{ $data_pilihan_pertanyaan->count() }}';
    var options = {
        filebrowserImageBrowseUrl: 'laravel-filemanager?type=Images',
        filebrowserImageUploadUrl: 'laravel-filemanager/upload?type=Images&_token=',
        filebrowserBrowseUrl: 'laravel-filemanager?type=Files',
        filebrowserUploadUrl: 'laravel-filemanager/upload?type=Files&_token='
    };


    $(document).ready(function() {
        changeCkedior();
    })

    function changeCkedior() {

        for (var i = 1; i <= pertanyaan; i++) {
            id = 'inputPertanyaan' + i;
            console.log(id);
            var editor = CKEDITOR.replace(id, options);
            if (editor) {
                editor.on('instanceReady', function(event) {
                    event.editor.config.removePlugins = 'toolbar';
                    var toolbar = event.editor.ui.space('top');
                    toolbar && toolbar.remove();
                });
            }

        }
    }


    $('#tambahPertanyaan').click(function() {
        if (pertanyaan != 5) {
            pertanyaan++;

            $(`#pertanyaan${pertanyaan-1}`).append(`
            <div id="pertanyaan${pertanyaan}">
                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <pre>${pertanyaan}. Pertanyaan</pre>
                                <input type="hidden" name="id_pilihan_pertanyaan[${pertanyaan}]"
                                value="0" />
                                <textarea class="form-control" required="" id="inputPertanyaan${pertanyaan}" name="pertanyaan[${pertanyaan}]" rows="3"></textarea>
                                
                                <br>
                                <pre>Kunci Jawaban</pre>
                                <select class="form-control show-tick" name="jawaban_benar[${pertanyaan}]" required="">
                                    @for ($i = 0; $i < 5; $i++)
                                        <option value="{{ $i }}">Jawaban {{ $i + 1 }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <pre>${pertanyaan}. Jawaban </pre>
                                @for ($i = 0; $i < 5; $i++)
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row clearfix">
                                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                                <b> {{ $i + 1 }}:</b>
                                            </div>
                                            <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11">
                                                <textarea id="inputJawaban${pertanyaan}{{ $i + 1 }}" class="form-control" required="" name="jawaban[${pertanyaan}][{{ $i }}]" rows="1"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                @endfor
                            </div>
                        </div>
                </div>
                <hr>`);

            setTimeout(function() {
                changeCkedior();
            }, 1000);

        }
    });

    $('#hapusPertanyaan').click(function() {
        if (pertanyaan != 1) {
            var element = document.getElementById('pertanyaan' + pertanyaan);
            element.remove();
            pertanyaan--;
        }

    });
</script>
