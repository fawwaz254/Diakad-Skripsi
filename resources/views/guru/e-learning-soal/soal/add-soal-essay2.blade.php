<div class="block-header">
    <h2><a type="button" class="btn bg-grey waves-effect"
            href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/paket-soal/detail/' . $paket_soal->id_paket_soal) }}">
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
            <input type="checkbox" id="wuswug" class="checkbox" checked>
            <label for="wuswug">Aktifkan Input Gambar / Rumus</label>
        </span>
    </h2>
</div>

<form class="form-validation" id="form-validation" method="POST"
    action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/paket-soal/input-soal/new') }}">
    {{ csrf_field() }}
    <input type="hidden" name="id_tipe_soal" value="2">
    <input type="hidden" name="id_kategori_soal" value="{{ $paket_soal->id_kategori_soal }}">
    <input type="hidden" name="id_paket_soal" value="{{ $paket_soal->id_paket_soal }}">
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
                    <h2 class="card-inside-title">Kunci Jawaban (akan di tampilkan ketika koreksi jawaban)</h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <textarea class="form-control q1" required="" name="jawaban[1]" rows="3">-</textarea>
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
    var jumlah = 1;
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
        var checkbox = document.getElementById("wuswug");
        var isChecked = checkbox.checked;
        if (isChecked) // if changed state is "CHECKED"
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
    }


    $("#btn-view").click(function() {
        var checkbox = document.getElementById("wuswug");
        var isChecked = checkbox.checked;
        $('#btn-submit').removeAttr('disabled', 'disabled');

        if (isChecked) {
            checkbox.checked = !checkbox.checked;
            for (var i = 1; i <= jumlah; i++) {
                id = 'q' + i;
                CKEDITOR.instances[id].destroy();
                // for (var j = 0; j < 5; j++) {
                //     idjawaban = 'a' + i + j;
                //     CKEDITOR.instances[idjawaban].destroy();
                // }
            }
        }
        $('#modal').html('');
        var html = '<table  class="table">';
        for (var i = 1; i <= jumlah; i++) {
            var soal = $(`textarea[id="q${i}"]`).val();

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
                html += '<td style="border: 1px solid pink;">';
                html += '</td >';
            }
        }
        html += '</table>';
        $('#modal').html(html);

        $('#myModal').modal('show');


    });




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

    //untuk fungsi  add jumlah soal

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
                            <textarea id="q1" class="form-control q1" required="" name="jawaban[${jumlah}]" rows="3">-</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        `);
        }
        changeCkedior();
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
