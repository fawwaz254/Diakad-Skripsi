<div class="container-fluid">
    <div class="block-header">
        <!-- <h2>PASSWORD</h2> -->
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/must-change-password')}}">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>PASSWORD ANDA SAAT INI ADALAH PASSWORD SEMENTARA, TOLONG UBAH PASSWORD ANDA</h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label>New Password</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input id="password" type="password" class="form-control" name="new_password" minlength="4" required="" aria-required="true">
                                    </div>
                                </div>
                                <label>Re-type New Password</label>
                                <div class="form-group">
                                    <div class="form-line">
                                    <input type="password" class="form-control" name="new_confirm_password" minlength="4" required="" aria-required="true" equalto="#password">
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