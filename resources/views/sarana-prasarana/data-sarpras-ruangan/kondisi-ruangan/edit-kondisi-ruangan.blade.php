<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#data-sarpras-ruangan/kondisi-ruangan')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-purple">
                    <h2>
                        EDIT KONDISI RUANGAN
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-kondisi-ruangan/edit/'.$data_kondisi_ruangan->id_kondisi_ruangan)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Nama Ruangan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_ruangan">
                                    @foreach($data_ruangan as $data)
                                        @if($data->id_ruangan == $data_kondisi_ruangan->id_ruangan)
                                            @if($data->is_aktif == 1)
                                                <option value="{{$data->id_ruangan}}" selected >{{$data->nm_ruangan}} - {{$data->nm_gedung}} (Aktif)</option>
                                            @else
                                                <option value="{{$data->id_ruangan}}" selected >{{$data->nm_ruangan}} - {{$data->nm_gedung}} (Non-Aktif)</option>
                                            @endif
                                        @else
                                            @if($data->is_aktif == 1)
                                                <option value="{{$data->id_ruangan}}">{{$data->nm_ruangan}} - {{$data->nm_gedung}} (Aktif)</option>
                                            @else
                                                <option value="{{$data->id_ruangan}}">{{$data->nm_ruangan}} - {{$data->nm_gedung}} (Non-Aktif)</option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Kerusakan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_kerusakan_ruangan">
                                    @foreach($data_kerusakan_ruangan as $data)
                                        @if($data->id_kerusakan_ruangan == $data_kondisi_ruangan->id_kerusakan_ruangan)
                                            <option value="{{$data->id_kerusakan_ruangan}}" selected >{{$data->nm_kerusakan_ruangan}}</option>
                                        @else
                                            <option value="{{$data->id_kerusakan_ruangan}}">{{$data->nm_kerusakan_ruangan}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Persentase Kerusakan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="persentase_kerusakan_ruangan" required="" aria-required="true" aria-invalid="true" value="{{$data_kondisi_ruangan->persentase_kerusakan_ruangan}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Keterangan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="keterangan_kerusakan_ruangan" required="" aria-required="true" aria-invalid="true" value="{{$data_kondisi_ruangan->keterangan_kerusakan_ruangan}}">
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