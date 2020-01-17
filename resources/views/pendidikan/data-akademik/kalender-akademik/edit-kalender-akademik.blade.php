<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#data-akademik/kalender-akademik/view-semester/'.$data_semester->id_semester)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        EDIT KALENDER AKADEMIK SEMESTER {{$data_semester->tahun_ajaran}} {{$data_semester->nm_semester}}
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-kalender-akademik/edit/'.$data_kalender_akademik->id_jadwal_kegiatan)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Nama Kegiatan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_kegiatan">
                                    @foreach($data_kegiatan as $data)
                                    @if($data->id_kegiatan == $data_kalender_akademik->id_kegiatan)
                                    <option value="{{$data->id_kegiatan}}" readonly>{{$data->kode_kegiatan}} - {{$data->nm_kegiatan}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Semester
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_semester">
                                    <option value="{{$data_semester->id_semester}}">{{$data_semester->tahun_ajaran}}
                                        {{$data_semester->nm_semester}}</option>
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal Mulai
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_mulai" required="" aria-required="true"
                                    aria-invalid="true" value="{{$tgl_mulai}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal Selesai
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_selesai" required="" aria-required="true"
                                    aria-invalid="true" value="{{$tgl_selesai}}">
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