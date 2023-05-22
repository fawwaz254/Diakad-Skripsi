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
                    @foreach ($question_package_details as $question_package_detail)
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
                                @else
                                    <label>Jawaban File ( pdf, ppt, docx, xlsx | max 10 mb )</label>
                                    <input type="file" class="form-control" name="file[]" required=""
                                        aria-required="true" aria-invalid="true"
                                        accept=".pdf, .doc, .docx, .ppt, .xlsx">
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
