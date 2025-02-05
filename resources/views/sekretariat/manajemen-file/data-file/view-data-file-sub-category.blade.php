@php
    $total_file = 0;
@endphp
<style type="text/css">
    .file:hover,
    .delete-one:hover,
    .download:hover {
        transform: scale(1.2);
    }

    .file,
    .delete-one {
        cursor: pointer;
    }

    i.file.material-icons {
        width: 45px;
    }

    .list-view {
        display: flex;
        flex-direction: column;
        margin: 0 -15px;
    }

    .list-view .file:hover {
        transform: scale(1.1);
    }

    .list-view .file {
        width: 100%;
        display: flex;
        align-items: center;
        margin: 5px 0;
    }
    
    .list-view .file-item {
        display: flex;
        width: 100%;
        justify-content: space-between;
        align-items: center;
    }
    
    .list-view .file i {
        margin-right: 15px;
        font-size: 35px;
    }

    .list-view .file span {
        font-size: 16px;
    }

    .view-toggle-btn {
        background: none;
        border: 1px solid #ddd;
        padding: 8px 12px;
        margin-left: 8px;
        transition: all 0.3s ease;
    }

    .view-toggle-btn.active {
        background: #00b0e4;
        color: white;
        border-color: #00b0e4;
    }
</style>

<div class="container-fluid">
    <div class="block-header">
        <div class="row clearfix">
            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1" style="margin-right:50px" id="add-file">
                <h2>
                    <a class="btn bg-blue waves-effect target-link" href="{{ url(Request::segment(1) . '#manajemen-file/data-file/add') }}">
                        <i class="material-icons">note_add</i>
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
                <button type="submit" form="form-validation" class="btn bg-red waves-effect target-link" id="delete-many-files-btn" style="display: none" onclick="deleteManyFiles()">
                    <i class="material-icons">delete_forever</i>
                    <span>Hapus File</span>
                </button>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header" style="display: flex; justify-content: space-between; align-items: center;">
                    <h2>Data File Sub Kategori {{ $sub_category->sub_category_file_name }}</h2>
                    <div>
                        <button class="view-toggle-btn active" id="gridViewBtn" onclick="toggleGridView()">
                            <i class="material-icons">apps</i>
                        </button>
                        <button class="view-toggle-btn" id="listViewBtn" onclick="toggleListView()">
                            <i class="material-icons">list</i>
                        </button>
                    </div>
                </div>
                <div class="body">
                    <div id="fileContainer"class="row" style="padding-left: 15px; padding-right: 15px;">
                        <form action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/data-file/action-data-file/delete-many/0') }}" id="form-validation" method="POST" style="overflow-x: hidden;">
                            @foreach ($files as $file)
                                    <p>{{ $file->nm_pengguna }}</p>
                                    <hr>
                                <div class="row">
                                    @foreach ($file->file_pengguna as $f)
                                        <div class="col-md-3 file-item">
                                            <a href="{{ $f->is_google_drive == 1 ? $f->link_file : Storage::disk('spaces')->url($f->link_file) }}" style="color: inherit;text-decoration: inherit;" target="_blank">
                                                @php
                                                    $total_file++;
                                                @endphp 
                                                <div class="file">
                                                    <div style="text-align: center;">
                                                        @if ($f->extension_file == 'pdf')
                                                            <i class="material-icons" style="color:red;font-size: 45px;width:45px">picture_as_pdf</i>
                                                        @elseif($f->extension_file == 'png' || $f->extension_file == 'jpg' || $f->extension_file == 'jpeg')
                                                            <i class="material-icons" style="color:blue;font-size: 45px;width:45px">collections_icon</i>
                                                        @elseif($f->extension_file == 'pptx' || $f->extension_file == 'docx' || $f->extension_file == 'xlsx')
                                                            <i class="material-icons" style="color:blue;font-size: 45px;width:45px">description</i>
                                                        @else
                                                            <i class="material-icons" style="color:green;font-size: 45px;width:45px">add_to_drive</i>
                                                        @endif
                                                    </div>
                                                    <div style="text-align: center">
                                                        <span>{{ $f->judul }}</span>
                                                    </div>
                                                 </div>
                                            </a>
                                            <div>
                                                <div class="form-check delete-many" style="text-align: center;margin-top: 10px; display: none">
                                                    <input type="checkbox" class="form-check-input" value="{{ $f->file_pengguna_id }}" name="id_file[]" id="id_file[{{ $f->file_pengguna_id }}]" required>
                                                    <label class="form-check-label" for="id_file[{{ $f->file_pengguna_id }}]"></label>
                                                    <input type="hidden" name="sub_category_file_id" value="{{ $sub_category->sub_category_file_id }}">
                                                </div>
                                                @if ($f->is_google_drive)
                                                <div style="text-align: center;margin-top: 10px;" class="delete-one">
                                                    <a onclick="deleteFile('{{ $f->file_pengguna_id }}','{{ $sub_category->sub_category_file_id }}')">
                                                        <i style="color:red;" class="material-icons">cancel</i>
                                                    </a>
                                                </div>
                                                @else
                                                <div class="row align-items-center">
                                                    <div style="text-align: right;margin-top: 10px;" class="download col-md-6">
                                                        <a href="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/data-file/download/' . $f->file_pengguna_id) }}" target="_blank">
                                                            <i style="color:blue;" class="material-icons">download_for_offline</i>
                                                        </a>
                                                    </div>
                                                    <div style="text-align: left;margin-top: 10px;" class="delete-one col-md-6">
                                                        <a onclick="deleteFile('{{ $f->file_pengguna_id }}','{{ $sub_category->sub_category_file_id }}')">
                                                            <i style="color:red;" class="material-icons">cancel</i>
                                                        </a>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </form>
                    </div>
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
            $('.download').hide()
            $('.delete-many').show()
            $('#delete-many-files-btn').show()
            $('#add-file').hide()
            $('#toggle-delete').html(`
                <i class="material-icons">do_disturb</i>
                <span>Batal</span>
            `)
        } else {
            $('.download').show()
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

    function deleteFile(id, sub_category_file_id) {
        swal({
            title: "Are you sure?",
            showCancelButton: true
        },
            function (isConfirm) {
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

    const gridViewBtn = document.getElementById("gridViewBtn");
    const listViewBtn = document.getElementById("listViewBtn");
    const fileContainer = document.getElementById("fileContainer");
    const savedView = localStorage.getItem('viewPreference') || 'grid';

    function applyView(view) {
        if (view === 'grid') {
            fileContainer.classList.remove('list-view');
            fileContainer.classList.add('grid-view');
            gridViewBtn.classList.add('active');
            listViewBtn.classList.remove('active');
        } else {
            fileContainer.classList.remove('grid-view');
            fileContainer.classList.add('list-view');
            listViewBtn.classList.add('active');
            gridViewBtn.classList.remove('active');
        }
    }

    function toggleGridView() {
        localStorage.setItem('viewPreference', 'grid');
        applyView('grid');
    }

    function toggleListView() {
        localStorage.setItem('viewPreference', 'list');
        applyView('list');
    }

    applyView(savedView);
</script>