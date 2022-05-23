<div class="container-fluid">
    <div class="block-header">
        <h2><a type="button" class="btn bg-grey waves-effect" href="{{url(Request::segment(1).'#'.Request::segment(2).'/soal')}}">
            <i class="material-icons">keyboard_backspace</i>
            <span>Kembali</span>
        </a> 
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-pink">
                    <h2>
                        MANAGE QUESTION
                    </h2>
                    <div class="header-dropdown m-r-15" style="top:12px">
                        <a class="btn bg-orange waves-effect" href="{{url(Request::segment(1).'#'.Request::segment(2).'/soal/test/'.$item->id_soal)}}" target="_blank">
                            <i class="material-icons">open_in_new</i>
                            <span>To Test Page</span>
                        </a>
                    </div>
                </div>
                <div class="body">
                    <form class="form-validation" method="POST" id="form-validation"  action="{{url(Request::segment(1).'/'.Request::segment(2).'/soal/new')}}">
                        {{csrf_field()}}
                        <input type="hidden" name="id_soal" value="{{$item->id_soal}}">
                        <h2 class="card-inside-title">Soal</h2>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <textarea id="q1" class="form-control" name="soal" data-sample-short>{!!$item->content!!}</textarea>
                            </div>
                        </div>
                        @foreach($question_options as $i => $question_option)
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">Jawaban {{$i + 1}}</h2>
                                <input type="hidden" name="id_jawaban[]" value="{{$question_option->id_pilihan_soal}}">
                                <textarea id="a{{$i}}" class="form-control" name="jawaban[]">{!!$question_option->content!!}</textarea>
                            </div>
                        </div>
                        @endforeach
                        <h2 class="card-inside-title">Jawaban Benar</h2>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="jawaban_benar" required="">
                                    @foreach($question_options as $i => $question_option)
                                    <option value="{{$i}}" @if($question_option->correct == 1) selected="" @endif>Jawaban {{$i + 1}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-pink waves-effect"  id="btn-submit" type="submit">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')