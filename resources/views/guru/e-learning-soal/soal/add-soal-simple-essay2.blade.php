<div class="block-header">
    <h2><a type="button" class="btn bg-grey waves-effect"
            href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/paket-soal/detail/' . $paket_soal->id_paket_soal) }}">
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
        <span style="background-color: white;padding:7px;border: 1px solid black;">
            <input type="checkbox" id="wuswug" class="checkbox" checked>
            <label for="wuswug">Aktifkan Input Gambar / Rumus</label>
        </span>
    </h2>
</div>

<form class="form-validation" id="form-validation" method="POST"
    action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/paket-soal/input-soal/new') }}">
    {{ csrf_field() }}
    <input type="hidden" name="id_tipe_soal" value="5">
    <input type="hidden" name="id_kategori_soal" value="{{ $paket_soal->id_kategori_soal }}">
    <input type="hidden" name="id_paket_soal" value="{{ $paket_soal->id_paket_soal }}">
    <br>

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-pink">
                    <h2>
                        ESSAY SINGKAT
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
                            <textarea id="a0" class="form-control q1" required="" name="jawaban[1]" rows="3"></textarea>
                        </div>
                    </div>
                    <h2 class="card-inside-title">Alternatif Jawaban 2</h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <textarea id="a1" class="form-control q2" name="jawaban[2]" rows="3"></textarea>
                        </div>
                    </div>
                    <h2 class="card-inside-title">Alternatif Jawaban 3</h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <textarea id="a2" class="form-control q3" name="jawaban[3]" rows="3"></textarea>
                        </div>
                    </div>
                    <h2 class="card-inside-title">Alternatif Jawaban 4</h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <textarea id="a3" class="form-control q4" name="jawaban[4]" rows="3"></textarea>
                        </div>
                    </div>
                    <h2 class="card-inside-title">Alternatif Jawaban 5</h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <textarea id="a4" class="form-control q5" name="jawaban[5]" rows="3"></textarea>
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
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <button class="btn btn-block bg-blue waves-effect" id="btn-view" type="button">Preview</button>
        </div>
    </div>
</form>
<br>
<br>
<br>


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
    $(document).ready(function() {
        $('#btn-submit').attr('disabled', 'disabled');
        changeCkedior();
    })


    function changeCkedior() {
        var editor = CKEDITOR.replace('q1', options);
    }

    $('.checkbox').on('change', function() { // on change of state
        if (this.checked) // if changed state is "CHECKED"
        {
            var editor = CKEDITOR.replace('q1', options);
        } else {
            CKEDITOR.instances['q1'].destroy();
        }
    });


    $("#btn-view").click(function() {
        var checkbox = document.getElementById("wuswug");
        var isChecked = checkbox.checked;
        $('#btn-submit').removeAttr('disabled', 'disabled');

        if (isChecked) {
            checkbox.checked = !checkbox.checked;
            id = 'q' + 1;
            CKEDITOR.instances[id].destroy();
        }
        $('#modal').html('');
        var html = '<table  class="table">';
        var soal = $(`textarea[id="q1"]`).val();
        if (soal) {
            html += '<tr>';
            html += '<td style="text-align: center;">';
            html += 'Soal';
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
                var jawaban = $(`textarea[id="a${j}"]`).val();
                html += '<tr>';
                html += '<td >';
                html += '<pre style="white-space: pre-wrap; word-wrap: break-word;">' + jawaban +
                    '</pre>';
                html += '</td >';
                html += '</tr>';

            }
            html += '<tr>';
            html += '<td style="border: 1px solid pink;">';

            html += '</td >';
        }

        html += '</table>';
        $('#modal').html(html);
        $('#myModal').modal('show');
    });
</script>
