<div class="block-header">
    <h2><a type="button" class="btn bg-grey waves-effect"
            href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/paket-soal/detail/' . $paket_soal->id_paket_soal) }}">
            <i class="material-icons">keyboard_backspace</i>
            <span>Kembali</span>
        </a>
        <span style="background-color: white;padding:7px;border: 1px solid black;">
            <input type="checkbox" id="wuswug" class="checkbox" checked>
            <label for="wuswug">Aktifkan Input Gambar / Rumus</label>
        </span>
    </h2>
</div>

<form class="form-validation" id="form-validation" method="POST"
    action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/paket-soal/input-soal/new') }}">
    {{ csrf_field() }}
    <input type="hidden" name="id_tipe_soal" value="8">
    <input type="hidden" name="id_kategori_soal" value="{{ $paket_soal->id_kategori_soal }}">
    <input type="hidden" name="id_paket_soal" value="{{ $paket_soal->id_paket_soal }}">
    <br>


    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-pink">
                    <h2>
                        SOAL PILIHAN GANDA CERITA
                    </h2>
                </div>
                <div class="body">
                    <h2 class="card-inside-title">Cerita</h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <textarea id="soal" class="form-control soal" required="" name="soal" rows="3"></textarea>
                        </div>
                    </div>
                    <div id="pertanyan1">
                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <pre>1. Pertanyaan</pre>
                                <textarea class="form-control" required="" id="inputPertanyaan1" name="pertanyaan[1]" rows="3"></textarea>
                                <br>
                                <pre>Kunci Jawaban</pre>
                                <select class="form-control show-tick" name="jawaban_benar[1]" required="">
                                    @for ($i = 0; $i < 5; $i++)
                                        <option value="{{ $i }}">Jawaban {{ $i + 1 }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <pre>1. Jawaban </pre>
                                @for ($i = 0; $i < 5; $i++)
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row clearfix">
                                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                                <b> {{ $i + 1 }}:</b>
                                            </div>
                                            <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11">
                                                <textarea id="inputJawaban1{{ $i + 1 }}" class="form-control" required=""
                                                    name="jawaban[1][{{ $i }}]" rows="1"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                @endfor
                            </div>
                            <div class="col-lg-2
                                col-md-2 col-sm-12 col-xs-12">
                                <div style="margin-bottom:20px">
                                    <button class="btn btn-success btn-block" id="tambahPertanyaan" type="button"><i
                                            class="material-icons">add</i>
                                        <pre>Pertanyaan </pre>
                                    </button>
                                </div>
                                <div style="margin-bottom:20px">
                                    <button class="btn btn-danger btn-block" id="hapusPertanyaan" type="button"><i
                                            class="material-icons">delete</i>
                                        <pre>Pertanyaan </pre>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>


                    <div id="place">


                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row clearfix" style="margin-top: 10px">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <button class="btn btn-block bg-pink waves-effect" id="btn-submit" type="submit">Save</button>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <button class="btn btn-block bg-blue waves-effect" id="btn-view" type="button">Preview</button>
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
<script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>

<script>
    var pertanyaan = 1;
    var options = {
        filebrowserImageBrowseUrl: 'laravel-filemanager?type=Images',
        filebrowserImageUploadUrl: 'laravel-filemanager/upload?type=Images&_token=',
        filebrowserBrowseUrl: 'laravel-filemanager?type=Files',
        filebrowserUploadUrl: 'laravel-filemanager/upload?type=Files&_token=',
    };

    $(document).ready(function() {
        $('#btn-submit').attr('disabled', 'disabled');
        changeCkedior();
    })

    function changeCkedior() {
        console.log(pertanyaan);
        var checkbox = document.getElementById("wuswug");
        var isChecked = checkbox.checked;
        if (isChecked) {
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

        } else {
            for (var i = 1; i <= pertanyaan; i++) {
                id = 'inputPertanyaan' + i;
                CKEDITOR.instances[id].destroy();
            }


        }
    }

    $("#btn-view").click(function() {
        var checkbox = document.getElementById("wuswug");
        var isChecked = checkbox.checked;
        $('#btn-submit').removeAttr('disabled', 'disabled');

        if (isChecked) {
            checkbox.checked = !checkbox.checked;
            for (var i = 1; i <= pertanyaan; i++) {
                id = 'inputPertanyaan' + i;
                CKEDITOR.instances[id].destroy();
            }

        }
        $('#modal').html('');
        var html = '<table  class="table">';
        for (var i = 1; i <= pertanyaan; i++) {
            var soal = $(`textarea[id="inputPertanyaan${i}"]`).val();
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
                    var jawaban = $(`textarea[id="inputJawaban${i}${j+1}"]`).val();
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

    $('.checkbox').on('change', function() {
        if (this.checked) {
            for (var i = 1; i <= pertanyaan; i++) {
                id = 'inputPertanyaan' + i;
                var editor = CKEDITOR.replace(id, options);
                editor.on('instanceReady', function(event) {
                    event.editor.config.removePlugins = 'toolbar';
                    var toolbar = event.editor.ui.space('top');
                    toolbar && toolbar.remove();
                });
            }

        } else {
            for (var i = 1; i <= pertanyaan; i++) {
                id = 'inputPertanyaan' + i;
                CKEDITOR.instances[id].destroy();
            }
        }
    });

    $('#tambahPertanyaan').click(function() {
        if (pertanyaan != 5) {
            pertanyaan++;
            $('#pertanyan1').append(`
            <div id="pertanyaan${pertanyaan}">
                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <pre>${pertanyaan}. Pertanyaan</pre>
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
