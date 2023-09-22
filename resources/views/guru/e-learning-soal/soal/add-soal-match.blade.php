<div class="block-header">
    <h2><a type="button" class="btn bg-grey waves-effect"
            href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/paket-soal/bank-soal') }}">
            <i class="material-icons">keyboard_backspace</i>
            <span>Kembali</span>
        </a>
        {{-- <button type="button" id="add" class="btn bg-green waves-effect">
            <i class="material-icons">add_box</i>
            <span>Tambah Jumlah Soal</span>
        </button>
        <button type="button" id="remove" class="btn bg-red waves-effect">
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
    action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/paket-soal/bank-soal/new') }}">
    {{ csrf_field() }}
    <input type="hidden" name="id_tipe_soal" value="6">
    <br>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body">

                    <br>
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
                    <br>
                </div>
            </div>
        </div>
    </div>
    <br>

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-pink">
                    <h2>
                        SOAL PENJODOHAN
                    </h2>
                </div>
                <div class="body">
                    <h2 class="card-inside-title">Penjelasan</h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <textarea id="soal" class="form-control soal" required="" name="soal" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                            <div id="pertanyan">
                                <div class="card" style="background-color: #e3e3e3; padding: 10px;">
                                    <h2 class="card-inside-title">Pertanyaan 1</h2>
                                    <textarea class="form-control" required="" name="pertanyaan[1]" rows="3"></textarea>
                                    <h2 class="card-inside-title">No Jawaban</h2>
                                    <input id="noJawaban1" type="number" class="form-control" required=""
                                        name="noJawaban[1]" />
                                </div>
                                <br>
                                <br>
                            </div>
                        </div>

                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                            <div id="jawaban">
                                <div class="card" style="background-color: #e3e3e3; padding: 10px;">
                                    <h2 class="card-inside-title">Jawaban 1</h2>
                                    <textarea id="q1" class="form-control q1" required="" name="jawaban[1]" rows="3"></textarea>
                                </div>
                                <br><br>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                            <div style="margin-bottom:20px">
                                <button class="btn btn-success btn-block" id="tambahPertanyaan" type="button"><i
                                        class="material-icons">add</i>
                                    <pre>Pertanyaan </pre>
                                </button>
                            </div>
                            <div style="margin-bottom:20px">
                                <button class="btn btn-success btn-block" id="tambahJawaban" type="button"><i
                                        class="material-icons">add</i>
                                    <pre>Jawaban</pre>
                                </button>
                            </div>
                            <div style="margin-bottom:20px">
                                <button class="btn btn-danger btn-block" id="hapusPertanyaan" type="button"><i
                                        class="material-icons">delete</i>
                                    <pre>Pertanyaan </pre>
                                </button>
                            </div>
                            <div style="margin-bottom:20px">
                                <button class="btn btn-danger btn-block" type="button" id="hapusJawaban"><i
                                        class="material-icons">delete</i>
                                    <pre>Jawaban</pre>
                                </button>
                            </div>
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
<script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>

<script>
    var jumlah = 1;
    var jawaban = 1;
    var pertanyaan = 1;
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

    $('#tambahPertanyaan').click(function() {
        if (pertanyaan != 5) {
            pertanyaan++;
            $('#pertanyan').append(`
            <div id="pertanyaan${pertanyaan}">
		<div class="card" style="background-color: #e3e3e3; padding: 10px;" >
                                <h2 class="card-inside-title">Pertanyaan ${pertanyaan}</h2>
                                <textarea id="pertanyaan${pertanyaan }" class="form-control" required="" name="pertanyaan[${pertanyaan }]" rows="3"></textarea>
                                <h2 class="card-inside-title">No Jawaban</h2>
                                <input id="noJawaban${pertanyaan }" type="number" class="form-control" required=""
                                    name="noJawaban[${pertanyaan }]"></input>
                            </div>
							<br><br></div>`);
        }
    });

    $('#tambahJawaban').click(function() {
        if (jawaban != 5) {
            jawaban++;
            $('#jawaban').append(`
            <div id="jawaban${jawaban}">
		<div class="card" style="background-color: #e3e3e3; padding: 10px;">
                                    <h2 class="card-inside-title">Jawaban ${jawaban}</h2>
                                    <textarea id="jawaban${jawaban}" class="form-control" required="" name="jawaban[${jawaban}]" rows="3"></textarea>
                                </div>
							<br><br></div>`);
        }
    });

    $('#hapusPertanyaan').click(function() {
        if (pertanyaan != 1) {
            var element = document.getElementById('pertanyaan' + pertanyaan);
            element.remove();
            pertanyaan--;
        }

    });


    $('#hapusJawaban').click(function() {
        if (jawaban != 1) {
            var element = document.getElementById('jawaban' + jawaban);
            element.remove();
            jawaban--;
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
