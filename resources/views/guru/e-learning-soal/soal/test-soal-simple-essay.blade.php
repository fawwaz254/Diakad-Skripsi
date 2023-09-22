<div class="container-fluid">
    <div class="row clearfix">
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
                        <h2 class="card-inside-title">Soal</h2>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                @if (strpos($question->content, '.mp3') || strpos($question->content, '.MP3'))
                                    <audio controls autoplay>
                                        <source src="{{ $question->text }}" type="audio/ogg">
                                    </audio>
                                @else
                                    <pre
                                        style="background:white; border: 1px solid #ccc; border-radius: 4px; display: block; padding: 9.5px; margin-bottom: 10px; white-space: pre-wrap;
                            word-wrap: break-word;">{!! $question->content !!}</pre>
                                @endif

                            </div>
                        </div>
                        <h2 class="card-inside-title">Alternatif Jawaban 1</h2>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <pre>{!! $question->alternatif_jawaban1 !!}</pre>
                            </div>
                        </div>
                        <h2 class="card-inside-title">Alternatif Jawaban 2</h2>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <pre>{!! $question->alternatif_jawaban2 !!}</pre>
                            </div>
                        </div>
                        <h2 class="card-inside-title">Alternatif Jawaban 3</h2>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <pre>{!! $question->alternatif_jawaban3 !!}</pre>
                            </div>
                        </div>
                        <h2 class="card-inside-title">Alternatif Jawaban 4</h2>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <pre>{!! $question->alternatif_jawaban4 !!}</pre>
                            </div>
                        </div>
                        <h2 class="card-inside-title">Alternatif Jawaban 5</h2>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <pre>{!! $question->alternatif_jawaban5 !!}</pre>
                            </div>
                        </div>


                        <h2 class="card-inside-title">Jawaban</h2>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <input class="form-control" disabled style="background-color: #CFE795">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
