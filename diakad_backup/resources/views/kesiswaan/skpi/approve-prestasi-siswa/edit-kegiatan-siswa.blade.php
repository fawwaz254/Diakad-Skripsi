<div class="container-fluid">
    <div class="block-header">
        @if(Request::segment(1)=='guru')
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#wali-kelas/approve-prestasi-siswa')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
        @else
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#skpi/approve-prestasi-siswa')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
        @endif
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        EDIT KEGIATAN SISWA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="post" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-edit-kegiatan-siswa/'.$kegiatan->id_kegiatan_siswa)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                                Nama Siswa
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="nm_pengguna" readonly="" aria-required="true"
                                    aria-invalid="true" value="{{$kegiatan->nm_pengguna}}">
                                    <input type="hidden" class="form-control" name="id_siswa" readonly="" aria-required="true"
                                    aria-invalid="true" value="{{$kegiatan->id_siswa}}">
                                </div>
                            </div>

                            <h2 class="card-inside-title">
                                Semester
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="id_semester">
                                        <option value="">-- Pilih Semester --</option>
                                        @foreach($data_semester as $data)
                                            <option value="{{$data->id_semester}}" @if($data->id_semester == $kegiatan->id_semester) selected @endif>
                                                {{$data->nm_semester}} ({{$data->tahun_ajaran}}) @if($data->is_aktif_semester == "1") (Aktif) @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        <h2 class="card-inside-title">
                            Nama Kegiatan 
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_kegiatan_siswa" required="" value="{{$kegiatan->nm_kegiatan_siswa}}" aria-required="true" aria-invalid="true">
                            </div>
                        </div>

                        <div class="row clearfix">

                            <div class="col-md-4">
                                <h2 class="card-inside-title">
                                   Lokasi
                                </h2>
                                <input type="text" value="{{$kegiatan->lokasi_kegiatan_siswa}}" class="form-control" name="lokasi_kegiatan_siswa" required="" aria-required="true" aria-invalid="true">
                            </div>

                             <div class="col-md-4">
                                <h2 class="card-inside-title">
                                   Penyelenggara
                                </h2>
                                <input type="text" value="{{$kegiatan->penyelenggara_kegiatan_siswa}}" class="form-control" name="penyelenggara_kegiatan_siswa" required="" aria-required="true" aria-invalid="true">
                            </div>
                        <h2 class="card-inside-title">
                            Tanggal Kegiatan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" value="{{date('d F Y', strtotime($kegiatan->tgl_kegiatan_siswa))}}" name="tgl_kegiatan_siswa" required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                         <h2 class="card-inside-title">
                            Link Sertifikat
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="link_sertifikat" required="" value="{{$kegiatan->nm_kegiatan_scan_sertif}}" aria-required="true" aria-invalid="true">
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