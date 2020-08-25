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
                        {{!empty($item)? 'EDIT' : 'TAMBAH'}} KEGIATAN HARIAN
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{url(Request::segment(1).'/'.Request::segment(2).'/'.Request::segment(3).'/action')}}/{{!empty($item)? 'edit' : 'add'}}">
                        {{csrf_field()}}
                        @if(!empty($item))
                        <input type="hidden" name="id_kegiatan_harian_pertanyaan" value="{{$item->id_kegiatan_harian_pertanyaan}}">
                        @endif
                        <h2 class="card-inside-title">
                            Kategori Pertanyaan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_kegiatan_harian_kategori" required="">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($data_kegiatan_harian_kategori as $data)
                                    <option value="{{$data->id_kegiatan_harian_kategori}}" {{(!empty($item) && $item->id_kegiatan_harian_kategori == $data->id_kegiatan_harian_kategori)? 'selected' : ''}}>{{$data->kegiatan_harian->nm_kegiatan_harian}} > {{$data->nm_kegiatan_harian_kategori}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nomor Pertanyaan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="show_order" required=""
                                    aria-required="true" aria-invalid="true" value="{{(!empty($item))? $item->show_order : '0'}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Isi Pertanyaan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="isi_pertanyaan" required=""
                                    aria-required="true" aria-invalid="true" value="{{(!empty($item))? $item->isi_pertanyaan : ''}}">
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