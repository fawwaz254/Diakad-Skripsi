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
                    <div style="background:white; border: 1px solid #ccc; border-radius: 4px; display: block; padding: 9.5px; margin-bottom: 10px;">
                        {!!$question->content!!}
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            {{csrf_field()}}
                            <div class="demo-radio-button">
                            @foreach($question_options as $no_option => $question_option)
                            @if(empty($test_answer->id_pilihan_soal))
                                <input name="question_option" type="radio" id="radio_{{$no_option}}" value="{{$question_option->id_pilihan_soal}}">
                                <label for="radio_{{$no_option}}"><pre>{!!$question_option->content!!}</pre></label>
                            @else
                                @if($test_answer->id_pilihan_soal == $question_option->id_pilihan_soal)
                                <input name="question_option" type="radio" checked="" id="radio_{{$no_option}}" value="{{$question_option->id_pilihan_soal}}">
                                <label for="radio_{{$no_option}}"><pre>{!!$question_option->content!!}</pre></label>
                                @else
                                <input name="question_option" type="radio" id="radio_{{$no_option}}" value="{{$question_option->id_pilihan_soal}}">
                                <label for="radio_{{$no_option}}"><pre>{!!$question_option->content!!}</pre></label>
                                @endif
                            @endif
                                <br>
                            @endforeach
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-green waves-effect">Simpan jawaban</button>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-pink waves-effect">Hapus jawaban</button>
                        </div>
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
                            <a type="button" class="btn bg-pink btn-circle waves-effect waves-circle waves-float">
                                1
                            </a>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <button type="button" class="btn btn-block bg-cyan waves-effect">
                                Selesai mengerjakan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>