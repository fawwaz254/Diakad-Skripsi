<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#utility/pembayaran-siswa/view-detail-siswa/'.$siswa->nis_siswa.'/'.$nis_nama_siswa_asli)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        DISKON TAGIHAN PEMBAYARAN SISWA
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                            <tr>
                                <th colspan="2" style="text-align: center;">TAGIHAN SISWA</th>
                            </tr>
                            <tr>
                                <td style="width: 50%">Nama Lengkap</td>
                                <td style="width: 50%">{{strtoupper($siswa->nm_pengguna)}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">NIS</td>
                                <td style="width: 50%">{{$tagihan->nis_siswa}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Kelas</td>
                                <td style="width: 50%">{{$siswa->nm_kelas}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Biaya Sekolah</td>
                                <td style="width: 50%">{{$tagihan->nm_kelompok_biaya." (".$tagihan->tahun_ajaran." ".$tagihan->nm_semester.")"}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Jalur</td>
                                <td style="width: 50%">{{$tagihan->nm_jalur}}</td>
                            </tr><tr>
                                <td style="width: 50%">Nama Biaya</td>
                                <td style="width: 50%">{{$tagihan->nm_biaya}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Keterangan Biaya</td>
                                <td style="width: 50%">{{$tagihan->keterangan_biaya}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Jenis Biaya</td>
                                <td style="width: 50%">{{$jenis_biaya}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Besar Tagihan</td>
                                <td style="width: 50%">Rp{{number_format($tagihan->besar_biaya)}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Denda Tagihan</td>
                                <td style="width: 50%">Rp{{number_format($tagihan->denda_biaya)}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Potongan Tagihan</td>
                                <td style="width: 50%">Rp{{number_format($tagihan->total_potongan)}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Keterangan</td>
                                <td style="width: 50%">{{$tagihan->keterangan}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Besar Pembayaran</td>
                                <td style="width: 50%">Rp{{number_format($tagihan->besar_pembayaran)}}</td>
                            </tr>
                        </table>
                    </div>

                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-pembayaran-siswa/diskon/'.$tagihan->id_tagihan_biaya)}}">
                        {{csrf_field()}}
                        <div class="row clearfix m-t-20">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="">
                                    Besar Potongan
                                </label>
                                <input type="number" class="form-control" name="besar_potongan" required="" aria-required="true" aria-invalid="true" value="{{ $tagihan->total_potongan }}">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="">
                                    Tanggal Pemberian Potongan
                                </label>
                                <input type="text" class="datepicker form-control" name="tgl_potongan" required="" aria-required="true" aria-invalid="true" value="{{ $tagihan->tanggal_potongan ? date_format(date_create($tagihan->tgl_potongan), 'd F Y H:i:s') : null }}">
                            </div>
                        </div>
                        @if($detail_biaya_internal->isNotEmpty())
                        <h5>Detail Biaya Internal</h5>
                        <hr>
                        @foreach($detail_biaya_internal as $x)
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="">
                                    {{ $x->nm_detail_biaya_internal }} <br>
                                    <small>Nominal : Rp {{ number_format($x->besar_biaya) }}</small>
                                </label>
                                <input type="text" class="form-control" name="{{ 'potongan_internal[' . $x->id_detail_biaya_internal . ']' }}" required="" aria-required="true" aria-invalid="true" value="{{ $detail_potongan_biaya->where('id_detail_biaya_internal', $x->id_detail_biaya_internal)->first()->potongan_biaya ?? 0 }}">
                            </div>
                        </div>
                        @endforeach
                        <hr>
                        @endif
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="hidden" class="form-control" name="nis_siswa" required="" aria-required="true" aria-invalid="true" value="{{$siswa->nis_siswa}}">
                                <input type="hidden" class="form-control" name="nis_nama_siswa_asli" required="" aria-required="true" aria-invalid="true" value="{{$nis_nama_siswa_asli}}">
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
<script>
$(function(){    
    $('.datepicker').bootstrapMaterialDatePicker({
        format: 'DD MMMM YYYY HH:mm:00',
        //lang : 'id',
        clearButton: true,
        weekStart: 1,
        time: true
    });

});

</script>