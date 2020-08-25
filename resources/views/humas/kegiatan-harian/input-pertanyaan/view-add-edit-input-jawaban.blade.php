
<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
            href="{{url(Request::segment(1).'#'.Request::segment(2).'/'.Request::segment(3).'/'.Request::segment(4).'/detail/'.$id_kegiatan_harian_pertanyaan)}}">
                <i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        {{!empty($item)? 'EDIT' : 'TAMBAH'}} JAWABAN
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{url(Request::segment(1).'/'.Request::segment(2).'/'.Request::segment(3).'/'.Request::segment(4).'/'.$id_kegiatan_harian_pertanyaan.'/action')}}/{{!empty($item)? 'edit' : 'add'}}">
                        {{csrf_field()}}
                        <input type="hidden" name="id_kegiatan_harian_pertanyaan" value="{{$id_kegiatan_harian_pertanyaan}}">
                        @if(!empty($item))
                        <input type="hidden" name="id_kegiatan_harian_jawaban" value="{{$item->id_kegiatan_harian_jawaban}}">
                        @endif
                        <h2 class="card-inside-title">
                            Nomor Jawaban
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="show_order" required=""
                                    aria-required="true" aria-invalid="true" value="{{(!empty($item))? $item->show_order : '0'}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Isi Jawaban
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="isi_jawaban" required=""
                                    aria-required="true" aria-invalid="true" value="{{(!empty($item))? $item->isi_jawaban : ''}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Bobot
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="bobot_jawaban" required=""
                                    aria-required="true" aria-invalid="true" value="{{(!empty($item))? $item->bobot_jawaban : '0'}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Warna Keadaan (Reference <a href="https://colorhunt.co/" target="_blank">https://colorhunt.co/</a>)
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="warna_keadaan" required=""
                                    aria-required="true" aria-invalid="true" value="{{(!empty($item))? $item->warna_keadaan : ''}}">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                        class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')