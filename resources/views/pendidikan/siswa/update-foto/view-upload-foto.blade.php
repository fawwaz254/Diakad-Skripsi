    <div class="container-fluid">
        <div class="block-header">
            <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#data-dokumen/input-dokumen/')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            UPDATE FOTO SISWA 
                        </h2>
                    </div>
                    <div class="body">
                        <form id="formUpload" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-update-foto/upload/'.$id_pengguna)}}" enctype="multipart/form-data">
                            {{csrf_field()}}
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h3>Upload Foto</h3>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>
                                    <input type="file" name="file" />
                                </label>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" id="upload" type="submit"><i class="material-icons">cloud_upload</i><span>Upload</span></button>
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
    $(document).ready(function () {

        $("#formUpload").submit(function (e) {

            //disable the submit button
            $('button').attr('disabled', 'disabled');

            return true;

        });

    });
</script>