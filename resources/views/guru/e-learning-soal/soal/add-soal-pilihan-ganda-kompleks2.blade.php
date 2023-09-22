<div class="block-header">
    <h2><a type="button" class="btn bg-grey waves-effect"
            href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/paket-soal/detail/' . $paket_soal->id_paket_soal) }}">
            <i class="material-icons">keyboard_backspace</i>
            <span>Kembali</span>
        </a>
        {{-- <button type="button" id="add" class="btn bg-green waves-effect">
            <i class="material-icons">add_box</i>
            <span>Tambah Jumlah Soal</span>
        </button> --}}
        {{-- <button type="button" id="remove" class="btn bg-red waves-effect">
            <i class="material-icons">indeterminate_check_box</i>
            <span>Hapus Jumlah Soal</span>
        </button>
        <input type="text" name="jumlah" style="padding:7px; background-color:white;border: 1px solid black;"
            value="Jumlah Soal = 1" disabled>
        <span style="background-color: white;padding:7px;border: 1px solid black;">
            <input type="checkbox" id="wuswug" class="checkbox">
            <label for="wuswug">Aktifkan Input Gambar / Rumus</label>
        </span> --}}
    </h2>
</div>

<form class="form-validation" id="form-validation" method="POST"
    action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/paket-soal/input-soal/new') }}">
    {{ csrf_field() }}
    <input type="hidden" name="id_tipe_soal" value="4">
    <input type="hidden" name="id_kategori_soal" value="{{ $paket_soal->id_kategori_soal }}">
    <input type="hidden" name="id_paket_soal" value="{{ $paket_soal->id_paket_soal }}">
    <br>

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-pink">
                    <h2>
                        PILIHAN GANDA KOMPLEKS
                    </h2>
                </div>
                <div class="body">
                    <h2 class="card-inside-title">Paste Soal dan Jawaban dari file World</h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <textarea id="1" class="form-control " onpaste="pasteFunction(this)" rows="5"></textarea>
                        </div>
                    </div>
                    <h2 class="card-inside-title">Soal</h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <textarea id="q1" class="form-control q1" required="" name="soal[1]" rows="3"></textarea>
                        </div>
                    </div>
                    @for ($i = 0; $i < 5; $i++)
                        <h2 class="card-inside-title">Jawaban {{ $i + 1 }}</h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <textarea id="a1{{ $i }}" class="form-control a1{{ $i }}" required="" name="jawaban[1][]"></textarea>
                            </div>
                        </div>
                    @endfor
                    <h2 class="card-inside-title">Jawaban Benar</h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            @for ($i = 0; $i < 5; $i++)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                        name="jawaban_benar[1][{{ $i }}]" value="{{ $i }}"
                                        id="jawaban_benar[1][{{ $i }}]">
                                    <label class="form-check-label" for="jawaban_benar[1][{{ $i }}]">
                                        Jawaban {{ $i + 1 }}
                                    </label>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="place">
    </div>

    <div class="row clearfix" style="margin-top: 10px">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <button class="btn btn-block bg-pink waves-effect" id="btn-submit" type="submit">Save</button>
        </div>
    </div>

    <br>
    <br>
</form>
@include('scriptjs')
<!-- CKeditor Plugin Js -->
<script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>

<script>
    var jumlah = 1;
    var options = {
        filebrowserImageBrowseUrl: 'laravel-filemanager?type=Images',
        filebrowserImageUploadUrl: 'laravel-filemanager/upload?type=Images&_token=',
        filebrowserBrowseUrl: 'laravel-filemanager?type=Files',
        filebrowserUploadUrl: 'laravel-filemanager/upload?type=Files&_token='
    };

    $('.checkbox').on('change', function() { // on change of state
        if (this.checked) // if changed state is "CHECKED"
        {
            for (var i = 1; i <= jumlah; i++) {
                id = 'q' + i;
                var editor = CKEDITOR.replace(id, options);
                for (var j = 0; j < 5; j++) {
                    idjawaban = 'a' + i + j;
                    var editorjawaban = CKEDITOR.replace(idjawaban, options);
                }
            }

        } else {
            for (var i = 1; i <= jumlah; i++) {
                id = 'q' + i;
                CKEDITOR.instances[id].destroy();
                for (var j = 0; j < 5; j++) {
                    idjawaban = 'a' + i + j;
                    CKEDITOR.instances[idjawaban].destroy();
                }
            }

        }
    });

    $('#add').click(function() {
        if (jumlah != 10) {

            jumlah++;
            var value = 'Jumlah Soal = ' + jumlah;
            $("input[name='jumlah']").val(value);
            $('#place').append(`
        <div class="row clearfix" style="margin-top: 10px" id="${jumlah }">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-pink">
                    <h2>
                       ${jumlah} . SOAL PILIHAN GANDA
                    </h2>
                </div>
                <div class="body">
                    <h2 class="card-inside-title">Paste Soal dan Jawaban dari file World</h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <textarea id="${jumlah}" onpaste="pasteFunction(this)" class="form-control " rows="1"></textarea>
                        </div>
                    </div>
                    <h2 class="card-inside-title">Soal</h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <textarea id="q${jumlah}" class="form-control q${jumlah}" required="" name="soal[${jumlah}]" rows="3"></textarea>
                        </div>
                    </div>
                    @for ($i = 0; $i < 5; $i++)
                        <h2 class="card-inside-title">Jawaban {{ $i + 1 }}</h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <textarea id="a${jumlah}{{ $i }}" class="form-control a${jumlah}{{ $i }}" required="" name="jawaban[${jumlah}][]"></textarea>
                            </div>
                        </div>
                    @endfor
                    <h2 class="card-inside-title">Jawaban Benar</h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <select class="form-control show-tick" name="jawaban_benar[${jumlah}]" required="">
                                @for ($i = 0; $i < 5; $i++)
                                    <option value="{{ $i }}">Jawaban {{ $i + 1 }}</option>
                                @endfor
                            </select>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        `);

        }
    });

    $('#remove').click(function() {
        if (jumlah != 1) {
            var element = document.getElementById(jumlah);
            jumlah--;
            var value = 'Jumlah Soal = ' + jumlah;
            $("input[name='jumlah']").val(value);
            while (element.firstChild) {
                element.removeChild(element.firstChild);
            }
            element.remove();
        }
    });

    function pasteFunction(el) {
        var i = el.id;
        var clipboardData = event.clipboardData || window.clipboardData;
        var pastedText = clipboardData.getData("text") || window.clipboardData.getData("Text");
        var lines = pastedText.split("\n");
        for (var j = 0; j < 5; j++) {
            id_paste_jawaban = 'a' + i + j;
            var inputElementJawaban = document.getElementById(id_paste_jawaban);
            if (inputElementJawaban === null) {} else {
                $data = lines[j + 1].split("\t");
                if ($data.length == '2') {
                    inputElementJawaban.value = $data[1];
                } else {
                    inputElementJawaban.value = $data[0];
                }

            }

        }
        var id_paste_soal = 'q' + i;
        var inputElementSoal = document.getElementById(id_paste_soal);
        if (inputElementSoal === null) {} else {
            $dataSoal = lines[0].split("\t");
            if ($dataSoal.length == '2') {
                inputElementSoal.value = $dataSoal[1];
            } else {
                inputElementSoal.value = $dataSoal[0];
            }
        }

    }
</script>
