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
                                            <input type="hidden" name="{{ $detailPaketSoal->soal_id_tipe_soal }}">
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
                                    <input type="hidden" name="id_tipe_soal"
                                        value="{{ $detailPaketSoal->soal->id_tipe_soal }}">
                                @elseif($detailPaketSoal->soal->id_tipe_soal == 3)
                                    <label>Jawaban File ( pdf, ppt, docx, xlsx | max 10 mb )</label>
                                    <input type="file" class="form-control" name="file" required=""
                                        @if (!empty($jawabanTest)) style="background-color: #CFE795;" @endif
                                        aria-required="true" aria-invalid="true"
                                        accept=".pdf, .doc, .docx, .ppt, .xlsx">
                                @elseif($detailPaketSoal->soal->id_tipe_soal == 4)
                                    <div class="demo-radio-button">
                                        @foreach ($detailPaketSoal->soal->pilihan_soal as $no_option => $question_option)
                                            <input type="hidden" name="{{ $detailPaketSoal->soal_id_tipe_soal }}">
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
