<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#rapb/kategori-pengeluaran/sub/ket/'.$data_subkategori_rapb->id_kategori_rapb.'/'.$data_subkategori_rapb->id_subkategori_rapb)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        TAMBAH KETERANGAN SUB-KATEGORI PENGELUARAN <br> 
                        Kategori : {{ $data_subkategori_rapb->kode_kategori_rapb }} - {{ $data_subkategori_rapb->nm_kategori_rapb }} <br> 
                        Sub-Kategori : {{ $data_subkategori_rapb->kode_subkategori_rapb }} - {{ $data_subkategori_rapb->nm_subkategori_rapb }}
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-kategori-pengeluaran/add-ket-subkategori/'.$id_ket_subkategori_rapb)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Kode
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="kode_ket_subkategori_rapb" required="" aria-required="true" aria-invalid="true">
                                <input type="hidden" class="form-control" name="id_kategori_rapb" required="" aria-required="true" aria-invalid="true" value="{{$data_subkategori_rapb->id_kategori_rapb}}">
                                <input type="hidden" class="form-control" name="id_subkategori_rapb" required="" aria-required="true" aria-invalid="true" value="{{$data_subkategori_rapb->id_subkategori_rapb}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Keterangan Sub-Kategori
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_ket_subkategori_rapb" required="" aria-required="true" aria-invalid="true">
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