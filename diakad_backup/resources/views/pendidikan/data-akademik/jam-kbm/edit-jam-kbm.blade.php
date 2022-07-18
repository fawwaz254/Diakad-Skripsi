<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#data-akademik/jam-kbm')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        EDIT JAM KBM
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-jam-kbm/edit/'.$data_jadwal_jam->id_jadwal_jam)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Nama Jadwal KBM
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_jadwal_jam" required="" aria-required="true" aria-invalid="true"
                                    value="{{$data_jadwal_jam->nm_jadwal_jam}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Jam Ke
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="jam_ke" required="" aria-required="true" aria-invalid="true"
                                    value="{{$data_jadwal_jam->jam_ke}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Jam Mulai
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="timepicker form-control" name="jam_menit_mulai" required="" aria-required="true"
                                    aria-invalid="true" value="{{$data_jadwal_jam->jam_mulai}}:{{$data_jadwal_jam->menit_mulai}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Jam Selesai
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="timepicker form-control" name="jam_menit_selesai" required="" aria-required="true"
                                    aria-invalid="true" value="{{$data_jadwal_jam->jam_selesai}}:{{$data_jadwal_jam->menit_selesai}}">
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
    $('.timepicker').bootstrapMaterialDatePicker({
        format: 'HH:mm',
        clearButton: true,
        date: false
    });
});
</script>