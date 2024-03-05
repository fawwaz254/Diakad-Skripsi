<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3)) }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>

    <form id="form-upload" method="POST"
        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/action-list-form/add/0') }}"
        enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="id_form" value="{{ $form->id_form }}">
        @foreach ($form->pertanyaan_form as $key => $pertanyaan_form)
            <input type="hidden" name="id_pertanyaan_form[{{ $key }}]"
                value="{{ $pertanyaan_form->id_pertanyaan_form }}">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <pre
                                style="background:white; border: 1px solid #ccc; border-radius: 4px; display: block; padding: 9.5px; margin-bottom: 10px; white-space: pre-wrap; word-wrap: break-word;">{{ $pertanyaan_form->nm_pertanyaan_form }}</pre>
                            <input type="hidden" name="jenis_pertanyaan[{{ $key }}]"
                                value="{{ $pertanyaan_form->jenis_pertanyaan }}">
                            @if ($pertanyaan_form->jenis_pertanyaan == '1')
                                <textarea class="form-control" name="jawaban_pertanyaan[{{ $key }}]" data-sample-short required></textarea>
                            @elseif($pertanyaan_form->jenis_pertanyaan == '2')
                                <input type="file" class="form-control"
                                    name="jawaban_pertanyaan[{{ $key }}]" aria-required="true"
                                    aria-invalid="true" required>
                            @elseif ($pertanyaan_form->jenis_pertanyaan == '3')
                                <div class="demo-radio-button">
                                    @foreach (json_decode($pertanyaan_form->options, true) as $options)
                                        <input name="jawaban_pertanyaan[{{ $key }}]" type="radio"
                                            id="radio_{{ $key }}_{{ $options }}"
                                            value="{{ $options }}" required>
                                        <label for="radio_{{ $key }}_{{ $options }}">
                                            <pre class="is-answer">{{ $options }}</pre>
                                        </label>
                                    @endforeach
                                </div>
                            @elseif($pertanyaan_form->jenis_pertanyaan == '4')
                                <div class="demo-checkbox-container">
                                    @foreach (json_decode($pertanyaan_form->options, true) as $key1 => $options)
                                        <div class="checkbox-option">
                                            <input name="jawaban_pertanyaan[{{ $key }}][{{ $key1 }}]"
                                                type="checkbox" id="checkbox_{{ $key1 }}_{{ $options }}"
                                                value="{{ $options }}">
                                            <label for="checkbox_{{ $key1 }}_{{ $options }}">
                                                <pre class="is-answer">{{ $options }}</pre>
                                            </label>
                                        </div>
                                    @endforeach
                                    {{-- Jika opsi lainnya diaktifkan, tambahkan input text untuk jawaban lainnya --}}
                                    @if ($pertanyaan_form->others == '1')
                                        <div class="others-option input-group">
                                            <input type="text" name="jawaban_lainnya[{{ $key }}]"
                                                placeholder="Masukkan jawaban lainnya...">
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <br>
        @endforeach
        <div class="row clearfix">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="body">
                        <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                class="material-icons">save</i><span>Save</span></button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- @if (empty($jawabanTest))
                        <input name="question_option" type="radio" id="radio_{{ $no_option }}"
                            value="{{ $question_option->id_pilihan_soal }}" required>
                        <label for="radio_{{ $no_option }}">
                            <pre class="is-answer">{!! $question_option->content !!}</pre>
                        </label>
                    @else
                        @if ($jawabanTest == $question_option->id_pilihan_soal)
                            <input name="question_option" type="radio" checked="" id="radio_{{ $no_option }}"
                                value="{{ $question_option->id_pilihan_soal }}">
                            <label for="radio_{{ $no_option }}">
                                <pre class="is-answer " style="background-color: #CFE795;">{!! $question_option->content !!}</pre>
                            </label>
                        @else
                            <input name="question_option" type="radio" id="radio_{{ $no_option }}"
                                value="{{ $question_option->id_pilihan_soal }}">
                            <label for="radio_{{ $no_option }}">
                                <pre class="is-answer">{!! $question_option->content !!}</pre>
                            </label>
                        @endif
                    @endif
                    <br>
                    @endforeach
                </div>
            @elseif($detailPaketSoal->soal->id_tipe_soal == 2)
                <h2 class="card-inside-title">Jawaban</h2>
                <textarea id="q1" class="form-control" name="jawaban_essay" data-sample-short
                    @if ($jawabanTest) style="background-color: #CFE795;" @endif>{{ $jawabanTest }}</textarea>
            @elseif($detailPaketSoal->soal->id_tipe_soal == 3)
                <label>Jawaban File ( pdf, ppt, docx, xlsx | max 10 mb )</label>
                <input type="file" class="form-control" name="file" required=""
                    @if (!empty($jawabanTest)) style="background-color: #CFE795;" @endif aria-required="true"
                    aria-invalid="true" accept=".pdf, .doc, .docx, .ppt, .xlsx">
            @elseif($detailPaketSoal->soal->id_tipe_soal == 4)
                <div class="demo-radio-button">
                    @foreach ($detailPaketSoal->soal->pilihan_soal as $no_option => $question_option)
                        @if (empty($jawabanTest))
                            <input name="question_option[{{ $no_option }}]" type="checkbox"
                                id="checkbox_{{ $no_option }}" value="{{ $question_option->id_pilihan_soal }}">
                            <label for="checkbox_{{ $no_option }}">
                                <pre class="is-answer">{!! $question_option->content !!}</pre>
                            </label>
                        @else
                            @if (in_array($question_option->id_pilihan_soal, $jawabanTest))
                                <input name="question_option[{{ $no_option }}]" type="checkbox" checked=""
                                    id="checkbox_{{ $no_option }}" value="{{ $question_option->id_pilihan_soal }}">
                                <label for="checkbox_{{ $no_option }}">
                                    <pre class="is-answer " style="background-color: #CFE795;">{!! $question_option->content !!}</pre>
                                </label>
                            @else
                                <input name="question_option[{{ $no_option }}]" type="checkbox"
                                    id="checkbox_{{ $no_option }}" value="{{ $question_option->id_pilihan_soal }}">
                                <label for="checkbox_{{ $no_option }}">
                                    <pre class="is-answer">{!! $question_option->content !!}</pre>
                                </label>
                            @endif
                        @endif
                        <br>
                    @endforeach
                </div>
            @elseif($detailPaketSoal->soal->id_tipe_soal == 5)
                <h2 class="card-inside-title">Jawaban Singkat</h2>
                <textarea id="q1" class="form-control" name="jawaban_essay" data-sample-short
                    @if ($jawabanTest) style="background-color: #CFE795;" @endif>{{ $jawabanTest }}</textarea>
            @elseif($detailPaketSoal->soal->id_tipe_soal == 6)
                <br><br>
                <div class="row clearfix">
                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                        <div id="pertanyan">
                            @foreach ($detailPaketSoal->soal->pilihan_pertanyaan as $no_option => $question_option)
                                <div class="card" style="background-color: #e3e3e3; padding: 10px; box-shadow:none">
                                    <div class="row clearfix">
                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                            <h2 class="card-inside-title">Pertanyaan
                                                {{ $no_option + 1 }}
                                            </h2>
                                            <pre
                                                style="background:white; border: 1px solid #ccc; border-radius: 4px; display: block; padding: 9.5px;white-space: pre-wrap;
                            word-wrap: break-word;">{!! $question_option->text !!}</pre>
                                        </div>
                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                            <h2 class="card-inside-title">Jawaban</h2>
                                            <select class="form-control show-tick"
                                                @if ($jawabanTest) style="background-color: #CFE795" @endif
                                                name="jawaban[{{ $question_option->nomer }}]" required>
                                                <option value="0">
                                                    Pilih
                                                </option>
                                                @foreach ($detailPaketSoal->soal->pilihan_jawaban as $jawaban)
                                                    @if (empty($jawabanTest))
                                                        <option value="{{ $jawaban->nomer }}">
                                                            {{ $jawaban->nomer }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $jawaban->nomer }}"
                                                            @if (isset($jawabanTest[$no_option + 1]) && $jawabanTest[$no_option + 1] == $jawaban->nomer) selected @endif>
                                                            {{ $jawaban->nomer }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <br>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                        <div id="jawaban">
                            @foreach ($detailPaketSoal->soal->pilihan_jawaban as $no_option => $question_option)
                                <div class="card" style="background-color: #e3e3e3; padding: 10px; box-shadow:none">
                                    <h2 class="card-inside-title">Jawaban
                                        {{ $question_option->nomer }}</h2>
                                    <pre
                                        style="background:white; border: 1px solid #ccc; border-radius: 4px; display: block; padding: 9.5px;  white-space: pre-wrap;
                    word-wrap: break-word;">{!! $question_option->text !!}</pre>
                                </div>
                                <br><br>
                            @endforeach
                        </div>
                    </div>
                </div>
            @elseif($detailPaketSoal->soal->id_tipe_soal == 7)
                <br><br>
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div id="pertanyan">
                            @foreach ($detailPaketSoal->soal->pilihan_pertanyaan as $no_option => $question_option)
                                <div class="card" style="background-color: #e3e3e3; padding: 10px; box-shadow:none">
                                    <div class="row clearfix">
                                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                            <h2 class="card-inside-title">Pertanyaan
                                                {{ $no_option + 1 }}
                                            </h2>
                                            <pre
                                                style="background:white; border: 1px solid #ccc; border-radius: 4px; display: block; padding: 9.5px;white-space: pre-wrap;
                        word-wrap: break-word;">{!! $question_option->text !!}</pre>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                            <h2 class="card-inside-title">Jawaban</h2>
                                            <select class="form-control show-tick"
                                                @if ($jawabanTest) style="background-color: #CFE795" @endif
                                                name="jawaban[{{ $question_option->nomer }}]" required>

                                                @if (empty($jawabanTest))
                                                    <option value="99" disabled selected>
                                                        Pilih
                                                    </option>
                                                    <option value="1">
                                                        True
                                                    </option>
                                                    <option value="0">
                                                        False
                                                    </option>
                                                @else
                                                    <option value="1"
                                                        @if (isset($jawabanTest[$no_option + 1]) && $jawabanTest[$no_option + 1] == '1') selected @endif>
                                                        True
                                                    </option>
                                                    <option value="0"
                                                        @if (isset($jawabanTest[$no_option + 1]) && $jawabanTest[$no_option + 1] == '0') selected @endif>
                                                        False
                                                    </option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <br>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                @endif
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <button class="btn btn-block bg-green waves-effect" type="submit">Simpan
                    jawaban</button>
            </div>
            </form>
        </div>
    </div>
</div>
</div>
<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
    <div class="card">
        <div class="header bg-brown">
            <h2>
                <i class="material-icons">access_alarm</i>
                <span id="timeleft">Waktu tersisa: -</span>
            </h2>
        </div>
    </div>
</div>

<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="margin-top: 20px">
    <div class="card">
        <div class="header bg-pink">
            <h2>
                Daftar soal
            </h2>
        </div>
        <div class="body">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    @foreach ($allDetailPaketSoal as $index => $soal)
                        @if ($index == $no)
                            <a type="button"
                                href="siswa#e-learning-soal/list-ujian/test/{{ $soal->id_paket_soal }}/{{ $index }}"
                                class="btn bg-red btn-circle waves-effect waves-circle waves-float"
                                style="pointer-events: none">
                                {{ $index }}
                            </a>
                        @elseif(session()->has($soal->id_paket_soal . '_jawaban' . $index))
                            <a type="button"
                                href="siswa#e-learning-soal/list-ujian/test/{{ $soal->id_paket_soal }}/{{ $index }}"
                                class="btn bg-blue btn-circle waves-effect waves-circle waves-float">
                                {{ $index }}
                            </a>
                        @else
                            <a type="button"
                                href="siswa#e-learning-soal/list-ujian/test/{{ $soal->id_paket_soal }}/{{ $index }}"
                                class="btn bg-success btn-circle waves-effect waves-circle waves-float">
                                {{ $index }}
                            </a>
                        @endif
                    @endforeach

                </div>
            </div>
            <div class="row clearfix">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <button type="button" onclick="endAction(this)"
                        class="btn btn-block bg-cyan waves-effect">Selesai</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div> --}}
