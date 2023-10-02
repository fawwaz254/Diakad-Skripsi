<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-pink">
                    <h2>
                        Jawablah soal di bawah
                    </h2>
                </div>
                <div class="body">
                    <p><b>Nomor Soal {{ $no }}</b></p>
                    @if (strpos($detailPaketSoal->soal->content, '.mp3') || strpos($detailPaketSoal->soal->content, '.MP3'))
                        <audio controls>
                            <source src="{{ $detailPaketSoal->soal->text }}" type="audio/ogg">
                        </audio>
                    @else
                        <pre
                            style="background:white; border: 1px solid #ccc; border-radius: 4px; display: block; padding: 9.5px; margin-bottom: 10px; white-space: pre-wrap;
                    word-wrap: break-word;">{!! $detailPaketSoal->soal->content !!}</pre>
                    @endif
                    <div class="row clearfix">
                        <form id="question-form" class="form-validation" method="POST" enctype="multipart/form-data"
                            action="{{ url('siswa/e-learning-soal/list-ujian/test/answer') }}">
                            <input type="hidden" name="question" value="{{ $detailPaketSoal->soal->id_soal }}">
                            <input type="hidden" name="no" value="{{ $no }}">
                            <input type="hidden" name="id_tipe_soal"
                                value="{{ $detailPaketSoal->soal->id_tipe_soal }}">
                            <input type="hidden" name="paket_soal" value="{{ $detailPaketSoal->id_paket_soal }}">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                {{ csrf_field() }}
                                @if ($detailPaketSoal->soal->id_tipe_soal == 1)
                                    <div class="demo-radio-button">
                                        @foreach ($detailPaketSoal->soal->pilihan_soal as $no_option => $question_option)
                                            {{-- <input type="hidden" name="{{ $detailPaketSoal->soal_id_tipe_soal }}"> --}}
                                            @if (empty($jawabanTest))
                                                <input name="question_option" type="radio"
                                                    id="radio_{{ $no_option }}"
                                                    value="{{ $question_option->id_pilihan_soal }}" required>
                                                <label for="radio_{{ $no_option }}">
                                                    <pre class="is-answer">{!! $question_option->content !!}</pre>
                                                </label>
                                            @else
                                                @if ($jawabanTest == $question_option->id_pilihan_soal)
                                                    <input name="question_option" type="radio" checked=""
                                                        id="radio_{{ $no_option }}"
                                                        value="{{ $question_option->id_pilihan_soal }}">
                                                    <label for="radio_{{ $no_option }}">
                                                        <pre class="is-answer " style="background-color: #CFE795;">{!! $question_option->content !!}</pre>
                                                    </label>
                                                @else
                                                    <input name="question_option" type="radio"
                                                        id="radio_{{ $no_option }}"
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
                                    {{-- <input type="hidden" name="id_tipe_soal"
                                        value="{{ $detailPaketSoal->soal->id_tipe_soal }}"> --}}
                                @elseif($detailPaketSoal->soal->id_tipe_soal == 3)
                                    <label>Jawaban File ( pdf, ppt, docx, xlsx | max 10 mb )</label>
                                    <input type="file" class="form-control" name="file" required=""
                                        @if (!empty($jawabanTest)) style="background-color: #CFE795;" @endif
                                        aria-required="true" aria-invalid="true"
                                        accept=".pdf, .doc, .docx, .ppt, .xlsx">
                                @elseif($detailPaketSoal->soal->id_tipe_soal == 4)
                                    <div class="demo-radio-button">
                                        @foreach ($detailPaketSoal->soal->pilihan_soal as $no_option => $question_option)
                                            {{-- <input type="hidden" name="{{ $detailPaketSoal->soal_id_tipe_soal }}"> --}}
                                            @if (empty($jawabanTest))
                                                <input name="question_option[{{ $no_option }}]" type="checkbox"
                                                    id="checkbox_{{ $no_option }}"
                                                    value="{{ $question_option->id_pilihan_soal }}">
                                                <label for="checkbox_{{ $no_option }}">
                                                    <pre class="is-answer">{!! $question_option->content !!}</pre>
                                                </label>
                                            @else
                                                @if (in_array($question_option->id_pilihan_soal, $jawabanTest))
                                                    <input name="question_option[{{ $no_option }}]" type="checkbox"
                                                        checked="" id="checkbox_{{ $no_option }}"
                                                        value="{{ $question_option->id_pilihan_soal }}">
                                                    <label for="checkbox_{{ $no_option }}">
                                                        <pre class="is-answer " style="background-color: #CFE795;">{!! $question_option->content !!}</pre>
                                                    </label>
                                                @else
                                                    <input name="question_option[{{ $no_option }}]" type="checkbox"
                                                        id="checkbox_{{ $no_option }}"
                                                        value="{{ $question_option->id_pilihan_soal }}">
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
                                    {{-- <input type="hidden" name="id_tipe_soal"
                                        value="{{ $detailPaketSoal->soal->id_tipe_soal }}"> --}}
                                @elseif($detailPaketSoal->soal->id_tipe_soal == 6)
                                    {{-- <input type="hidden" name="{{ $detailPaketSoal->soal_id_tipe_soal }}"> --}}
                                    <br><br>
                                    <div class="row clearfix">
                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                            <div id="pertanyan">
                                                @foreach ($detailPaketSoal->soal->pilihan_pertanyaan as $no_option => $question_option)
                                                    <div class="card"
                                                        style="background-color: #e3e3e3; padding: 10px; box-shadow:none">
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
                                                                    name="jawaban[{{ $question_option->nomer }}]"
                                                                    required>
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
                                                                                @if ($jawabanTest[$no_option + 1] == $jawaban->nomer) selected @endif>
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
                                                    <div class="card"
                                                        style="background-color: #e3e3e3; padding: 10px; box-shadow:none">
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
                                                    <div class="card"
                                                        style="background-color: #e3e3e3; padding: 10px; box-shadow:none">
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
                                                                    name="jawaban[{{ $question_option->nomer }}]"
                                                                    required>

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
                                                                            @if ($jawabanTest[$no_option + 1] == '1') selected @endif>
                                                                            True
                                                                        </option>
                                                                        <option value="0"
                                                                            @if ($jawabanTest[$no_option + 1] == '0') selected @endif>
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
    </div>
</div>
@include('scriptjs')
<script>
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
</script>
