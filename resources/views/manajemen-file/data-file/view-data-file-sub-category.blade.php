<style type="text/css">
    .folder:hover{
        transform: scale(1.2);
    }
    .folder{
        cursor: pointer;
    }
</style>

<div class="container-fluid">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    <div class="header">
                        <h2>Data File Sub Kategori {{$sub_category->sub_category_file_name}}</h2>
                    </div>
                    <div class="body">

                        <div class="row">
                                
                            @foreach($file as $r)
                            @if($r->is_google_drive == 1)
                            <a href="{{$r->link_file}}" target="_blank" style="color: inherit;text-decoration: inherit; ">
                            @else
                            <a href="{{Storage::disk('spaces')->url($r->link_file)}}" target="_blank" style="color: inherit;text-decoration: inherit; ">
                            @endif
                            <div class="col-md-3 folder">
                                <center>
                                  @if($r->extension_file == 'pdf')
                                  <i class="material-icons" style="color:red;font-size: 45px;">picture_as_pdf</i>
                                  @else
                                  <i class="material-icons" style="color:blue;font-size: 45px;">description</i>
                                  @endif                
                                </center>
                                <center>
                                    <span>{{$r->judul}}</span>
                                </center>
                            </a>
                            <center style="margin-top:10px;">
                                <a onclick="deleteFile('{{$r->file_pengguna_id}}')">
                                    <i style="color:red;" class="material-icons">cancel</i>
                                </a>
                            </center>
                            </div>
                            @endforeach

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function deleteFile(id) {
        var token = $("meta[name='csrf-token']").attr("content");
        swal(
        { title: "Are you sure?", showCancelButton: true},
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: `{{Request::segment(1)}}/{{Request::segment(2)}}/{{Request::segment(3)}}/action-data-file/delete/${id}`,
                    type: "post",

                    data: {
                        _token: token,
                    },

                    success: function () {
                        swal({
                            title: "Delete Success",
                            text: "Data berhasil dihapus",
                            icon: "success",
                        });
                        loadURI('{{Request::segment(2)}}/{{Request::segment(3)}}/{{Request::segment(4)}}/{{Request::segment(5)}}');
                    },
                });
            }
            return;
        }
    );
}
</script>