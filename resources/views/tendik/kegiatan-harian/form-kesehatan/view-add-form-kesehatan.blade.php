<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{url(Request::segment(1).'#'.Request::segment(2).'/'.Request::segment(3))}}">
                <i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        MENGISI FORM KESEHATAN
                    </h2>
                </div>
                <div class="body">
                    <h2>
                        <b>{{$kegiatan_harian->nm_kegiatan_harian}}</b>
                    </h2>
                    <form id="form-validation" method="POST"
                        action="{{url(Request::segment(1).'/'.Request::segment(2).'/'.Request::segment(3).'/action/add')}}">
                        {{csrf_field()}}
                        @foreach($data_kegiatan_harian_kategori as $kegiatan_harian_kategori)
                        <div class="box">
                        <h4><b>{{$kegiatan_harian_kategori->nm_kegiatan_harian_kategori}}</b></h4>
                            @foreach($kegiatan_harian_kategori->pertanyaan as $pertanyaan)
                            <p>
                                <b>{{$pertanyaan->show_order}}. {{$pertanyaan->isi_pertanyaan}}</b>
                            </p>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    @foreach($pertanyaan->jawaban->sortBy('show_order')->all() as $jawaban)
                                    <input class="with-gap radio-col-light-green form-control validate" type="radio" name="jawaban_pertanyaan[{{$pertanyaan->id_kegiatan_harian_pertanyaan}}]" value="{{$jawaban->id_kegiatan_harian_jawaban}}"
                                        id="jb_{{$jawaban->id_kegiatan_harian_jawaban}}" required="required" data-error="Error msg here">
                                    <label for="jb_{{$jawaban->id_kegiatan_harian_jawaban}}">{{$jawaban->isi_jawaban}}</label>
                                        @if(!empty($jawaban->placeholder))
                                        <input type="text" name="jawaban_text[{{$jawaban->id_kegiatan_harian_jawaban}}]" placeholder="{{$jawaban->placeholder}}">
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        @if(!$is_disabled)
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                        class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')