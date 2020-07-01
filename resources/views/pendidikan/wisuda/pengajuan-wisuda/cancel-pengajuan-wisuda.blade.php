<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#wisuda/pengajuan-wisuda/view-detail/'.$id_periode_wisuda.'/'.$id_kelas)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        CANCEL PENGAJUAN WISUDA {{$data_pengajuan_wisuda->nm_periode_wisuda}} SEMESTER {{$data_pengajuan_wisuda->tahun_ajaran}} {{$data_pengajuan_wisuda->nm_semester}}
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-pengajuan-wisuda/cancel/'.$data_pengajuan_wisuda->id_pengajuan_wisuda.'/0/0')}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            NIS
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nis_siswa" required="" aria-required="true" aria-invalid="true" value="{{$data_pengajuan_wisuda->nis_siswa}}" readonly >
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Siswa
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_pengguna" required="" aria-required="true" aria-invalid="true" value="{{$data_pengajuan_wisuda->nm_pengguna}}" readonly >
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kelas Siswa
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_kelas" required="" aria-required="true" aria-invalid="true" value="{{$data_pengajuan_wisuda->nm_kelas}}" readonly >
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal Pengajuan Wisuda
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="tgl_pengajuan_wisuda" required="" aria-required="true" aria-invalid="true" value="{{$tgl_pengajuan_wisuda}}" readonly >
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Keterangan Batal
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="keterangan_batal" required="" aria-required="true" aria-invalid="true" >
                                <input type="hidden" class="form-control" name="nis_nama_siswa" required="" aria-required="true" aria-invalid="true" value="{{$nis_nama_siswa}}">
                                <input type="hidden" class="form-control" name="id_periode_wisuda" required="" aria-required="true" aria-invalid="true" value="{{$id_periode_wisuda}}">
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
        format: 'dddd, DD MMMM YYYY',
        //lang : 'id',
        clearButton: true,
        weekStart: 1,
        time: false
    });
});
</script>