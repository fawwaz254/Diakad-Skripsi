<div class="block-header">
    <h2><a type="button" class="btn bg-grey waves-effect" href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/paket-soal/detail/' . $paket_soal->id_paket_soal) }}">
            <i class="material-icons">keyboard_backspace</i>
            <span>Kembali</span>
        </a>

        <span style="background-color: white;padding:7px;border: 1px solid black;">
            <input type="checkbox" id="wuswug" class="checkbox" checked>
            <label for="wuswug">Aktifkan Input Gambar / Rumus</label>
        </span>
    </h2>
</div>

<form class="form-validation" id="form-validation" method="POST" action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/paket-soal/input-soal/new') }}">
    {{ csrf_field() }}
    <input type="hidden" name="id_tipe_soal" value="1">
    <input type="hidden" name="id_kategori_soal" value="{{ $paket_soal->id_kategori_soal }}">
    <input type="hidden" name="id_paket_soal" value="{{ $paket_soal->id_paket_soal }}">
    <br>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body">
                    <h2 class="card-inside-title">Paste 10 Soal dan Jawaban dari file Word</h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <textarea id="1" class="form-control " onpaste="pasteFunction()" rows="60" style="resize:vertical"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            @foreach (range(1, 10) as $j)
            @if ($j % 2 == 0)
            <div class="card" style="background-color: rgb(241, 241, 241)">
                <div class="header bg-pink">
                    @else
                    <div class="card">
                        <div class="header bg-blue">
                            @endif

                            <h2>
                                {{ $j . '. SOAL PILIHAN GANDA' }}
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <pre>{{ $j }}. Pertanyaan</pre>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <textarea id="{{ 'q' . $j }}" class="form-control {{ 'q' . $j }}" required="" name="soal[{{ $j }}]" rows="14" style="resize:vertical"></textarea>
                                        </div>
                                    </div>
                                    <pre>Kunci Jawaban</pre>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select class="form-control show-tick" name="jawaban_benar[{{ $j }}]" required="">
                                                @for ($i = 0; $i < 5; $i++) <option value="{{ $i }}">Jawaban {{ $i + 1 }}</option>
                                                    @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <pre>{{$j}}. Jawaban</pre>
                                    @for ($i = 0; $i < 5; $i++) <div class="row">
                                        <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row m-0">
                                                <label for="{{ 'a' . $j . $i }}" class="col-sm-2 col-form-label text-center"><pre>{{$i+1}}</pre></label>
                                                <div class="col-sm-10">
                                                    <textarea id="{{ 'a' . $j . $i }}" class="form-control {{ 'a' . $j . $i }}" required="" rows="1" name="jawaban[{{ $j }}][]" style="resize:vertical"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                @endfor
                            </div>


                        </div>
                    </div>
                </div>
                <br>
                @endforeach
            </div>
        </div>


        <div id="place">
        </div>

        <div class="row clearfix" style="margin-top: 10px">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <button class="btn btn-block bg-pink waves-effect" id="btn-submit" type="submit">Save</button>
                <button class="btn btn-block bg-blue waves-effect" id="btn-view" type="button" style="margin-bottom: 20px; margin-top: 20px">Preview</button>
            </div>
        </div>

        <br>
        <br>
</form>


<div class="modal" tabindex="-1" role="dialog" id="myModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="header bg-pink">
                <h4 class="modal-title" style="text-align: center">Preview Soal</h4>
            </div>

            <div id="modal">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@include('scriptjs')
<!-- CKeditor Plugin Js -->
<script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>

