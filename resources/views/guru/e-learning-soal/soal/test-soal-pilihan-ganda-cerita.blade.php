<div class="block-header">
    <h2>
        <h2><a type="button" class="btn bg-grey waves-effect"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/paket-soal/bank-soal') }}">
                <i class="material-icons">keyboard_backspace</i>
                <span>Kembali</span>
            </a></h2>
</div>
<div class="row clearfix">
    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-pink">
                <h2>
                    Jawablah soal di bawah
                </h2>
            </div>
            <div class="body">
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
                        <div id="pertanyan">
                            @foreach ($question->pilihan_pertanyaan as $no_option => $question_option)
                                <div class="card" style="background-color: #e3e3e3; padding: 10px; box-shadow:none">
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <h2 class="card-inside-title">Pertanyaan
                                                {{ $no_option + 1 }}
                                            </h2>
                                            <pre
                                                style="background:white; border: 1px solid #ccc; border-radius: 4px; display: block; padding: 9.5px;white-space: pre-wrap;
        word-wrap: break-word;">{!! $question_option->text !!}</pre>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <h2 class="card-inside-title">Jawaban</h2>
                                            <div class="demo-radio-button">

                                                @foreach (json_decode($question_option->options) as $no_option => $option)
                                                    @if ($question_option->jawaban == $no_option)
                                                        <input name="option{{ $question_option->nomer }}" type="radio"
                                                            id="radio_{{ $question_option->nomer . $no_option }}"
                                                            value="{{ $no_option }}">
                                                        <label for="radio_{{ $question_option->nomer . $no_option }}">
                                                            <pre style="background-color: #CFE795;">{!! $option !!}</pre>
                                                        </label>
                                                    @else
                                                        <input name="option{{ $question_option->nomer }}" type="radio"
                                                            id="radio_{{ $question_option->nomer . $no_option }}"
                                                            value="{{ $no_option }}">
                                                        <label for="radio_{{ $question_option->nomer . $no_option }}">
                                                            <pre>{!! $option !!}</pre>
                                                        </label>
                                                    @endif
                                                    <br>
                                                @endforeach
                                            </div>

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

            </div>
        </div>
    </div>
</div>

</div>
</div>
