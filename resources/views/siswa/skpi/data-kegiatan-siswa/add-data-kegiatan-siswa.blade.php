<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#skpi/data-kegiatan-siswa')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        TAMBAH KEGIATAN SISWA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="post" action="{{url(Request::segment(1).'/'.Request::segment(2).'/data-kegiatan-siswa/action/add/0')}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Nama Kegiatan 
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_kegiatan_siswa" required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>

                        <div class="row clearfix">

                            <div class="col-md-4">
                                <h2 class="card-inside-title">
                                   Lokasi
                                </h2>
                                <input type="text" class="form-control" name="lokasi_kegiatan_siswa" required="" aria-required="true" aria-invalid="true">
                            </div>

                             <div class="col-md-4">
                                <h2 class="card-inside-title">
                                   Penyelenggara
                                </h2>
                                <input type="text" class="form-control" name="penyelenggara_kegiatan_siswa" required="" aria-required="true" aria-invalid="true">
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
                                <input type="text" class="datepicker form-control" name="tgl_kegiatan_siswa" required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                         <h2 class="card-inside-title">
                            Link Sertifikat
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="link_sertifikat" required="" aria-required="true" aria-invalid="true">
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
