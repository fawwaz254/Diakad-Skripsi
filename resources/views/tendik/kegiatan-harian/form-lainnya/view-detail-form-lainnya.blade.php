<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        DETAIL ISIAN FORM  {{$pengisian_kegiatan_harian->pengguna_pengisi->fullname()}} pada {{date_format(date_create($pengisian_kegiatan_harian->created_at), 'd M Y H:i')}}
                    </h2>
                </div>
                <div class="body">
                    <h4>
                        <b>Status: </b>
                            <span class="label" style="background-color: #{{$pengisian_kegiatan_harian->warna_keadaan}};">{{$pengisian_kegiatan_harian->status_to_text()}}</span>
                    </h4>
                    @foreach($data_pengisian_jawaban as $pengisian_jawaban)
                    <p>
                        <b>{{$pengisian_jawaban->pertanyaan->show_order}}. {{$pengisian_jawaban->pertanyaan->isi_pertanyaan}}</b>
                    </p>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label class="label" style="background-color: #{{$pengisian_jawaban->jawaban->warna_keadaan}};">{{$pengisian_jawaban->jawaban->isi_jawaban}}</label>
                            @if(!empty($pengisian_jawaban->isi_jawaban_text))
                            <input type="text" value="{{$pengisian_jawaban->isi_jawaban_text}}" readonly="">
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')