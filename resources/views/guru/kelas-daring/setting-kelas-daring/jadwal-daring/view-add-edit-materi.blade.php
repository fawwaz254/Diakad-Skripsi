<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#'.Request::segment(2).'/jadwal-kelas/data-jadwal/'.$id_kelas_mp_grup)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        Input Materi Kelas
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/jadwal-kelas/materi/action/add-materi')}}" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <input type="hidden" name="id_kelas_mp_grup" value="{{$id_kelas_mp_grup}}">
                        <input type="hidden" name="id_presensi_mp" value="{{$item->id_presensi_mp}}">

                        <label>Isi Materi Tanggal {{date_format(date_create($item->tgl_presensi), 'd M Y')}}</label>

                        <textarea name="uraian_materi" id="editor1" class="editor1" rows="10" cols="80">{{$item->uraian_materi}}</textarea>
                        <div id="te"></div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Simpan Materi</span></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card">
                <div class="header">
                    <h2>
                        File-file materi
                    </h2>
                </div>
                <div class="body">
                    @if($data_materi->first())
                    <ol>
                        @foreach($data_materi as $materi)
                        <li><a href="{{$materi->link_materi}}" target="_blank">{{$materi->nm_materi}}</a></li>
                        @endforeach
                    </ol>
                    @endif
                    <form id="form-upload" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/jadwal-kelas/materi/action/add-file')}}" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <input type="hidden" name="id_kelas_mp_grup" value="{{$id_kelas_mp_grup}}">
                        <input type="hidden" name="id_presensi_mp" value="{{$item->id_presensi_mp}}">

                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Nama File</label>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_materi" required="" aria-required="true" aria-invalid="true" >
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Upload Materi</label>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>
                                    <input type="file" name="file" />
                                </label>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Upload</span></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<script>
var primary_table = null;
    $('#form-upload').validate({
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
                enctype: 'multipart/form-data',
                data: new FormData($('#form-upload')[0]),
                cache: false,
                contentType: false,
                processData: false,
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
                    $('input').removeAttr('readonly', 'readonly');
                }
            });
        }
    });
</script>

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