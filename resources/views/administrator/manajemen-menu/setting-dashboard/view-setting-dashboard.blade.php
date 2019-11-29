<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        SETTING DASHBOARD
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-view-setting-dashboard')}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Filter Role
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_role">
                                    <option value="" disabled selected >-- Pilih Role --</option>
                                    @foreach($data_role as $data)
                                        @if($id_role != null)
                                            @if($data->id_role == $id_role)
                                                <option value="{{$data->id_role}}" selected >{{$data->nm_role}}</option>
                                            @else
                                                <option value="{{$data->id_role}}">{{$data->nm_role}}</option>
                                            @endif
                                        @else
                                            <option value="{{$data->id_role}}">{{$data->nm_role}}</option>
                                        @endif
                                    @endforeach
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