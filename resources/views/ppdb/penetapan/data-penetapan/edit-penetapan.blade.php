<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#penetapan/data-penetapan')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-lime">
                    <h2>
                        EDIT PENETAPAN
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-penetapan/edit/'.$data_penetapan->id_penetapan)}}">
                        {{csrf_field()}}

                        <!-- Mulai Isian untuk Tabel Penetapan -->
                        
                        <h2 class="card-inside-title">
                            Detail Info Penetapan
                        </h2>
                        <h2 class="card-inside-title">
                            Nama Penetapan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_penetapan" required="" aria-required="true" aria-invalid="true" value="{{$data_penetapan->nm_penetapan}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            No Surat
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nomor_sk_penetapan" required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal Penetapan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_penetapan" required="" aria-required="true" aria-invalid="true" value="{{$tgl_penetapan}}">
                            </div>
                        </div>

                       
                        <h2 class="card-inside-title">
                            Sidang Ke
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="periode" required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Status Aktif
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="is_aktif">]
                                    @if($data_penerimaan->is_aktif == 0)
                                        <option value="0" selected >Tidak Aktif</option>
                                    @else
                                        <option value="0">Tidak Aktif</option>
                                    @endif
                                    @if($data_penerimaan->is_aktif == 1)
                                        <option value="1" selected >Aktif</option>
                                    @else
                                        <option value="1">Aktif</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <!-- Akhir Isian untuk Edit Tabel Penetapan -->
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
<script>
$(function(){    
    $('.datepicker').bootstrapMaterialDatePicker({
        format: 'DD MMMM YYYY',
        //lang : 'id',
        clearButton: true,
        weekStart: 1,
        time: false
    });
    $('.datepicker-time').bootstrapMaterialDatePicker({
        format: 'DD MMMM YYYY HH:mm:00',
        //lang : 'id',
        clearButton: true,
        weekStart: 1,
        time: true
    });
});
</script>