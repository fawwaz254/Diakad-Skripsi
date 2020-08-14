<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
            <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-setting-toleransi')}}">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>
                            SETTING KETERLAMBATAN UNTUK KELAS DARING (BERLAKU SECARA GLOBAL)
                        </h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label>Toleransi keterlambatan dalam menit</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="number" class="form-control" min="0" name="setting_keterlambatan_global" required="" aria-required="true" aria-invalid="true" value="{{$setting->value}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-btn-submit waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')