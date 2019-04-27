<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#wisuda/periode-wisuda')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-green">
                    <h2>
                        EDIT PERIODE WISUDA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-periode-wisuda/edit/'.$data_periode_wisuda->id_periode_wisuda)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Nama Wisuda
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_wisuda">
                                    @foreach($data_wisuda as $data)
                                        @if($data->id_wisuda == $data_periode_wisuda->id_wisuda)
                                            <option value="{{$data->id_wisuda}}" selected >{{$data->nm_wisuda}}</option>
                                        @else
                                            <option value="{{$data->id_wisuda}}">{{$data->nm_wisuda}}</option>
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
                                    @foreach($data_semester as $data)
                                        @if($data->id_semester == $data_periode_wisuda->id_semester)
                                            <option value="{{$data->id_semester}}" selected >{{$data->tahun_ajaran}}
                                            {{$data->nm_semester}}</option>
                                        @else
                                            <option value="{{$data->id_semester}}">{{$data->tahun_ajaran}}
                                            {{$data->nm_semester}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Periode
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_periode_wisuda" required="" aria-required="true" aria-invalid="true" value="{{$data_periode_wisuda->nm_periode_wisuda}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Besar Biaya
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="besar_biaya" required="" aria-required="true"
                                    aria-invalid="true" value="{{$data_periode_wisuda->besar_biaya}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal Bayar Mulai
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_bayar_mulai" required="" aria-required="true" aria-invalid="true" value="{{$tgl_mulai}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal Bayar Selesai
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_bayar_selesai" required="" aria-required="true" aria-invalid="true" value="{{$tgl_selesai}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Status Aktif
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="is_aktif">
                                    @if($data_periode_wisuda->is_aktif == 0)
                                        <option value="0" selected >Non-Aktif</option>
                                        <option value="1">Aktif</option>
                                    @else
                                        <option value="0">Non-Aktif</option>
                                        <option value="1" selected >Aktif</option>
                                    @endif
                                </select>
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
            format: 'DD MMMM YYYY',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: false
        });
    });
</script>