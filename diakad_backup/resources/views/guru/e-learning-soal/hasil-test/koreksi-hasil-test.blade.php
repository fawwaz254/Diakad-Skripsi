<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
                        <input type="hidden" name="id_pengguna" value="{{ $id_pengguna }}">
                        <input type="hidden" name="total_nilai" value="{{ $total_nilai }}">
                        Total Nilai Pilihan Ganda : {{  $total_nilai  }}
                        @foreach ($questions as $question)
                            <hr style="height:1px;border:none;color:#333;background-color:#333;">
                            <p>Soal no. {{ $nomor++ }}</p>
                            <input type="hidden" name="id_jawaban_test[]" value="{{ $question->id_jawaban_test }}">
                            <pre
                                style="background:white; border: 1px solid #ccc; border-radius: 4px; display: block; padding: 9.5px; margin-bottom: 10px;">{!! $question->soal->content !!}</pre>
                            <p>Jawaban</p>
                            @if ($question->id_tipe_soal == 2)
                                <textarea id="q1" class="form-control" name="soal" data-sample-short disabled>{!! $question->jawaban_essay !!}</textarea>
                            @else
                                @if ($question->type_file == 'pdf')
                                    <iframe
                                        src="https://diakad.sgp1.digitaloceanspaces.com/{{ $question->link_file }}"
                                        style="width:100%; height:535px;" frameborder="0"></iframe>
                                @else
                                    <iframe
                                        src='https://view.officeapps.live.com/op/embed.aspx?src=https://diakad.sgp1.digitaloceanspaces.com/{{ $question->link_file }}'
                                        style="width:100%;" height='535px' frameborder='0'></iframe>
                                @endif
                            @endif
                            <br>
                            <p>Nilai</p>
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <input type="number" class="form-control"
                                        name="nilai[{{ $question->id_jawaban_test }}]" required>
                                </div>
                            </div>
                            <p>Tangapan (Opsional)</p>
                            <div class="row clearfix">
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control"
                                        name="tangapan[{{ $question->id_jawaban_test }}]">
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
    </div>
</div>
@include('scriptjs')
