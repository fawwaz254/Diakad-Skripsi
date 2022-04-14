<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#magang-siswa/laporan-magang')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        EDIT LINK LAPORAN MAGANG SISWA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/input/action-laporan-input-siswa/edit/'.$data->link_laporan_magang_id)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Link Google Drive
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="link_google_drive" required="" aria-required="true"
                                aria-invalid="true" value="{{ $data->link_google_drive }}">
                            </div>
                        </div>
                        {{-- <input type="hidden" class="form-control" name="id_rekanan_magang" required="" aria-required="true"
                                aria-invalid="true" value="{{ $data->$id_rekanan_magang }}">
                        <input type="hidden" class="form-control" name="id_periode_magang" required="" aria-required="true"
                                aria-invalid="true" value="{{ $data->id_periode_magang }}"> --}}


                        <div class="alert alert-warning">
                            <strong>Catatan !</strong> Untuk link sertifikat pastikan anda mengupload di google drive dengan settingan publik, untuk tutorial menguplod dengan settingan publik bisa dilihat <span style="text-decoration:underline;cursor:pointer" id="disini">disini</span>.
                        </div>

                        <div id="video" style="display:none;">
                        <iframe width="870" height="393" src="https://www.youtube.com/embed/ccXgIuT0Hjc" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
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

@include('scriptjs');

<script type="text/javascript">

    $('#disini').click(function(){
        $('#video').show();
    })
</script>