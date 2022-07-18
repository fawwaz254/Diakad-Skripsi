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
                
                <div class="body">
                    <form id="form-validation1" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/setting-dashboard')}}">
                        <h2 class="card-inside-title">
                            Isi Dashboard Role {{$nm_role}}
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                {{csrf_field()}}
                                <input type="hidden" class="form-control" name="id_role" required="" aria-required="true" aria-invalid="true" value="{{$id_role}}">
                                <textarea name="isi_dashboard" id="editor1" class="editor1" rows="10" cols="80">{{$isi_dashboard}}</textarea>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-green waves-effect" type="submit"><i class="material-icons">save</i><span>Simpan Data Dashboard</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

@include('scriptjs')

<!-- CKeditor Plugin Js -->
<script src="{{asset('plugins/ckeditor/ckeditor.js')}}"></script>

<script>
CKEDITOR.replace( 'editor1' );

// custom code to key binding ckeditor
timer = setInterval(updateDiv,100);
function updateDiv(){
    var editorText = CKEDITOR.instances.editor1.getData();
    $('#editor1').val(editorText);
}
</script>


<script> 
    $('#form-validation1').validate({
        rules: {
            'checkbox': {
                required: true
            },
            'gender': {
                required: true
            }
        },
        highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
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
                    $('button').removeAttr('disabled', 'disabled');
                }
            });
        }
    });
</script>