</div>
{{-- @include('scriptjs') --}}

<script type="text/javascript">
    $('#form-upload').submit(function(e) {
        // alert('test');
        e.preventDefault();
    }).validate({
        highlight: function(input) {
            $(input).addClass('is-danger');
        },
        unhighlight: function(input) {
            $(input).removeClass('is-danger');
        },
        errorPlacement: function(error, element) {
            $(element).parents('.control').addClass('help').addClass('is-danger').append(error);
        },
        submitHandler: function(form) {
            $('button').attr('disabled', 'disabled');

            var formData = new FormData(form);

            setTimeout(() => {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    enctype: 'multipart/form-data',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
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
                        } else if (response.status == 300) {
                            vex.dialog.alert(response.message);
                        }
                    },
                    complete: function() {
                        $('button').removeAttr('disabled');
                    }
                });

            }, 1000);
        }
    });
</script>
{{-- <script>
    var id_paket_soal = '{{ $detailPaketSoal->id_paket_soal }}'
    var var_url = 'siswa/e-learning-soal/list-ujian/test/end';
    var timeout = 'e-learning-soal/list-ujian';
    var distance = '{{ $sisaWaktu }}';

    clearInterval(x);
    var x = setInterval(function() {
        var hours = Math.floor((distance % (1 * 60 * 60 * 24)) / (1 * 60 * 60));
        var minutes = Math.floor((distance % (1 * 60 * 60)) / (1 * 60));
        var seconds = Math.floor((distance % (1 * 60)) / 1);

        document.getElementById("timeleft").innerHTML = "Waktu tersisa: " + hours + "h " +
            minutes + "m " + seconds + "s ";

        if (distance <= 0) {
            clearInterval(x);
            loadURI(timeout);
        } else {
            distance--
        }
    }, 1000);

    function endAction(item) {
        clearInterval(x);
        var item = $(item);
        vex.dialog.confirm({
            message: 'Apakah yakin sudah selesai mengerjakan.??',
            callback: function(value) {
                if (value) {
                    $.ajax({
                        type: "POST",
                        url: var_url,
                        data: {
                            paket_soal: id_paket_soal
                        },
                        success: function(response) {
                            vex.dialog.alert(response.message);
                            setTimeout(() => {
                                loadURI(response.path);
                            }, 2000);
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText);
                        }
                    });
                } else {
                    item.prop('disabled', false);
                }
            }
        })
    }
</script> --}}