<script>
    var jumlah = 10;
    $(document).ready(function() {
        $('#btn-submit').attr('disabled', 'disabled');
        changeCkedior();
    })

    function changeCkedior() {
        var checkbox = document.getElementById("wuswug");
        var isChecked = checkbox.checked;
        if (isChecked) {
            for (var i = 1; i <= jumlah; i++) {
                id = 'q' + i;
                // var editor = CKEDITOR.replace(id, options);
                for (var j = 0; j < 5; j++) {
                    idjawaban = 'a' + i + j;
                    // var editorjawaban = CKEDITOR.replace(idjawaban, options);
                }
            }

        } else {
            for (var i = 1; i <= jumlah; i++) {
                id = 'q' + i;
                // CKEDITOR.instances[id].destroy();
                for (var j = 0; j < 5; j++) {
                    idjawaban = 'a' + i + j;
                    // CKEDITOR.instances[idjawaban].destroy();
                }
            }

        }
    }


    $("#btn-view").click(function() {
        var checkbox = document.getElementById("wuswug");
        var isChecked = checkbox.checked;
        $('#btn-submit').removeAttr('disabled', 'disabled');

        if (isChecked) {
            checkbox.checked = !checkbox.checked;
            for (var i = 1; i <= jumlah; i++) {
                id = 'q' + i;
                // CKEDITOR.instances[id].destroy();
                for (var j = 0; j < 5; j++) {
                    idjawaban = 'a' + i + j;
                    // CKEDITOR.instances[idjawaban].destroy();
                }
            }
        }
        $('#modal').html('');
        var html = '<table  class="table">';
        for (var i = 1; i <= jumlah; i++) {
            var soal = $(`textarea[id="q${i}"]`).val();
            var kunci = $(`select[name="jawaban_benar[${i}]"]`).val();
            if (soal) {
                html += '<tr>';
                html += '<td style="text-align: center;">';
                html += i + '. Soal';
                html += '</td >';
                html += '</tr>';
                html += '<tr>';
                html += '<td >';
                html +=
                    '<pre style="white-space: pre-wrap; word-wrap: break-word;">' +
                    soal + '</pre>';
                html += '</td>';
                html += '</tr>';

                html += '<tr>';
                html += '<td style="text-align: center">';
                html += 'Jawaban';
                html += '</td >';
                html += '</tr>';

                for (var j = 0; j < 5; j++) {
                    idjawaban = 'a' + i + j;
                    var jawaban = $(`textarea[id="a${i}${j}"]`).val();
                    html += '<tr>';
                    html += '<td >';
                    if (j == kunci) {
                        html +=
                            '<pre style="white-space: pre-wrap; word-wrap: break-word;background-color:#CFE795"">' +
                            jawaban + '</pre>';
                    } else {
                        html += '<pre style="white-space: pre-wrap; word-wrap: break-word;">' + jawaban +
                            '</pre>';
                    }
                    html += '</td >';
                    html += '</tr>';

                }
                html += '<tr>';
                html += '<td style="border: 1px solid pink;">';

                html += '</td >';
            }
        }
        html += '</table>';
        $('#modal').html(html);



        $('#myModal').modal('show');


    });


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
                // var editor = CKEDITOR.replace(id, options);
                for (var j = 0; j < 5; j++) {
                    idjawaban = 'a' + i + j;
                    // var editorjawaban = CKEDITOR.replace(idjawaban, options);
                }
            }

        } else {
            for (var i = 1; i <= jumlah; i++) {
                id = 'q' + i;
                // CKEDITOR.instances[id].destroy();
                for (var j = 0; j < 5; j++) {
                    idjawaban = 'a' + i + j;
                    // CKEDITOR.instances[idjawaban].destroy();
                }
            }

        }
    });

    function pasteFunction() {
        var checkbox = document.getElementById("wuswug");
        var isChecked = checkbox.checked;

        var clipboardData = event.clipboardData || window.clipboardData;
        var pastedText = clipboardData.getData("text") || window.clipboardData.getData("Text");
        var lines = pastedText.split("\n");
        for (var i = 1; i <= jumlah; i++) {
            var id_paste_soal = 'q' + i;
            if (isChecked) {
                // CKEDITOR.instances[id_paste_soal].destroy();
            }
            for (var j = 0; j < 5; j++) {
                id_paste_jawaban = 'a' + i + j;
                if (isChecked) {
                    // CKEDITOR.instances[id_paste_jawaban].destroy();
                }
            }
        }



        setTimeout(function() {
            var linesoal = 0;
            for (var i = 1; i <= jumlah; i++) {
                var id_paste_soal = 'q' + i;
                var inputElementSoal = document.getElementById(id_paste_soal);
                if (inputElementSoal === null) {} else {
                    $dataSoal = lines[linesoal].split("\t");
                    if ($dataSoal.length == '2') {
                        inputElementSoal.value = $dataSoal[1];
                    } else {
                        inputElementSoal.value = $dataSoal[0];
                    }
                }

                if (isChecked) {
                    // CKEDITOR.replace(id_paste_soal, options);
                }

                linesoal += 6;
            }
        }, 1000);

        setTimeout(function() {
            var lineJawaban = 1;
            for (var i = 1; i <= jumlah; i++) {
                for (var j = 0; j < 5; j++) {
                    id_paste_jawaban = 'a' + i + j;
                    var inputElementJawaban = document.getElementById(id_paste_jawaban);
                    if (inputElementJawaban === null) {} else {
                        $data = lines[lineJawaban].split("\t");
                        if ($data.length == '2') {
                            inputElementJawaban.value = $data[1];
                        } else {
                            inputElementJawaban.value = $data[0];
                        }

                    }
                    if (isChecked) {
                        // CKEDITOR.replace(id_paste_jawaban, options);
                    }
                    lineJawaban++;
                }
                lineJawaban++;
            }
        }, 1000)


    }
</script>