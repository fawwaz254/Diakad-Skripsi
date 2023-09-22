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

                    <pre>{!! $question->content !!}</pre>

                </div>
                <div class="row clearfix">
                    <div class="ol-lg-6 col-md-6 col-sm-12 col-xs-12" id="place_file">
                        <label>Jawaban File ( pdf, ppt, docx, xlsx | max 10 mb )</label>
                        <input type="file" class="form-control" name="file[]" required="" aria-required="true"
                            aria-invalid="true" accept=".pdf, .doc, .docx, .ppt, .xlsx"
                            style="background-color: #CFE795">
                    </div>
                </div>
                {{-- <h2 class="card-inside-title">Jawaban</h2>
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <input  class="form-control"  disabled>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</div>
</div>

</div>
</div>
