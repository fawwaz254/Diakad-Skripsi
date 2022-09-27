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
                        action="{{url(Request::segment(1).'/'.Request::segment(2).'/'.Request::segment(3).'/action')}}/{{!empty($item)? 'edit/'.$item->id_kegiatan_harian : 'add/0'}}">
                        {{csrf_field()}}
                        @if(!empty($item))
                        <input type="hidden" name="id_kegiatan_harian" value="{{$item->id_kegiatan_harian}}">
                        @endif
                        <h2 class="card-inside-title">
                            Nama Kegiatan Harian
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_kegiatan_harian" required=""
                                    aria-required="true" aria-invalid="true" value="{{(!empty($item))? $item->nm_kegiatan_harian : ''}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Status Aktif
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="is_aktif">
                                    <option value="0" {{(!empty($item) && $item->is_aktif == 0)? 'selected' : ''}}>Non-Aktif</option>
                                    <option value="1" {{(!empty($item) && $item->is_aktif == 1)? 'selected' : ''}}>Aktif</option>
                                </select>
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