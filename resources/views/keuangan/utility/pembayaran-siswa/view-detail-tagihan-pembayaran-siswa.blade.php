<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#utility/pembayaran-siswa/view-detail-siswa/'.$nis_siswa.'/'.$nis_nama_siswa_asli)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        DETAIL TAGIHAN PEMBAYARAN SISWA
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
                                <td style="width: 50%">{{$nis_siswa}}</td>
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

                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-pembayaran-siswa/add/1')}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Semester Bayar
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_semester_bayar">
                                    <option value="" disabled selected >-- Pilih Semester --</option>
                                    @foreach($data_semester as $data)
                                        @if($data->is_aktif_semester == 1)
                                            <option value="{{$data->id_semester}}" selected >{{$data->tahun_ajaran}} {{$data->nm_semester}} (Aktif)</option>
                                        @else
                                            <option value="{{$data->id_semester}}">{{$data->tahun_ajaran}} {{$data->nm_semester}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Besar Pembayaran
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="besar_pembayaran" required="" aria-required="true" aria-invalid="true" >
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal Pembayaran
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="datetime-local" class="form-control" name="tgl_pembayaran" required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <div class="card-inside-title">
                            Metode Pembayaran
                            <small class="form-text text-muted">
                                *Pembayaran dilakukan dengan metode cash/transfer
                            </small>
                        </div>
                        <div class="row clearfix" style="margin-bottom:28px">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:0px !important;">
                                <select class="form-control show-tick" name="metode_pembayaran" id="metode_pembayaran" >
                                    <option value="0">Manual (Cash)</option>
                                    <option value="1">Via Bank (Transfer)</option>
                                </select>
                            </div>
                        </div>

                        <div class="hidden" id="transfer-section">
                            <div class="card-inside-title">
                                Transfer Via Bank
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 via" style="margin-bottom:0px !important;">
                                    <select class="form-control show-tick" name="id_bank">
                                        <option value="" disabled selected >-- Pilih Bank --</option>
                                        @foreach($bank as $bnk)
                                            <option value="{{ $bnk->id_bank }}">{{ $bnk->nm_bank }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="card-inside-title">
                                Transfer Metode
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:0px !important;">
                                    <select class="form-control show-tick" name="id_bank_via">
                                        <option value="" disabled selected >-- Pilih Metode Transfer --</option>
                                        @foreach($bank_via as $via)
                                            <option value="{{ $via->id_bank_via }}">{{ $via->nm_bank_via }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="card-inside-title">
                                Nomor Transaksi
                                <small class="form-text text-muted">
                                    *Nomor transaksi transfer
                                </small>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:0px !important;">
                                    <input type="text" class="form-control" name="nomor_transaksi" aria-required="true" aria-invalid="true" >
                                </div>
                            </div>
                            <div class="card-inside-title">
                                Penarikan Dari Bank
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:0px !important;">
                                    <select class="form-control show-tick" name="is_tarik">
                                        <option value="0" >Belum Ditarik</option>
                                        <option value="1" >Sudah Ditarik</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <h2 class="card-inside-title">
                            Keterangan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="keterangan" required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="hidden" class="form-control" name="id_tagihan_biaya" required="" aria-required="true" aria-invalid="true" value="{{$tagihan->id_tagihan_biaya}}">
                                <input type="hidden" class="form-control" name="besar_pembayaran_lama" required="" aria-required="true" aria-invalid="true" value="{{($tagihan->besar_pembayaran==null?'0':$tagihan->besar_pembayaran)}}">
                                <input type="hidden" class="form-control" name="nis_siswa" required="" aria-required="true" aria-invalid="true" value="{{$nis_siswa}}">
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

$('#metode_pembayaran').on('change', function (e) {
        var optionSelected = $(this).find("option:selected");
        if($(this).val() == "1"){ 
            $('#transfer-section').removeClass('hidden');
        } else {
            $('#transfer-section').addClass('hidden');
        }
    });
</script>