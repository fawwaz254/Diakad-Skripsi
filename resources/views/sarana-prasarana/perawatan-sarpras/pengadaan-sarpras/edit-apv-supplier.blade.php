<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#perawatan-sarpras/pengadaan-sarpras/view-detail-supplier/'.$data_rpb_sarpras->id_rpb_sarpras)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        EDIT APV PENGADAAN SUPPLIER
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-pengadaan-sarpras/edit-apv-supplier/'.$data_rpb_sarpras_supplier->id_rpb_sarpras_supplier)}}">
                        {{csrf_field()}}
                        <div class="row clearfix">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    INFO PENGADAAN BARANG/SARPRAS
                                </h2>
                                <hr style="border: 3px solid black;">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Semester
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true" aria-invalid="true" value="{{ $data_rpb_sarpras->tahun_ajaran }} {{ $data_rpb_sarpras->nm_semester }}">
                                        <input type="hidden" class="form-control" name="id_rpb_sarpras" aria-required="true" aria-invalid="true" value="{{ $data_rpb_sarpras->id_rpb_sarpras }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Unit Kerja
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true"
                                        aria-invalid="true" value="{{ $data_rpb_sarpras->nm_unit_kerja }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Jenis Buku/Alat
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true"
                                        aria-invalid="true" value="{{ $data_rpb_sarpras->nm_jenis_buku_alat }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Buku/Alat
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true"
                                        aria-invalid="true" value="{{ $data_rpb_sarpras->nm_buku_alat }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Ruangan
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true"
                                        aria-invalid="true" value="{{ $data_rpb_sarpras->nm_ruangan }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Inventaris
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true"
                                        aria-invalid="true" value="{{ $data_rpb_sarpras->nm_inventaris_ruangan }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Harga Satuan
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true"
                                        aria-invalid="true" value="Rp{{ number_format($data_rpb_sarpras->harga_satuan_rpb_sarpras, 0) }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Qty
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true"
                                        aria-invalid="true" value="{{ $data_rpb_sarpras->qty_rpb_sarpras }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Tanggal Pengadaan
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="text" class="form-control" name="nm_mata_pelajaran" disabled="" aria-required="true" aria-invalid="true" value="{{ strftime("%A, %d %B %Y", strtotime($data_rpb_sarpras->tgl_rpb_sarpras)) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <hr style="border: 3px solid black;">
                            </div>
                        </div>

                        <h2 class="card-inside-title">
                            Supplier
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_supplier">
                                  <option value="" disabled selected >-- Pilih Supplier --</option>
                                    @foreach($data_supplier as $data)
                                        @if($data->id_supplier == $data_rpb_sarpras_supplier->id_supplier)
                                            <option value="{{$data->id_supplier}}" selected >{{ $data->nm_supplier }}  ({{ $data->cp_supplier_1 }})</option>
                                        @else
                                            <option value="{{$data->id_supplier}}">{{ $data->nm_supplier }}  ({{ $data->cp_supplier_1 }})</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Harga Supplier
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="harga_supplier" required="" aria-required="true" aria-invalid="true" value="{{$data_rpb_sarpras_supplier->harga_supplier}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Harga Penawaran
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="harga_penawaran" required="" aria-required="true" aria-invalid="true" value="{{$data_rpb_sarpras_supplier->harga_penawaran}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Qty Penawaran
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="qty_penawaran" required="" aria-required="true" aria-invalid="true" value="{{$data_rpb_sarpras_supplier->qty_penawaran}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Termin Penawaran
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="termin_penawaran" required="" aria-required="true" aria-invalid="true" value="{{$data_rpb_sarpras_supplier->termin_penawaran}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Harga Apv Supplier
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="harga_approve_supplier" required="" aria-required="true" aria-invalid="true" value="{{$data_rpb_sarpras_supplier->harga_approve_supplier}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Qty Apv Supplier <small>*Boleh Kosong</small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="qty_approve_supplier" required="" aria-required="true" aria-invalid="true" value="{{$data_rpb_sarpras_supplier->qty_approve_supplier}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Termin Apv Supplier <small>*Boleh Kosong</small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="termin_approve_supplier" required="" aria-required="true" aria-invalid="true" value="{{$data_rpb_sarpras_supplier->termin_approve_supplier}}">
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