
            <div class="block-header">
                <h2>
                <h2><a type="button" class="btn bg-grey waves-effect" href="{{url(Request::segment(1).'#'.Request::segment(2).'/soal')}}">
                    <i class="material-icons">keyboard_backspace</i>
                    <span>Back</span>
                </a> 
                &nbsp; &nbsp; &nbsp;
                MANAGE QUESTION
                </h2>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header bg-pink">
                            <h2>
                                SOAL
                            </h2>
                        </div>
                        <div class="body">
                            <form class="form-validation" id="form-validation"  method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/soal/new')}}">
                                {{csrf_field()}}
                                <h2 class="card-inside-title">Category</h2>
                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <select class="form-control show-tick" name="kategori" required="">
                                            @foreach($question_categories as $question_category)
                                            <option value="{{$question_category->id_kategori_soal}}">{{$question_category->nama}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <h2 class="card-inside-title">Soal</h2>
                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <textarea id="q1" class="form-control" required="" name="soal" rows="3"></textarea>
                                    </div>
                                </div>
                                @for($i=0; $i < 4; $i++)
                                <h2 class="card-inside-title">Jawaban {{$i + 1}}</h2>
                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <textarea id="a{{$i}}" class="form-control" required="" name="jawaban[]"></textarea>
                                    </div>
                                </div>
                                @endfor
                                <h2 class="card-inside-title">True Answer</h2>
                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <select class="form-control show-tick" name="jawaban_benar" required="">
                                            @for($i=0; $i < 4; $i++)
                                            <option value="{{$i}}">Answer {{$i + 1}}</option>
                                            @endfor
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
