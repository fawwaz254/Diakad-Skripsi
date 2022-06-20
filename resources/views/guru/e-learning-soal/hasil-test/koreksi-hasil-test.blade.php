<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-pink">
                    <h2>
                        Koreksi Hasil Test
                    </h2>
                </div>
                <div class="body">
                    <form class="form-validation" method="POST" id="form-validation"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/hasil-test/koreksi') }}">
                        {{ csrf_field() }}
                        @php
                            $nomor = 1;
                        @endphp
                        <input type="hidden" name="id_paket_soal" value="{{ $id_paket_soal }}">
                        @foreach ($questions as $question)
                            <hr>
                            <p>Soal no. {{ $nomor++ }}</p>
                            <input type="hidden" name="id_jawaban_test[]" value="{{ $question->id_jawaban_test }}">
                            <div
                                style="background:white; border: 1px solid #ccc; border-radius: 4px; display: block; padding: 9.5px; margin-bottom: 10px;">
                                {!! $question->soal->content !!}
                            </div>
                            <p>Jawaban</p>
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <textarea id="q1" class="form-control" name="soal" data-sample-short disabled>{!! $question->jawaban_essay !!}</textarea>
                                </div>
                            </div>
                            <p>Nilai</p>
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <input type="number" class="form-control"
                                        name="nilai[{{ $question->id_jawaban_test }}]" required>
                                </div>
                            </div>
                        @endforeach
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-pink waves-effect" id="btn-submit"
                                    type="submit">Save</button>
                            </div>
                        </div>
                    </form>
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
@include('scriptjs')
