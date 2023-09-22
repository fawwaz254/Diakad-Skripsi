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
                        {{-- {{csrf_field()}} --}}
                        <div class="demo-radio-button">
                            @foreach ($question->pilihan_soal as $no_option => $question_option)
                                @if ($question_option->correct == 1)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            name="jawaban[1][{{ $question_option->id_pilihan_soal }}]"
                                            value="{{ $question_option->id_pilihan_soal }}"
                                            id="jawaban[1][{{ $question_option->id_pilihan_soal }}]">
                                        <label class="form-check-label"
                                            for="jawaban[1][{{ $question_option->id_pilihan_soal }}]">
                                            <pre style="background-color: #CFE795;">{!! $question_option->content !!}</pre>
                                        </label>
                                    </div>
                                @else
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            name="jawaban[1][{{ $question_option->id_pilihan_soal }}]"
                                            value="{{ $question_option->id_pilihan_soal }}"
                                            id="jawaban[1][{{ $question_option->id_pilihan_soal }}]">
                                        <label class="form-check-label"
                                            for="jawaban[1][{{ $question_option->id_pilihan_soal }}]">
                                            <pre>{!! $question_option->content !!}</pre>
                                        </label>
                                    </div>
                                @endif
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
