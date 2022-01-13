<div class="container-fluid">
    <div class="block-header">
        "{{url(Request::segment(1).'#'.Request::segment(2).'/'.Request::segment(3).'/'.$presences->id_presensi_pengguna.'/edit')}}"
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'#'.Request::segment(2).'/'.Request::segment(3).'/'.$presences->id_presensi_pengguna.'/edit')}}">
                    {{csrf_field()}}
                    <label>Status</label>
                    <label for="">Status</label>
                    <input type="text" name="status" value="{{$presences["status"]}}">
                    <label for="">Notes</label>

                    <input type="text" name="notes" value="{{$presences["notes"]}}">
                    <button type="submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')