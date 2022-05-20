<div class="container-fluid">
    <div class="block-header">
        @if(!empty(Request::input('question_package_id')))
        <h2><a type="button" class="btn bg-grey waves-effect" href="{{url('organizer/question/package/detail/'.Request::input('question_package_id'))}}">
        @else
        <h2><a type="button" class="btn bg-grey waves-effect" href="{{url('organizer/question')}}">
        @endif
            <i class="material-icons">keyboard_backspace</i>
            <span>Back</span>
        </a> 
        &nbsp; &nbsp; &nbsp;
        MANAGE QUESTION
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-pink">
                    <h2>
                        MANAGE QUESTION
                    </h2>
                    <div class="header-dropdown m-r-15" style="top:12px">
                        <a class="btn bg-orange waves-effect" href="{{url('organizer/question/test/'.$item->question_id)}}" target="_blank">
                            <i class="material-icons">open_in_new</i>
                            <span>To Test Page</span>
                        </a>
                    </div>
                </div>
                <div class="body">
                    <form class="form-validation" method="POST" action="{{url('organizer/question/order/save')}}">
                        {{csrf_field()}}
                        <input type="hidden" name="question_id" value="{{$item->id_soal}}">
                        <h2 class="card-inside-title">Category</h2>
                        <label class="form-label">@if($item) {{$item->kategori_soal->nama}} @endif</label>
                        <h2 class="card-inside-title">Question</h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div style="background:white; border: 1px solid #ccc; border-radius: 4px; display: block; padding: 9.5px; margin-bottom: 10px;">
                                    {!!$item->content!!}
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <ul id="sortable">
                            @foreach($question_options as $i => $question_option)
                                <li>
                                    <input type="hidden" name="answer[]" value="{{$question_option->id_pilihan_soal}}">
                                    <pre>Answer {{$i + 1}}<br><label>{!!$question_option->content!!}</label></pre>
                                </li>
                            @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-pink waves-effect" type="submit">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>