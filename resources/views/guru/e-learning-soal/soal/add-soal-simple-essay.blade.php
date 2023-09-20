<div class="block-header">
    <h2><a type="button" class="btn bg-grey waves-effect"
            href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/paket-soal/bank-soal') }}">
            <i class="material-icons">keyboard_backspace</i>
            <span>Kembali</span>
        </a>
        <button type="button" id="add" class="btn bg-green waves-effect">
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
        </span>
    </h2>
</div>

<form class="form-validation" id="form-validation" method="POST"
    action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/paket-soal/bank-soal/new') }}">
    {{ csrf_field() }}
    <input type="hidden" name="id_tipe_soal" value="5">
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
                        1. SOAL ESSAY
                    </h2>
                </div>
                <div class="body">
                    <h2 class="card-inside-title">Soal</h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <textarea id="q1" class="form-control q1" required="" name="soal[1]" rows="3"></textarea>
                        </div>
                    </div>
                    <h2 class="card-inside-title">Alternatif Jawaban 1</h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <textarea id="q1" class="form-control q1" required="" name="jawaban[1]" rows="3"></textarea>
                        </div>
                    </div>
                    <h2 class="card-inside-title">Alternatif Jawaban 2</h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <textarea id="q2" class="form-control q2" name="jawaban[2]" rows="3"></textarea>
                        </div>
                    </div>
                    <h2 class="card-inside-title">Alternatif Jawaban 3</h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <textarea id="q3" class="form-control q3" name="jawaban[3]" rows="3"></textarea>
                        </div>
                    </div>
                    <h2 class="card-inside-title">Alternatif Jawaban 4</h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <textarea id="q4" class="form-control q4" name="jawaban[4]" rows="3"></textarea>
                        </div>
                    </div>
                    <h2 class="card-inside-title">Alternatif Jawaban 5</h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <textarea id="q5" class="form-control q5" name="jawaban[5]" rows="3"></textarea>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div id="place">
    </div>
    <br>
    <br>
    <div class="row clearfix">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <button class="btn btn-block bg-pink waves-effect" id="btn-submit" type="submit">Save</button>
        </div>
    </div>
</form>


@include('scriptjs')

<script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
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
    $('.checkbox').on('change', function() { // on change of state
        if (this.checked) // if changed state is "CHECKED"
        {
            for (var i = 1; i <= jumlah; i++) {
                id = 'q' + i;
                var editor = CKEDITOR.replace(id, options);

            }

        } else {
            for (var i = 1; i <= jumlah; i++) {
                id = 'q' + i;
                CKEDITOR.instances[id].destroy();

            }

        }
    });
</script>
<script>
    //untuk fungsi  add jumlah soal
    var jumlah = 1;
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
                        ${jumlah} . SOAL ESSAY
                    </h2>
                </div>
                <div class="body">

                    <h2 class="card-inside-title">Soal</h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <textarea id="q${jumlah}" class="form-control q${jumlah}" required="" name="soal[${jumlah}]" rows="3"></textarea>
                        </div>
                    </div>
                    <h2 class="card-inside-title">Kunci Jawaban (akan di tampilkan ketika koreksi jawaban)</h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <textarea id="q1" class="form-control q1" required="" name="jawaban[${jumlah}]" rows="3"></textarea>
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
</script>
