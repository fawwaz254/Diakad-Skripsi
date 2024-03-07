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
                                @if ($pertanyaan_form->others == '1')
                                    <div class="demo-radio-button">
                                        @foreach (json_decode($pertanyaan_form->options, true) as $options)
                                            <input name="jawaban_pertanyaan[{{ $key }}]" type="radio"
                                                id="radio_{{ $key }}_{{ $options }}"
                                                value="{{ $options }}" required>
                                            <label for="radio_{{ $key }}_{{ $options }}">
                                                <pre class="is-answer">{{ $options }}</pre>
                                            </label>
                                        @endforeach
                                        <input name="jawaban_pertanyaan[{{ $key }}]" type="radio"
                                            id="radio_{{ $key }}_lainnya" value="lainnya" required
                                            @if (isset($ans->jawaban) && !in_array($ans->jawaban, json_decode($pertanyaan_form->options, true))) checked @endif>
                                        <label for="radio_{{ $key }}_lainnya">
                                            <pre class="is-answer">Lainnya</pre>
                                        </label>
                                        <div class="others-option input-group">
                                            <input type="text" name="jawaban_lainnya[{{ $key }}]"
                                                placeholder="Masukkan jawaban lainnya...">
                                        </div>
                                    </div>
                                @else
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
                                @endif
                            @elseif($pertanyaan_form->jenis_pertanyaan == '4')
                                @if ($pertanyaan_form->others == '1')
                                    <div class="demo-checkbox-container">
                                        @foreach (json_decode($pertanyaan_form->options, true) as $key1 => $options)
                                            <div class="checkbox-option">
                                                <input
                                                    name="jawaban_pertanyaan[{{ $key }}][{{ $key1 }}]"
                                                    type="checkbox"
                                                    id="checkbox_{{ $key1 }}_{{ $options }}"
                                                    value="{{ $options }}">
                                                <label for="checkbox_{{ $key1 }}_{{ $options }}">
                                                    <pre class="is-answer">{{ $options }}</pre>
                                                </label>
                                            </div>
                                        @endforeach
                                        <input type="checkbox" id="checkbox_{{ $key1 }}_lainnya" value=""
                                            @if (isset($ans->jawaban) && !in_array($ans->jawaban, json_decode($pertanyaan_form->options, true))) checked @endif>
                                        <label for="checkbox_{{ $key1 }}_lainnya">
                                            <pre class="is-answer">Lainnya</pre>
                                        </label>
                                        <div class="others-option input-group">
                                            <input type="text" name="jawaban_lainnya[{{ $key }}]"
                                                placeholder="Masukkan jawaban lainnya...">
                                        </div>
                                    </div>
                                @else
                                    <div class="demo-checkbox-container">
                                        @foreach (json_decode($pertanyaan_form->options, true) as $key1 => $options)
                                            <div class="checkbox-option">
                                                <input
                                                    name="jawaban_pertanyaan[{{ $key }}][{{ $key1 }}]"
                                                    type="checkbox"
                                                    id="checkbox_{{ $key1 }}_{{ $options }}"
                                                    value="{{ $options }}">
                                                <label for="checkbox_{{ $key1 }}_{{ $options }}">
                                                    <pre class="is-answer">{{ $options }}</pre>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                {{-- <div class="demo-checkbox-container">
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
                                    @if ($pertanyaan_form->others == '1')
                                        <div class="others-option input-group">
                                            <input type="text" name="jawaban_lainnya[{{ $key }}]"
                                                placeholder="Masukkan jawaban lainnya...">
                                        </div>
                                    @endif
                                </div> --}}
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
    // Validate form on submission
    $('#form-upload').submit(function(e) {
        e.preventDefault(); // Prevent default form submission
    }).validate({
        highlight: function(input) {
            $(input).addClass('is-danger'); // Highlight input on validation error
        },
        unhighlight: function(input) {
            $(input).removeClass('is-danger'); // Remove highlight on valid input
        },
        errorPlacement: function(error, element) {
            $(element).parents('.control').addClass('help').addClass('is-danger').append(
                error); // Place error message near the input
        },
        submitHandler: function(form) {
            $('button').attr('disabled',
                'disabled'); // Disable submit button to prevent multiple submissions

            var formData = new FormData(form); // Create FormData object for form data

            // Submit form data via AJAX after a short delay
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
                        handleResponse(response); // Handle response from server
                    },
                    complete: function() {
                        $('button').removeAttr(
                            'disabled'
                        ); // Re-enable submit button after request completion
                    }
                });
            }, 1000);
        }
    });

    // Handle radio button change event
    $('input[type="radio"]').on('change', function() {
        var id = $(this).attr('id');
        var key = id.split('_')[1];
        var option = id.split('_').pop();

        // Show/hide 'Other' input based on radio button selection
        if (option == 'lainnya') {
            $(this).parents('.demo-radio-button').find('.others-option').show();
        } else {
            $(this).parents('.demo-radio-button').find('.others-option').hide();
            $('input[name="jawaban_pertanyaan[' + key + ']"]').val(option);
        }
    });

    // Handle checkbox change event
    $('input[type="checkbox"]').on('change', function() {
        var id = $(this).attr('id');
        var key = id.split('_')[1];
        var option = id.split('_').pop();

        // Show/hide 'Other' input based on checkbox selection
        if (option == 'lainnya') {
            if ($(this).is(':checked')) {
                $(this).parents('.demo-checkbox-container').find('.others-option').show();
            } else {
                $(this).parents('.demo-checkbox-container').find('.others-option').hide();
                $('input[name="jawaban_lainnya[' + key + ']"]').val(''); // Clear custom value when unchecked
            }
        } else {
            // Update hidden input value based on checkbox state
            var value = $(this).is(':checked') ? $(this).val() : '';
            $('input[name="jawaban_pertanyaan[' + key + '][' + option + ']"]').val(value);
        }
    });

    // Hide 'Other' input on page load
    $('.others-option').hide();

    // Function to handle AJAX response from server
    function handleResponse(response) {
        switch (response.status) {
            case 200:
                vex.dialog.alert(response.message);
                break;
            case 201:
                vex.dialog.alert(response.message);
                window.location.href = response.link;
                break;
            case 202:
                vex.dialog.alert(response.message);
                setTimeout(() => {
                    loadURI(response.path);
                }, 2000);
                break;
            case 203:
                vex.dialog.alert(response.message);
                primary_table.ajax.reload(null, false);
                break;
            case 204:
                loadURI(response.path);
                break;
            case 300:
                vex.dialog.alert(response.message);
                break;
            default:
                // Handle other status codes or errors
                break;
        }
    }
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
