<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#'.Request::segment(2).'/data-prestasi')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        TAMBAH PRESTASI
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="post" action="{{url(Request::segment(1).'/'.Request::segment(2).'/data-prestasi/action/add/0')}}">
                        {{csrf_field()}}

                        <div class="row">
                            <div class="col-md-6">            
                                <h2 class="card-inside-title">
                                    Nama Prestasi 
                                </h2>
                                <input type="text" class="form-control" name="nm_prestasi" required="" aria-required="true" aria-invalid="true">
                            </div>

                            <div class="col-md-6">
                                <h2 class="card-inside-title">
                                    Jenis Lomba
                                </h2>
                                <select class="form-control show-tick" name="jenis_lomba">
                                    <option value="">Pilih Jenis Lomba</option>
                                    <option value="Individu">Individu</option>
                                    <option value="Kelompok">Kelompok</option>
                                </select>
                            </div>

                        </div>

                        <div class="row">

                             <div class="col-md-4">
                                <h2 class="card-inside-title">
                                   Peringkat
                                </h2>
                                <input type="number" class="form-control" name="peringkat" required="" aria-required="true" aria-invalid="true">
                            </div>

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

                        </div>
                       
                        <div class="row">
                            <div class="col-md-4">
                                <h2 class="card-inside-title">
                                    Jenis Prestasi
                                </h2>
                                <select class="form-control show-tick" name="jenis_prestasi" required="">
                                    @foreach($jenis_prestasi as $r)
                                        <option value="{{$r[0]}}">{{$r[1]}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <h2 class="card-inside-title">
                                Tingkat Prestasi
                                </h2>
                                <select class="form-control show-tick" name="id_tingkat_prestasi" required="">
                                    @foreach($tingkat as $r)
                                        <option value="{{$r->id_tingkat_prestasi_siswa}}">{{$r->nm_tingkat_prestasi_siswa}}</option>
                                    @endforeach
                                </select>
                            </div>

                             <div class="col-md-4">
                                <h2 class="card-inside-title">
                                    Tanggal Kegiatan
                                </h2>
                                <input type="text" class="datepicker form-control" name="tanggal" required="" aria-required="true" aria-invalid="true">
                            </div>

                        </div>


                        <div class="alert alert-warning">
                            <strong>Catatan !</strong> Untuk link sertifikat pastikan anda mengupload di google drive dengan settingan publik, untuk tutorial menguplod dengan settingan publik bisa dilihat <span style="text-decoration:underline;cursor:pointer" id="disini">disini</span>.
                        </div>

                        <div id="video" style="display:none;">
                        <iframe width="870" height="393" src="https://www.youtube.com/embed/ccXgIuT0Hjc" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">            
                                <h2 class="card-inside-title">
                                    Link Sertifikat
                                </h2>
                                <input type="text" class="form-control" name="link_sertifikat" required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>

                        @if(Request::segment(1)=='humas')
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
                            <h2 class="card-inside-title">
                                Pilih Guru
                            </h2>
                            <select class="form-control show-tick" name="id_pengguna" required="">
                                @foreach($guru as $r)
                                    <option value="{{$r->id_pengguna}}">{{$r->nm_pengguna}}</option>
                                @endforeach
                            </select>
                            </div>
                        </div>
                        @endif

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