<div class="block-header">
    <h2><a type="button" class="btn bg-grey waves-effect"
            href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/paket-soal/bank-soal') }}">
            <i class="material-icons">keyboard_backspace</i>
            <span>Kembali</span>
        </a>
        <span style="background-color: white;padding:7px;border: 1px solid black;">
            <input type="checkbox" id="wuswug" class="checkbox">
            <label for="wuswug">Aktifkan Input Gambar / Rumus</label>
        </span>
    </h2>
</div>

<form class="form-validation" id="form-validation" method="POST"
    action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/paket-soal/bank-soal/new') }}">
    {{ csrf_field() }}
    <input type="hidden" name="id_tipe_soal" value="7">
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
                        SOAL True False
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
                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                            <div id="pertanyan">
                                <div class="card" style="background-color: #e3e3e3; padding: 10px;">
                                    <h2 class="card-inside-title">Pertanyaan 1</h2>
                                    <textarea class="form-control" required="" id="inputPertanyaan1" name="pertanyaan[1]" rows="3"></textarea>
                                    <h2 class="card-inside-title">Jawaban</h2>
                                    <select class="form-control show-tick" id="noJawaban1" name="noJawaban[1]"
                                        required="">
                                        <option value="1">True</option>
                                        <option value="0">False</option>
                                    </select>
                                </div>
                                <br>
                                <br>
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
                                <button class="btn btn-danger btn-block" id="hapusPertanyaan" type="button"><i
                                        class="material-icons">delete</i>
                                    <pre>Pertanyaan </pre>
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
    var pertanyaan = 1;
    var options = {
        filebrowserImageBrowseUrl: 'laravel-filemanager?type=Images',
        filebrowserImageUploadUrl: 'laravel-filemanager/upload?type=Images&_token=',
        filebrowserBrowseUrl: 'laravel-filemanager?type=Files',
        filebrowserUploadUrl: 'laravel-filemanager/upload?type=Files&_token=',

    };

    $('.checkbox').on('change', function() {
        if (this.checked) {
            for (var i = 1; i <= pertanyaan; i++) {
                id = 'inputPertanyaan' + i;
                var editor = CKEDITOR.replace(id, options);
            }

            for (var i = 1; i <= jawaban; i++) {
                id = 'inputJawaban' + i;
                var editor = CKEDITOR.replace(id, options);
            }
        } else {
            for (var i = 1; i <= pertanyaan; i++) {
                id = 'inputPertanyaan' + i;
                CKEDITOR.instances[id].destroy();
            }

            for (var i = 1; i <= jawaban; i++) {
                id = 'inputJawaban' + i;
                CKEDITOR.instances[id].destroy();
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
                                <textarea id="inputPertanyaan${pertanyaan }" class="form-control" required="" name="pertanyaan[${pertanyaan }]" rows="3"></textarea>
                                <h2 class="card-inside-title">Jawaban</h2>
								<select class="form-control show-tick" id="noJawaban${pertanyaan}"
                                        name="noJawaban[${pertanyaan }]" required="">
                                        <option value="1">True</option>
                                        <option value="0">False</option>
                                    </select>
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
</script>
