<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{url(Request::segment(1).'#data-sumber-daya/update-foto/')}}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        {{-- {{url(Request::segment(1).'/'.Request::segment(2).'/action-batch-upload-foto')}} --}}
                        UPDATE FOTO GURU DAN TENDIK BATCH
                    </h2>
                </div>
                <div class="body">
                    <div id="frmFileUpload" class="dropzone">
                        <div class="dz-message">
                            <div class="drag-icon-cph">
                                <i class="material-icons">touch_app</i>
                            </div>
                            <h3>Drag dan drop foto siswa di sini.</h3>
                            <em>(Sistem kami akan secara otomatis menyambungkan nama file dengan NIS Pengguna yang telah ada di sistem.)</em>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        var myDropzone = new Dropzone("div#frmFileUpload", { 
            url: "{{url(Request::segment(1).'/'.Request::segment(2).'/action-batch-upload-foto')}}",
            maxFilesize: 3,
            acceptedFiles: "image/*",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="token"]').attr('content')
            }
        });
    })
</script>