<div class="container-fluid">
    <div class="block-header">
        <h2><a type="button" class="btn bg-grey waves-effect"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/paket-soal') }}">
                <i class="material-icons">keyboard_backspace</i>
                <span>Kembali</span>
            </a>
    </div>
    <div class="row clearfix">
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-pink">
                    <h2>
                        Detail Paket Soal
                    </h2>
                </div>
                <div class="body">
                    @php
                        $nomor = 1;
                    @endphp
                    @foreach ($item->detail_paket_soal as $question_package_detail)
                        @php
                            $question = $question_package_detail->soal;
                            $question_options = $question->pilihan_soal;
                        @endphp
                        <hr>
                        <p>Soal no. {{ $nomor++ }}</p>
                        @if (strpos($question->content, '.mp3') || strpos($question->content, '.MP3'))
                            <audio controls autoplay>
                                <source src="{{ $question->text }}" type="audio/ogg">
                            </audio>
                        @else
                            <pre
                                style="background:white; border: 1px solid #ccc; border-radius: 4px; display: block; padding: 9.5px; margin-bottom: 10px; white-space: pre-wrap;
                            word-wrap: break-word;">{!! $question->content !!}</pre>
                        @endif
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                @if ($question->id_tipe_soal == 1)
                                    <div class="demo-radio-button">
                                        @foreach ($question_options as $no_option => $question_option)
                                            <input name="question_option" type="radio" id="radio_{{ $no_option }}"
                                                value="{{ $question_option->id_pilihan_soal }}">
                                            <label for="radio_{{ $no_option }}">
                                                <pre @if ($question_option->correct == 1) style="background-color: #CFE795;" @endif>{!! $question_option->content !!}</pre>
                                            </label>
                                            <br>
                                        @endforeach
                                    </div>
                                @elseif($question->id_tipe_soal == 2)
                                    <textarea class="form-control" data-sample-short></textarea>
                                @elseif($question->id_tipe_soal == 3)
                                    <label>Jawaban File ( pdf, ppt, docx, xlsx | max 10 mb )</label>
                                    <input type="file" class="form-control" name="file[]" required=""
                                        aria-required="true" aria-invalid="true"
                                        accept=".pdf, .doc, .docx, .ppt, .xlsx">
                                @elseif($question->id_tipe_soal == 4)
                                    <div class="demo-radio-button">
                                        @foreach ($question->pilihan_soal as $no_option => $question_option)
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
                                @elseif($question->id_tipe_soal == 5)
                                    <textarea id="q1" class="form-control" name="jawaban_essay" data-sample-short></textarea>
                                @elseif($question->id_tipe_soal == 6)
                                    <div class="row clearfix">
                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                            <div id="pertanyan">
                                                @foreach ($question->pilihan_pertanyaan as $no_option => $question_option)
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
                                                                    name="jawaban[{{ $question_option->nomer }}]"
                                                                    required>
                                                                    <option value="0">
                                                                        Pilih
                                                                    </option>
                                                                    @foreach ($question->pilihan_jawaban as $jawaban)
                                                                        <option value="{{ $jawaban->nomer }}">
                                                                            {{ $jawaban->nomer }}
                                                                        </option>
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
                                                @foreach ($question->pilihan_jawaban as $no_option => $question_option)
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
                                @else
                                @endif
                            </div>
                        </div>
                    @endforeach
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
    </div>
</div>
