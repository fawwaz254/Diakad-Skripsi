<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#pengeluaran-sekolah/subkategori-pengeluaran')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-amber">
                    <h2>
                        EDIT SUB-KATEGORI PENGELUARAN
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-subkategori-pengeluaran/edit/'.$data_subkategori_pengeluaran->id_pengeluaran_biaya_subkategori)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Nama Kategori
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_pengeluaran_biaya_kategori">
                                    <option value="" disabled selected >-- Pilih Kategori --</option>
                                    @foreach($data_kategori_pengeluaran as $data)
                                        @if($data->id_pengeluaran_biaya_kategori == $data_subkategori_pengeluaran->id_pengeluaran_biaya_kategori)
                                            <option value="{{$data->id_pengeluaran_biaya_kategori}}" selected >{{$data->nm_pengeluaran_biaya_kategori}}</option>
                                        @else
                                            <option value="{{$data->id_pengeluaran_biaya_kategori}}">{{$data->nm_pengeluaran_biaya_kategori}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Sub-Kategori
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_pengeluaran_biaya_subkategori" required="" aria-required="true" aria-invalid="true" value="{{$data_subkategori_pengeluaran->nm_pengeluaran_biaya_subkategori}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Keterangan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="keterangan_pengeluaran_biaya_subkategori" required="" aria-required="true" aria-invalid="true" value="{{$data_subkategori_pengeluaran->keterangan_pengeluaran_biaya_subkategori}}">
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