@php
    $total_file = 0;
@endphp
<style type="text/css">
/*     .folder:hover {
        transform: scale(1.2);
    } */

    .folder {
        cursor: pointer;
    }

    i.folder.material-icons {
        width: 45px;
    }
</style>
<div class="container-fluid">
    <div class="block-header">
        <div class="row clearfix">
            <div class="col-lg-1 col-md-6 col-sm-12 col-xs-12" id="add-file">
                <h2>
                    <a class="btn bg-blue waves-effect target-link"
                        href="{{url(Request::segment(1).'#manajemen-file/data-file/add')}}"><i
                            class="material-icons">note_add</i>
                        <span>Tambah File</span>
                    </a>
                </h2>
            </div>
            <div class="col-lg-1 col-md-6 col-sm-12 col-xs-12">
                <button onclick="toggleDelete()" class="btn bg-red waves-effect target-link" id="toggle-delete">
                    <i class="material-icons">delete_forever</i>
                    <span>Hapus File</span>
                </button>
            </div>
            <div class="col-lg-11 col-md-6 col-sm-12 col-xs-12">
                <button type="submit" form="form-validation" class="btn bg-red waves-effect target-link"
                    id="delete-many-files-btn" style="display: none" onclick="deleteManyFiles()">
                    <i class="material-icons">delete_forever</i>
                    <span>Hapus File</span>
                </button>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Data File Sub Kategori {{$sub_category->sub_category_file_name}}</h2>
                </div>
                <div class="body">
                    <form
                        action="{{url(Request::segment(1).'/'.Request::segment(2).'/data-file/action-data-file/delete-many/0')}}"
                        id="form-validation" method="POST">
                        @foreach ($files as $file)
                        <p>{{$file->nm_pengguna}}</p>
                        <hr>
                        <div class="row">
                            @foreach($file->file_pengguna as $r)
                            @if($r->is_google_drive == 1)
                            <a href="{{$r->link_file}}" target="_blank"
                                style="color: inherit;text-decoration: inherit; ">
                                @else
                                <a href="{{Storage::disk('spaces')->url($r->link_file)}}" target="_blank"
                                    style="color: inherit;text-decoration: inherit; ">
                                    @endif
                                    @php
                                    $total_file++
                                    @endphp
                                    <div class="col-md-3 folder">
                                        <div style="text-align: center;">
                                            @if($r->extension_file == 'pdf')
                                            <i class="material-icons"
                                                style="color:red;font-size: 45px;width:45px">picture_as_pdf</i>
                                            @elseif($r->extension_file == 'png' || $r->extension_file == 'jpg' ||
                                            $r->extension_file == 'jpeg')
                                            <i class="material-icons"
                                                style="color:blue;font-size: 45px;width:45px">collections_icon</i>
                                            @elseif($r->extension_file == 'pptx' || $r->extension_file == 'docx' ||
                                            $r->extension_file == 'xlsx')
                                            <i class="material-icons"
                                                style="color:blue;font-size: 45px;width:45px">description</i>
                                            @else
                                            <i class="material-icons"
                                                style="color:green;font-size: 45px;width:45px">add_to_drive</i>
                                            @endif
                                        </div>

                                        <div style="text-align: center">
                                            {{-- <span>{{$r->judul}}</span> --}}
                                            <span>{{$r->file_pengguna_id}}</span>
                                            <span>{{$r->link_file}}</span>
                                        </div>
                                </a>
                            </a>
                            <div class="form-check delete-many"
                                style="text-align: center;margin-top: 10px; display: none">
                                <input type="checkbox" class="form-check-input" value="{{$r->file_pengguna_id}}"
                                    name="id_file[]" id="id_file[{{$r->file_pengguna_id}}]">
                                <label class="form-check-label" for="id_file[{{$r->file_pengguna_id}}]"></label>
                                <input type="hidden" name="link_file[]" value="{{$r->link_file}}">
                            </div>
                            <input type="hidden" name="sub_category_file_id"
                                value="{{$sub_category->sub_category_file_id}}">
                            <div style="text-align: center;margin-top: 10px;" class="delete-one">
                                <a
                                    onclick="deleteFile('{{$r->file_pengguna_id}}','{{$r->link_file}}','{{$sub_category->sub_category_file_id}}')">
                                    <i style="color:red;" class="material-icons">cancel</i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </form>
                    @endforeach
                </div>
                <p>TOTAL FILE : <b>{{$total_file}}</b></p>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<script>
    function toggleDelete() {
        if ($('.delete-one').css('display') === "block") {
            $('.delete-one').hide()
            $('.delete-many').show()
            $('#delete-many-files-btn').show()
            $('#add-file').hide()
            $('#toggle-delete').html(`
                <i class="material-icons">do_disturb</i>
                <span>Batal</span>
            `)
        } else {
            $('.delete-one').show()
            $('.delete-many').hide()
            $('#add-file').show()
            $('#delete-many-files-btn').hide()
            $('#toggle-delete').html(`
                <i class="material-icons">delete_forever</i>
                <span>Hapus File</span>
            `)
        }
    }
    $('#form-validation').validate({
        rules: {
            'checkbox': {
                required: true
            },
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
        submitHandler: function (form) {
            $('button').attr('disabled', 'disabled');
            $.ajax({
                url: form.action,
                type: form.method,
                enctype: 'multipart/form-data',
                data: new FormData($('#form-upload')[0]),
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.status == 200) {
                        vex.dialog.alert(response.message);
                    } else if (response.status == 201) {
                        vex.dialog.alert(response.message);
                        window.location.href = response.link;
                    } else if (response.status == 202) {
                        vex.dialog.alert(response.message);
                        loadURI(response.path);
                    } else if (response.status == 203) {
                        vex.dialog.alert(response.message);
                        primary_table.ajax.reload(null, false);
                    } else if (response.status == 204) {
                        loadURI(response.path);
                    } else if (response.status == 300) {
                        vex.dialog.alert(response.message);
                    }
                },
                complete: function (response) {
                    $('input').removeAttr('readonly', 'readonly');
                    $('button').removeAttr('disabled');
                }
            });
        }
    });

    function deleteFile(id, linkFile, sub_category_file_id) {
        swal({
                title: "Are you sure?",
                showCancelButton: true
            },
            function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: `{{Request::segment(1)}}/{{Request::segment(2)}}/{{Request::segment(3)}}/action-data-file/delete/${id}`,
                        type: "post",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="token"]').attr('content')
                        },
                        data: {
                            link_file: linkFile,
                            sub_category_file_id: sub_category_file_id
                        },

                        success: function (response) {
                            if (response.status == 200) {
                                vex.dialog.alert(response.message);
                            } else if (response.status == 201) {
                                vex.dialog.alert(response.message);
                                window.location.href = response.link;
                            } else if (response.status == 202) {
                                vex.dialog.alert(response.message);
                                loadURI(response.path);
                            } else if (response.status == 203) {
                                vex.dialog.alert(response.message);
                                primary_table.ajax.reload(null, false);
                            } else if (response.status == 204) {
                                loadURI(response.path);
                            } else if (response.status == 300) {
                                vex.dialog.alert(response.message);
                            }
                        },
                    });
                }
                return;
            }
        );
    }
</script>