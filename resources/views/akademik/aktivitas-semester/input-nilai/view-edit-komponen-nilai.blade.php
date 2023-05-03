<div class="container-fluid">
    <div class="block-header">
       <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#aktivitas-semester/input-nilai/view-kelas/'.$id_pengguna.'/'.$id_semester.'/'.$id_kelas_mp)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        EDIT KOMPONEN NILAI MAPEL {{strtoupper($data_kelas->nm_mata_pelajaran) . " (" . strtoupper($data_kelas->kd_mata_pelajaran) . ") Kelas " . strtoupper($data_kelas->nm_kelas)}}
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-komponen-nilai/edit/'.$data_komponen_mp->id_komponen_mp)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Nama Komponen
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_komponen_mp" required="" aria-required="true"
                                    aria-invalid="true" value="{{$data_komponen_mp->nm_komponen_mp}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Persentase Komponen <small><b>* Cukup Angka Saja Tanpa Tanda %</b></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="persentase_komponen_mp" required="" aria-required="true" aria-invalid="true" value="{{$data_komponen_mp->persentase_komponen_mp}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Urutan Komponen
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="urutan_komponen_mp" required="" aria-required="true" aria-invalid="true" value="{{$data_komponen_mp->urutan_komponen_mp}}">
                                <input type="hidden" class="form-control" name="id_kelas_mp" required="" aria-required="true" aria-invalid="true" value="{{$id_kelas_mp}}">
                                <input type="hidden" class="form-control" name="id_pengguna" required="" aria-required="true" aria-invalid="true" value="{{$id_pengguna}}">
                                <input type="hidden" class="form-control" name="id_semester" required="" aria-required="true" aria-invalid="true" value="{{$id_semester}}">
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