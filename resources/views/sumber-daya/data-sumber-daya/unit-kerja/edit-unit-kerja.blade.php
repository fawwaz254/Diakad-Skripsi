<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#data-sumber-daya/unit-kerja')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        EDIT UNIT KERJA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-unit-kerja/edit/'.$data_unit_kerja->id_unit_kerja)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Nama Unit Kerja
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_unit_kerja" required="" aria-required="true" aria-invalid="true"
                                    value="{{$data_unit_kerja->nm_unit_kerja}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Deskripsi
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="deskripsi_unit_kerja" required="" aria-required="true"
                                    aria-invalid="true" value="{{$data_unit_kerja->deskripsi_unit_kerja}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tipe Unit Kerja
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="tipe_unit_kerja">
                                    <option value="">-- Pilih Tipe Unit Kerja --</option>
                                    @if($data_unit_kerja->tipe_unit_kerja == 'PIMPINAN')
                                        <option value="PIMPINAN" selected >PIMPINAN</option>
                                        <option value="KEUANGAN">KEUANGAN</option>
                                        <option value="SARPRAS">SARPRAS</option>
                                    @elseif($data_unit_kerja->tipe_unit_kerja == 'KEUANGAN')
                                        <option value="PIMPINAN">PIMPINAN</option>
                                        <option value="KEUANGAN" selected >KEUANGAN</option>
                                        <option value="SARPRAS">SARPRAS</option>
                                    @elseif($data_unit_kerja->tipe_unit_kerja == 'SARPRAS')
                                        <option value="PIMPINAN">PIMPINAN</option>
                                        <option value="KEUANGAN">KEUANGAN</option>
                                        <option value="SARPRAS" selected >SARPRAS</option>
                                    @else
                                        <option value="PIMPINAN">PIMPINAN</option>
                                        <option value="KEUANGAN">KEUANGAN</option>
                                        <option value="SARPRAS">SARPRAS</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Unit Kerja Induk
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_unit_kerja_induk">
                                    <option value="">-- Pilih Unit Kerja --</option>
                                    @foreach($data_unit_kerja_induk as $data)
                                    @if($data->id_unit_kerja == $data_unit_kerja->id_unit_kerja_induk)
                                    <option value="{{$data->id_unit_kerja}}" selected>{{$data->nm_unit_kerja}}</option>
                                    @else
                                    <option value="{{$data->id_unit_kerja}}">{{$data->nm_unit_kerja}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Singkatan Unit
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_singkatan_unit" required="" aria-required="true"
                                    aria-invalid="true" value="{{$data_unit_kerja->nm_singkatan_unit}}">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')