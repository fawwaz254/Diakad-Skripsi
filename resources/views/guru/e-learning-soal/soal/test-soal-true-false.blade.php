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
                                            <select class="form-control show-tick" style="background-color: #CFE795"
                                                required>
                                                <option value="1"
                                                    @if ($question_option->jawaban == '1') selected @endif>
                                                    True
                                                </option>
                                                <option value="0"
                                                    @if ($question_option->jawaban == '0') selected @endif>
                                                    False
                                                </option>

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

            </div>
        </div>
    </div>
</div>

</div>
</div>
