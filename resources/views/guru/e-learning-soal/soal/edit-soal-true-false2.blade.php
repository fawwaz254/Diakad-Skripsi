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
                        <h2 class="card-inside-title">Penjelasan</h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <textarea id="soal" class="form-control is-editor" required="" name="soal" rows="3">{{$item->content}}</textarea>
                            </div>
                        </div>
                        <div class="row clearfix">
                            @php
                                $no = 1;
                            @endphp
                            @foreach($question_options as $question_option)
                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                <div id="pertanyan">
                                    <div class="card" style="background-color: #e3e3e3; padding: 10px;">
                                        <h2 class="card-inside-title">Pertanyaan {{$no}}</h2>
                                        <input type="hidden" name="id_pilihan_pertanyaan[]" value="{{ $question_option->id_pilihan_pertanyaan }}">
                                        <textarea id="q{{$no}}" class="form-control is-editor" required="" id="inputPertanyaan1" name="pertanyaan[]" rows="3">{{$question_option->text}}</textarea>
                                        <h2 class="card-inside-title">Jawaban</h2>
                                        <select class="form-control show-tick" id="noJawaban1" name="noJawaban[]"
                                            required="">
                                            <option {{$question_option->jawaban == 1? 'selected' : ''}} value="1">True</option>
                                            <option {{$question_option->jawaban == 0? 'selected' : ''}} value="0">False</option>
                                        </select>
                                    </div>
                                    <br>
                                    <br>
                                </div>
                            </div>
                            @php
                                $no++;
                            @endphp
                            @endforeach
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

<script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script>
    var options = {
        filebrowserImageBrowseUrl: 'laravel-filemanager?type=Images',
        filebrowserImageUploadUrl: 'laravel-filemanager/upload?type=Images&_token=',
        filebrowserBrowseUrl: 'laravel-filemanager?type=Files',
        filebrowserUploadUrl: 'laravel-filemanager/upload?type=Files&_token='
    };

    for (var i = 1; i < {{$no}}; i++) {
        id = 'q' + i;
        var editor = CKEDITOR.replace(id, options);
        
    }
</script>

<script>
    var primary_table = null;
    $('#form-validation').validate({
        rules: {
            'checkbox': {
                required: true
            },
            'gender': {
                required: true
            }
        },
        highlight: function(input) {
            $(input).parents('.form-group').addClass('error');
        },
        unhighlight: function(input) {
            $(input).parents('.form-group').removeClass('error');
        },
        errorPlacement: function(error, element) {
            $(element).parents('.form-group').append(error);
        },
        submitHandler: function(form) {
            for (var i = 1; i < {{$no}}; i++) {
                id = 'q' + i;
                CKEDITOR.instances[id].destroy();
            }
            
            $('button').attr('disabled', 'disabled');
            $.ajax({
                processData: false,  // Important!
                contentType: false,
                cache: false,
                url: form.action,
                type: form.method,
                data: new FormData($(form)[0]),
                success: function(response) {
                    if (response.status == 200) {
                        vex.dialog.alert(response.message);
                    } else if (response.status == 201) {
                        vex.dialog.alert(response.message);
                        window.location.href = response.link;
                    } else if (response.status == 202) {
                        vex.dialog.alert(response.message);
                        setTimeout(() => {
                            loadURI(response.path);
                        }, 2000);
                    } else if (response.status == 203) {
                        vex.dialog.alert(response.message);
                        primary_table.ajax.reload(null, false);
                    } else if (response.status == 204) {
                        loadURI(response.path);
                    } else if (response.status == 205) {
                        $('#modalMaster').modal('hide');
                        vex.dialog.alert(response.message);
                        primary_table.ajax.reload(null, false);
                    } else if (response.status == 300) {
                        vex.dialog.alert(response.message);
                    }
                },
                complete: function() {
                    $('button').removeAttr('disabled');
                }
            });
        }
    });
</script>