<div class="block-header">
    <h2>
        <h2><a type="button" class="btn bg-grey waves-effect"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/soal') }}">
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

                        {!! $question->content  !!}
                        
                    </div>
                </div>
                <h2 class="card-inside-title">Jawaban</h2>
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <input  class="form-control"  disabled>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

</div>
</div>
