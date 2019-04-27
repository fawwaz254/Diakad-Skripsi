<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#magang-siswa/periode-magang')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-green">
                    <h2>
                        EDIT PERIODE MAGANG
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-periode-magang/edit/'.$data_periode_magang->id_periode_magang)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Nama Magang Siswa
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_magang">
                                    @foreach($data_magang as $data)
                                        @if($data->id_magang == $data_periode_magang->id_magang)
                                            <option value="{{$data->id_magang}}" selected >{{$data->nm_magang}}</option>
                                        @else
                                            <option value="{{$data->id_magang}}">{{$data->nm_magang}}</option>
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
                                        @if($data->id_semester == $data_periode_magang->id_semester)
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
                                <input type="text" class="form-control" name="nm_periode_magang" required="" aria-required="true" aria-invalid="true" value="{{$data_periode_magang->nm_periode_magang}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            No. SK Periode Magang
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nomor_sk_periode_magang" required="" aria-required="true" aria-invalid="true" value="{{$data_periode_magang->nomor_sk_periode_magang}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Besar Biaya
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="besar_biaya" required="" aria-required="true"
                                    aria-invalid="true" value="{{$data_periode_magang->besar_biaya}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal Mulai Magang
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_magang_mulai" required="" aria-required="true" aria-invalid="true" value="{{$tgl_mulai}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal Selesai Magang
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_magang_selesai" required="" aria-required="true" aria-invalid="true" value="{{$tgl_selesai}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Status Aktif
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="is_aktif">
                                    @if($data_periode_magang->is_aktif == 0)
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
