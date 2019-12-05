<style>
    .card .card-inside-title {
        margin-top: 10px;
        margin-bottom: 10px;
    }
</style>
<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#pendaftaran/pembukaan-voucher/'.$penerimaan->id_penerimaan)}}"><i class="material-icons">keyboard_backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    <div class="header">
                        <h2>PEMBUKAAN NOMOR PENDAFTARAN - TAMBAH TARIF</h2>
                    </div>

                    <div class="body">
                        <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/pembukaan-voucher/'.$penerimaan->id_penerimaan.'/add')}}">
                            {{csrf_field()}}
                            <input name="id_penerimaan" type="hidden" value="{{$penerimaan->id_penerimaan}}">
                            <input name="id_semester" type="hidden" value="{{$penerimaan->id_semester}}">
                            <h2 class="card-inside-title">
                                Penerimaan
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="penerimaan"  aria-required="true" aria-invalid="true" value="{{$penerimaan->nm_penerimaan}}" disabled>
                                </div>
                            </div>

                            <h2 class="card-inside-title">
                                Status Tarif
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select id="status_tarif" class="form-control show-tick" name="status_tarif">
                                        <option value="">-</option>
                                        <option value="1">Semua Jurusan</option>
                                        <option value="2">Jurusan Khusus</option>
                                    </select>
                                </div>
                            </div>

                            <div id="jurusan" class="hide">
                            <h2 class="card-inside-title">
                                Jurusan
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="id_jurusan">
                                        @foreach($jurusan as $jur)
                                            <option value="{{$jur->id_jurusan}}">{{$jur->nm_jurusan}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            </div>

                            <h2 class="card-inside-title">
                                Nominal Tarif
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" class="form-control" name="tarif"  aria-required="true" aria-invalid="true" value="">
                                </div>
                            </div>

                            <h2 class="card-inside-title">
                                Deskripsi
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <textarea name="deskripsi" cols="30" rows="5" class="form-control" aria-required="true"></textarea>
                                </div>
                            </div>
                            
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <a class="btn bg-blue btn-block waves-effect target-link" href="{{url(Request::segment(1).'#pendaftaran/pembukaan-voucher/'.$penerimaan->id_penerimaan)}}"><i class="material-icons">cancel</i><span>Cancel</span></a>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<script>
$('#status_tarif').on('change', function (e) {
    var optionSelected = $(this).find("option:selected");
    if($(this).val() == "2"){ // jurusan khusus
        $('#jurusan').removeClass('hide');
    } else {
        $('#jurusan').addClass('hide');
    }
});
</script>