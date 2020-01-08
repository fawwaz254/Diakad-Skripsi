<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#sarpras/komplain-sarpras/ruangan-sarpras/view-ruangan/'.$data_ruangan->id_ruangan)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        EDIT KOMPLAIN SARPRAS RUANGAN ({{$data_ruangan->nm_ruangan}} - {{$data_ruangan->nm_jenis_ruangan}})
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-komplain-sarpras/edit-ruangan/'.$data_komplain_sarpras->id_komplain_sarpras)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Ruangan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_ruangan">
                                    <option value="{{$data_ruangan->id_ruangan}}">{{$data_ruangan->nm_ruangan}} - {{$data_ruangan->nm_jenis_ruangan}}</option>
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Inventaris Ruangan <small><b>* Kosongkan Apabila Komplain Kepada Ruangan</b></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_inventaris_ruangan">
                                    <option value="">--</option>
                                    @foreach($data_inventaris_ruangan as $data)
                                        @if($data->id_inventaris_ruangan == $data_komplain_sarpras->id_inventaris_ruangan)
                                            <option value="{{$data->id_inventaris_ruangan}}" selected >{{$data->nm_inventaris_ruangan}}</option>
                                        @else
                                            <option value="{{$data->id_inventaris_ruangan}}" >{{$data->nm_inventaris_ruangan}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Keterangan Komplain
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="keterangan_komplain" required="" aria-required="true" aria-invalid="true" value="{{$data_komplain_sarpras->keterangan_komplain}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Status Urgent
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="is_urgent">
                                    @if($data_komplain_sarpras->is_urgent == 0)
                                        <option value="0" selected >Tidak Urgent</option>
                                        <option value="1">Urgent</option>
                                        <option value="2">Sangat Urgent</option>
                                    @elseif($data_komplain_sarpras->is_urgent == 1) 
                                        <option value="0">Tidak Urgent</option>
                                        <option value="1" selected >Urgent</option>
                                        <option value="2">Sangat Urgent</option>
                                    @elseif($data_komplain_sarpras->is_urgent == 2)
                                        <option value="0">Tidak Urgent</option>
                                        <option value="1">Urgent</option>
                                        <option value="2" selected >Sangat Urgent</option>
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