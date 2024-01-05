
<div class="container-fluid">
    <div class="block-header">
        <!-- <h2>PASSWORD</h2> -->
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>PASSWORD ANDA SAAT INI ADALAH PASSWORD SEMENTARA, TOLONG UBAH PASSWORD ANDA</h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/must-change-password')}}">
                    {{csrf_field()}}
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
                    </form>
                    <hr>
                    <form id="form-validation-1" method="POST" action="{{ url(Request::segment(1).'/by-pass-change-password') }}">
                        {{csrf_field()}}
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <input type="checkbox" id="is_agree" name="is_agree" value="1">
                                    <label for="is_agree"> Saya menyadari potensi yang terjadi jika tetap menggunakan PASSWORD DEFAULT</label><br>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block btn-danger waves-effect" type="submit"><i class="material-icons">warning</i><span>Saya ingin menggunakan PASSWORD DEFAULT ini*</span></button>
                                <small>* orang lain berpotensi untuk login menggunakan akun Anda</small>
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
$('#form-validation-1').validate({
        rules: {
            'checkbox': {
                required: true
            },
            'gender': {
                required: true
            }
        },
        highlight: function (input) {
            $(input).parents('.form-group').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-group').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error);
        },
        submitHandler: function(form) {
            $('button').attr('disabled', 'disabled');
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    if(response.status == 200){
                        vex.dialog.alert(response.message);
                    }else if(response.status == 201){
                        vex.dialog.alert(response.message);
                        window.location.href = response.link;
                    }else if(response.status == 202){
                        vex.dialog.alert(response.message);
                        loadURI(response.path);
                    }else if(response.status == 203){
                        vex.dialog.alert(response.message);
                        primary_table.ajax.reload(null, false);
                    }else if(response.status == 204){
                        loadURI(response.path);
                    }else if(response.status == 300){
                        vex.dialog.alert(response.message);
                    }
                },
                complete: function() {
                    $('button').removeAttr('disabled');
                }
            });
        }
    });
</script>