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
                        @php
                            $nomor = 1;
                        @endphp
                   
                        @foreach ($questions as $question)
                            <hr style="height:1px;border:none;color:#333;background-color:#333;">
                            <p>Soal no. {{ $nomor++ }}</p>
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
                                    <input type="number" class="form-control" disabled
                              value="{{ $question->nilai }}">
                                </div>
                            </div>
                            <p>Tangapan (Opsional)</p>
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" disabled
                                    value="{{ $question->tangapan }}">
                                </div>
                            </div>
                        @endforeach
                        
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
