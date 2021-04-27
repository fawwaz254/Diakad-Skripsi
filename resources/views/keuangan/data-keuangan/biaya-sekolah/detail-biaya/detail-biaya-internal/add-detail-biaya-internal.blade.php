<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#data-keuangan/biaya-sekolah/detail-biaya/detail-biaya-internal/'.Request::segment(7))}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        TAMBAH DETAIL BIAYA INTERNAL
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/biaya-sekolah/detail-biaya/detail-biaya-internal/action-detail-biaya-internal/'.Request::segment(7).'/add/'.$id_detail_biaya_internal)}}">
                        {{csrf_field()}}

                        <div class="row">

                            <div class="col-md-5">
                                <h2 class="card-inside-title">
                                    Nama Detail Biaya Internal
                                </h2>
                                <input type="text" class="form-control" name="nm_detail_biaya_internal[]" required="" aria-required="true" aria-invalid="true">   
                            </div>

                            <div class="col-md-5">
                                <h2 class="card-inside-title">
                                    Besar Biaya
                                </h2>
                                <input type="number" class="form-control" name="besar_biaya[]" required="" aria-required="true" aria-invalid="true">
                            </div>

                            <div class="col-md-2">
                                <button type="button" id="button_tambah" style="margin-top: 27px;" class="btn bg-purple waves-effect">
                                    <i class="material-icons">add</i>
                                    <span>Tambah</span>
                                </button>
                            </div>


                        </div>

                        <div id="place_add">



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

<script type="text/javascript">
    
    $('#button_tambah').click(function(){

        $('#place_add').append(`

            <div class="row">

                <div class="col-md-5">
                    <h2 class="card-inside-title">
                        Nama Detail Biaya Internal
                    </h2>
                    <input type="text" class="form-control" name="nm_detail_biaya_internal[]" required="" aria-required="true" aria-invalid="true">   
                </div>

                <div class="col-md-5">
                    <h2 class="card-inside-title">
                        Besar Biaya
                    </h2>
                    <input type="number" class="form-control" name="besar_biaya[]" required="" aria-required="true" aria-invalid="true">
                </div>

                <div class="col-md-2">
                    <button type="button" id="button_hapus" style="margin-top: 27px;" class="btn bg-red waves-effect">
                        <i class="material-icons">delete</i>
                        <span>Hapus</span>
                    </button>
                </div>


            </div>

        `);

    });

    $("#place_add").on("click", "#button_hapus", function() {

        $(this).parent().parent().remove();

    });

</script>