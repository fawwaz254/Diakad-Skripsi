<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#biodata/data-kegiatan')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        TAMBAH KEGIATAN
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="post" action="{{url(Request::segment(1).'/'.Request::segment(2).'/data-kegiatan/action/add/0')}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Nama Kegiatan 
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_kegiatan" required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>

                        <div class="row clearfix">

                            <div class="col-md-4">
                                <h2 class="card-inside-title">
                                   Lokasi
                                </h2>
                                <input type="text" class="form-control" name="lokasi" required="" aria-required="true" aria-invalid="true">
                            </div>

                             <div class="col-md-4">
                                <h2 class="card-inside-title">
                                   Penyelenggara
                                </h2>
                                <input type="text" class="form-control" name="penyelenggara" required="" aria-required="true" aria-invalid="true">
                            </div>

                            <div class="col-md-4">
                            <h2 class="card-inside-title">
                                Tingkat Kegiatan
                            </h2>
                                <select class="form-control show-tick" name="id_tingkat_prestasi_siswa" required="">
                                	@foreach($tingkat as $r)
                                        <option value="{{$r->id_tingkat_prestasi_siswa}}">{{$r->nm_tingkat_prestasi_siswa}}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                        </div>

                        <h2 class="card-inside-title">
                            Tanggal Kegiatan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_kegiatan" required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <strong>Catatan !</strong> Untuk link sertifikat pastikan anda mengupload di google drive dengan settingan publik, untuk tutorial menguplod dengan settingan publik bisa dilihat <span style="text-decoration:underline;cursor:pointer" id="disini">disini</span>.
                        </div>

                        <div id="video" style="display:none;">
                        <iframe width="870" height="393" src="https://www.youtube.com/embed/ccXgIuT0Hjc" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>

                         <h2 class="card-inside-title">
                            Link Sertifikat
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="link_kegiatan"  aria-required="true" aria-invalid="true">
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

<script type="text/javascript">
    $('#disini').click(function(){
        $('#video').show();
    })
</script>

<script type="text/javascript">
    $(function(){
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'DD MMMM YYYY',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: false
        });
    });
</script>
