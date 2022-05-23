<div class="container-fluid">
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
                    @foreach($question_package_details as $question_package_detail)
                    @php
                        $question = $question_package_detail->soal;
                        $question_options = $question->pilihan_soal;
                    @endphp
                    <hr>
                    <p>Soal no. {{$nomor++}}</p>
                    <div style="background:white; border: 1px solid #ccc; border-radius: 4px; display: block; padding: 9.5px; margin-bottom: 10px;">
                        {!!$question->content!!}
                     
                    
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            {{csrf_field()}}
                            <div class="demo-radio-button">
                            @foreach($question_options as $no_option => $question_option)
                      
                                <input name="question_option" type="radio" id="radio_{{$no_option}}" value="{{$question_option->id_pilihan_soal}}">
                                <label for="radio_{{$no_option}}"><pre @if($question_option->correct == 1) style="background-color: #CFE795;" @endif>{!!$question_option->content!!}</pre></label>
                                <br>
                            @endforeach
                            </div>
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