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
                    <pre style="background:white; border: 1px solid #ccc; border-radius: 4px; display: block; padding: 9.5px; margin-bottom: 10px;">{!!$test->soal->content!!}</pre>
                    {{-- <div class="row clearfix"> --}}
                        <div class="row clearfix">
                            <form id="question-form" class="form-validation" method="POST" enctype="multipart/form-data"
                                action="{{ url('siswa/e-learning-soal/list-ujian/test/answer') }}">
                                <input type="hidden" name="question" value="{{ $test->soal->id_soal }}">
                                <input type="hidden" name="test" value="{{ $test->id_test }}">
                                <input type="hidden" name="no" value="{{ $no }}">
                                <input type="hidden" name="id_tipe_soal" value="{{ $test->soal->id_tipe_soal }}">
                                <input type="hidden" name="paket_soal" value="{{ $paket_soal->id_paket_soal }}">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    {{ csrf_field() }}
                                    @if ($test->soal->id_tipe_soal == 1)
                                        <div class="demo-radio-button">
                                            @foreach ($question_options as $no_option => $question_option)
                                                <input type="hidden" name="{{ $test->soal_id_tipe_soal }}">
                                                @if (empty($test->id_pilihan_soal))
                                                    <input name="question_option" type="radio"
                                                        id="radio_{{ $no_option }}"
                                                        value="{{ $question_option->id_pilihan_soal }}" required>
                                                    <label for="radio_{{ $no_option }}">
                                                        <pre class="is-answer">{!! $question_option->content !!}</pre>
                                                    </label>
                                                @else
                                                    @if ($test->id_pilihan_soal == $question_option->id_pilihan_soal)
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
                                    @elseif($test->soal->id_tipe_soal == 2)
                                    <h2 class="card-inside-title">Jawaban</h2>
                                        <textarea id="q1" class="form-control" name="jawaban_essay" data-sample-short @if(!empty($test->jawaban_essay ))  style="background-color: #CFE795;"@endif>{{ !empty($test) ? $test->jawaban_essay : '' }}</textarea>
                                        <input type="hidden" name="id_tipe_soal"
                                            value="{{ $test->soal->id_tipe_soal }}">
                                    @else
                                    <label>Jawaban File ( pdf, ppt, docx, xlsx | max 10 mb )</label>
                                    <input type="file" class="form-control" name="file" required=""
                                        aria-required="true" aria-invalid="true" accept=".pdf, .doc, .docx, .ppt, .xlsx">
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <button class="btn btn-block bg-green waves-effect" type="submit">Simpan
                                        jawaban</button>
                                </div>
                                {{-- <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <button class="btn btn-block bg-pink waves-effect" type="button"
                                        onclick="deleteAnswerAction()">Hapus jawaban</button>
                                </div> --}}
                            </form>
                        {{-- </div> --}}

                        {{-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            {{csrf_field()}}
                        <div class="demo-radio-button">
                            @foreach ($question_options as $no_option => $question_option)
                            @if (empty($test_answer->id_pilihan_soal))
                            <input name="question_option" type="radio" id="radio_{{$no_option}}"
                                value="{{$question_option->id_pilihan_soal}}">
                            <label for="radio_{{$no_option}}">
                                <pre>{!!$question_option->content!!}</pre></label>
                            @else
                            @if ($test_answer->id_pilihan_soal == $question_option->id_pilihan_soal)
                            <input name="question_option" type="radio" checked="" id="radio_{{$no_option}}"
                                value="{{$question_option->id_pilihan_soal}}">
                            <label for="radio_{{$no_option}}">
                                <pre>{!!$question_option->content!!}</pre></label>
                            @else
                            <input name="question_option" type="radio" id="radio_{{$no_option}}"
                                value="{{$question_option->id_pilihan_soal}}">
                            <label for="radio_{{$no_option}}">
                                <pre>{!!$question_option->content!!}</pre></label>
                            @endif
                            @endif
                            <br>
                            @endforeach
                        </div>
                    </div> --}}



                        {{-- <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-green waves-effect">Simpan jawaban</button>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-pink waves-effect">Hapus jawaban</button>
                        </div> --}}
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

                        {{-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            @php
                                $nomor = 1;
                            @endphp
                            @foreach ($paket_soal->detail_paket_soal->sortBy('nomor')->all() as $other_test_answer)
                            @if ($test->test->nomor == $other_test_answer->nomor)
                                <a type="button" href="{{url('test/question/'.$other_test_answer->id_soal)}}"
                    class="btn bg-amber btn-circle waves-effect waves-circle waves-float">
                    @else
                    @if (empty($other_test_answer->id_pilihan_jawaban))
                    <a type="button" href="{{url('test/question/'.$other_test_answer->id_soal)}}"
                        class="btn bg-pink btn-circle waves-effect waves-circle waves-float">
                        @else
                        <a type="button" href="{{url('test/question/'.$other_test_answer->id_soal)}}"
                            class="btn bg-green btn-circle waves-effect waves-circle waves-float">
                            @endif
                            @endif
                            {{$other_test_answer->nomor}}
                        </a>

                        @if ($nomor == 5)
                        @php
                        $nomor = 1;
                        @endphp
                        <br>
                        @else
                        @php
                        $nomor++;
                        @endphp
                        @endif
                        @endforeach
                </div> --}}


                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            @foreach ($jawabanTest->sortBy('nomer')->all() as $index => $soal)
                                @if ($soal->id_pilihan_soal != null || $soal->jawaban_essay != null)
                                    <a type="button"
                                        href="siswa#e-learning-soal/list-ujian/test/{{ $test->test->id_test }}/{{ $index + 1 }}"
                                        class="btn bg-pink btn-circle waves-effect waves-circle waves-float">
                                        {{ $index + 1 }}
                                    </a>
                                @else
                                    <a type="button"
                                        href="siswa#e-learning-soal/list-ujian/test/{{ $test->test->id_test }}/{{ $index + 1 }}"
                                        class="btn bg-success btn-circle waves-effect waves-circle waves-float">
                                        {{ $index + 1 }}
                                    </a>
                                @endif
                            @endforeach

                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <button type="button" onclick="endAction(this)" class="btn btn-block bg-cyan waves-effect">
                                Selesai mengerjakan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<script>
    var id_test = '{{ $test->id_test }}';
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
            //                 window.location = url; 
        } else {
            distance--
        }
    }, 1000);

    function endAction(item) {
        var item = $(item);
        vex.dialog.confirm({
            message: 'Apakah yakin sudah selesai mengerjakan.??',
            callback: function(value) {
                // alert(url)
                if (value) {
                    // alert(url);
                    $.ajax({
                        type: "POST",
                        url: var_url,
                        data: {
                            test: id_test
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

    //     vex.dialog.confirm({
    //         message: 'Apakah kamu yakin sudah selesai mengerjakan?',
    //         callback: function (value) {
    //             if(value){
    //                 var url = timeout;
    //                 window.location = url; 
    //             }
    //         }
    //     })
    // }

    // function deleteAnswerAction(){
    //     $('input[name=question_option]').prop('checked', false);
    //     $('#question-form').submit();
    // }
</script>
