<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    <div class="header">
                        <h2>JADWAL PERSIDANGAN</h2>
                    </div>
                    <div class="body">
                        <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/'.Request::segment(3).'/post-view-persidangan')}}">
                            {{csrf_field()}}
                            <div class="form-group">
                                <div class="form-line">
                                
                                <h6>JADWAL PERSIDANGAN</h6>   
                                        <select class="form-control show-tick" name="tahun_penetapan">
                                        <option value="">- Pilih Tahun -</option>
                                        @foreach($data_tahun_penetapan as $data)
                                            <option value="{{$data->tgl_penetapan}}">{{$data->tgl_penetapan}}</option>
                                        @endforeach
                                        </select>
                                    
                                
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary waves-effect" ><i class="material-icons"></i><span>View</span></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
