@php
$total_file = 0;
@endphp
<style type="text/css">
    .file:hover {
        transform: scale(1.2);
    }

    .file {
        cursor: pointer;
        /* overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis; */
    }

    .delete-one:hover {
        transform: scale(1.2);
    }

    .delete-one {
        cursor: pointer;
    }

    i.file.material-icons {
        width: 45px;
    }

</style>
<div class="container-fluid">
    <div class="block-header">
        <div class="row clearfix">
            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1" style="margin-right:50px" id="add-file">
                <h2>
                    <a class="btn bg-blue waves-effect target-link"
                        href="{{ url(Request::segment(1) . '#manajemen-file/data-file/add') }}"><i
                            class="material-icons">note_add</i>
                        <span>Tambah File</span>
                    </a>
                </h2>
            </div>
            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                <button onclick="toggleDelete()" class="btn bg-red waves-effect target-link" id="toggle-delete">
                    <i class="material-icons">delete_forever</i>
                    <span>Hapus File</span>
                </button>
            </div>
            <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11">
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
                    <h2>Data File Sub Kategori {{ $sub_category->sub_category_file_name }}</h2>
                </div>
                <div class="body">
                    <form
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/data-file/action-data-file/delete-many/0') }}"
                        id="form-validation" method="POST">
                        @foreach ($files as $file)
                            <div class="row">
                                <p>{{ $file->nm_pengguna }}</p>
                                <hr>
                            </div>
                            <div class="row">
                                @foreach ($file->file_pengguna as $f)
                                    <div class="col-md-3">
                                        <a href="{{ $f->is_google_drive == 1 ? $f->link_file : Storage::disk('spaces')->url($f->link_file) }}"
                                            style="color: inherit;text-decoration: inherit;" target="_blank"
                                            @if ($f->is_google_drive == 0) download="{{ $f->judul }}.{{ $f->extension_file }}" @endif>
                                            @php
                                                $total_file++;
                                            @endphp <div class="file">
                                                <div style="text-align: center;">
                                                    @if ($f->extension_file == 'pdf')
                                                        <i class="material-icons"
                                                            style="color:red;font-size: 45px;width:45px">picture_as_pdf</i>
                                                    @elseif($f->extension_file == 'png' || $f->extension_file == 'jpg' || $f->extension_file == 'jpeg')
                                                        <i class="material-icons"
                                                            style="color:blue;font-size: 45px;width:45px">collections_icon</i>
                                                    @elseif($f->extension_file == 'pptx' || $f->extension_file == 'docx' || $f->extension_file == 'xlsx')
                                                        <i class="material-icons"
                                                            style="color:blue;font-size: 45px;width:45px">description</i>
                                                    @else
                                                        <i class="material-icons"
                                                            style="color:green;font-size: 45px;width:45px">add_to_drive</i>
                                                    @endif
                                                </div>

                                                <div style="text-align: center">
                                                    <span>{{ $f->judul }}</span>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="form-check delete-many"
                                            style="text-align: center;margin-top: 10px; display: none">
                                            <input type="checkbox" class="form-check-input"
                                                value="{{ $f->file_pengguna_id }}" name="id_file[]"
                                                id="id_file[{{ $f->file_pengguna_id }}]" required>
                                            <label class="form-check-label"
                                                for="id_file[{{ $f->file_pengguna_id }}]"></label>
                                            <input type="hidden" name="sub_category_file_id"
                                                value="{{ $sub_category->sub_category_file_id }}">
                                        </div>
                                        <div style="text-align: center;margin-top: 10px;" class="delete-one">
                                            <a
                                                onclick="deleteFile('{{ $f->file_pengguna_id }}','{{ $sub_category->sub_category_file_id }}')">
                                                <i style="color:red;" class="material-icons">cancel</i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </form>
                </div>
                <p>TOTAL FILE : <b>{{ $total_file }}</b></p>
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
        highlight: function(input) {
            $(input).parents('.form-group').addClass('error');
        },
        unhighlight: function(input) {
            $(input).parents('.form-group').removeClass('error');
        },
        errorPlacement: function(error, element) {
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
                complete: function(response) {
                    $('input').removeAttr('readonly', 'readonly');
                    $('button').removeAttr('disabled');
                }
            });
        }
    });

    function deleteFile(id, sub_category_file_id) {
        swal({
                title: "Are you sure?",
                showCancelButton: true
            },
            function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: `{{ Request::segment(1) }}/{{ Request::segment(2) }}/{{ Request::segment(3) }}/action-data-file/delete/0`,
                        type: "post",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="token"]').attr('content')
                        },
                        data: {
                            id_file: id,
                            sub_category_file_id: sub_category_file_id,
                        },

                        success: function(response) {
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
