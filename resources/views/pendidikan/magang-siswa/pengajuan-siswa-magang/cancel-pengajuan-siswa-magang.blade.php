<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#magang-siswa/pengajuan-siswa-magang')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-green">
                    <h2>
                        CANCEL PENGAJUAN MAGANG {{$data_pengambilan_magang->nm_periode_magang}}
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-pengajuan-siswa-magang/cancel/'.$id.'/0/0/0')}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            NIS
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nis_siswa" required="" aria-required="true" aria-invalid="true" 
                                value="{{$data_pengambilan_magang->nis_siswa}}" readonly >
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Siswa
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_pengguna" required="" aria-required="true" aria-invalid="true" value="{{$data_pengambilan_magang->nm_pengguna}}" readonly >
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kelas Siswa
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_kelas" required="" aria-required="true" aria-invalid="true" value="{{$data_pengambilan_magang->nm_kelas}}" readonly >
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Periode Magang
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_kelas" required="" aria-required="true" aria-invalid="true" value="{{$data_pengambilan_magang->nm_periode_magang}}" readonly >
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Keterangan Batal
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="keterangan_batal" required="" aria-required="true" aria-invalid="true" >
                                <input type="hidden" class="form-control" name="nis_nama_siswa" required="" aria-required="true" aria-invalid="true" value="{{$data_pengambilan_magang->nis_siswa}}">
                                <input type="hidden" class="form-control" name="id_periode_magang" required="" aria-required="true" aria-invalid="true" value="{{$data_pengambilan_magang->id_rekanan_magang}}">
                                <input type="hidden" class="form-control" name="id_rekanan_magang" required="" aria-required="true" aria-invalid="true" value="{{$data_pengambilan_magang->id_rekanan_magang}}">
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