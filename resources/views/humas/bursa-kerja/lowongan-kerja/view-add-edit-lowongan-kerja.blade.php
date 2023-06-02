<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3)) }}">
                <i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        {{ !empty($item) ? 'EDIT' : 'TAMBAH' }} LOWONGAN KERJA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-upload" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/action') }}/{{ !empty($item) ? 'edit' : 'add' }}"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @if (!empty($item))
                            <input type="hidden" name="id_lowongan_kerja[]" value="{{ $item->id_lowongan_kerja }}">
                        @endif
                        <h2 class="card-inside-title">
                            Judul Lowongan Kerja
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="judul_lowongan_kerja[]" required=""
                                    aria-required="true" aria-invalid="true"
                                    value="{{ !empty($item) ? $item->judul_lowongan_kerja : '' }}">
                            </div>
                        </div>

                        <h2 class="card-inside-title">
                            Poster Lowongan Kerja
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="file" class="form-control" name="file[]" aria-required="true"
                                    aria-invalid="true">
                            </div>
                        </div>

                        @if (!empty($item))
                            @if ($item->poster_lowongan_kerja)
                                <h2 class="card-inside-title">
                                    Preview Poster Lowongan Kerja Sebelumnya
                                </h2>
                                @php
                                    $ext = pathinfo($item->poster_lowongan_kerja, PATHINFO_EXTENSION);
                                @endphp

                                @if ($ext == 'pdf' || $ext == 'doc' || $ext == 'docx')
                                    <a href="{{ Storage::disk('spaces')->url($item->poster_lowongan_kerja) }}"
                                        target="_blank"> <i class="material-icons"
                                            style="font-size: 60px;">insert_drive_file</i></a>
                                @else
                                    <a href="{{ Storage::disk('spaces')->url($item->poster_lowongan_kerja) }}"
                                        target="_blank"><img
                                            src="{{ Storage::disk('spaces')->url($item->poster_lowongan_kerja) }}"
                                            style="width: 300px;height: 300px;"></a>
                                @endif

                            @endif

                        @endif

                        <h2 class="card-inside-title">
                            Deskripsi Lowongan Kerja
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <textarea id="editor1" class="editor1" name="deskripsi_lowongan_kerja[]" required="">
                          {{ !empty($item) ? $item->deskripsi_lowongan_kerja : '' }}
                        </textarea>

                        <div id="dynamic-input" class="">
                            
                        </div>

                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                                class="material-icons">save</i><span>Save</span></button>
                                    </div><div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <button class="btn btn-block bg-blue waves-effect" id="add"><i
                                                class="material-icons">add</i><span>Tambah</span></button>
                                    </div>
                                </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CKeditor Plugin Js -->
<script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>

<script>
    CKEDITOR.replace('editor1');

    // custom code to key binding ckeditor
    timer = setInterval(updateDiv, 100);

    function updateDiv() {
        var editorText = CKEDITOR.instances.editor1.getData();
        $('#editor1').val(editorText);
    }
</script>

<script>
    $(document).ready(function() {

        let counter = 1;
        $('#add').click(function(e) {
            e.preventDefault();
            counter++;
            let html = `<div class="dynamic-input-${counter}">
                            <hr>
                            <h2 class="card-inside-title">
                                Judul Lowongan Kerja
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="judul_lowongan_kerja[]" required=""
                                        aria-required="true" aria-invalid="true"
                                        value="">
                                </div>
                            </div>
    
                            <h2 class="card-inside-title">
                                Poster Lowongan Kerja
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="file" class="form-control" name="file[]" aria-required="true"
                                        aria-invalid="true">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Deskripsi Lowongan Kerja
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <textarea id="editor${counter}" class="editor${counter}" name="deskripsi_lowongan_kerja[]" required="">
                            </textarea>
                        </div>`;

            $('#dynamic-input').append(html);
            var $ckfield = CKEDITOR.replace(`editor${counter}`);
            $ckfield.on('change', function() {
                $ckfield.updateElement();         
            });

            // // custom code to key binding ckeditor
            // timer = setInterval(updateDiv, 100);

            // function updateDiv() {
            //     var editorText = CKEDITOR.instances.editor${counter}.getData();
            //     $(`editor${counter}`).val(editorText);
            // }
        })

        $('#remove').click(function(e) {
            e.preventDefault();
            $(`.dynamic-input-${counter}`).remove();
            counter--;
        });
    });
</script>

{{-- <script>
    $(document).ready(function() {

        let counter = 1;
        $('#add').click(function(e) {
            e.preventDefault();
            counter++;
            let html = `<div class="dynamic-input-${counter}">
                            <hr>
                            <h2 class="card-inside-title">
                                Judul Lowongan Kerja
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="judul_lowongan_kerja[]" required=""
                                        aria-required="true" aria-invalid="true"
                                        value="">
                                </div>
                            </div>
    
                            <h2 class="card-inside-title">
                                Poster Lowongan Kerja
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="file" class="form-control" name="file[]" aria-required="true"
                                        aria-invalid="true">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Deskripsi Lowongan Kerja
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <textarea id="editor${counter}" class="editor${counter}" name="deskripsi_lowongan_kerja[]" required="">
                            </textarea>
                        </div>`;

            $('#dynamic-input').append(html);
            CKEDITOR.replace(`editor${counter}`);

            // custom code to key binding ckeditor
            // timer = setInterval(updateDiv, 100);

            function updateDiv() {
                var editorText = CKEDITOR.instances.editor1.getData();
                $(`editor${counter}`).val(editorText);
            }
        })

        $('#remove').click(function(e) {
            e.preventDefault();
            $(`.dynamic-input-${counter}`).remove();
            counter--;
        });
    });
</script> --}}


<script type="text/javascript">
    $('#form-upload').submit(function(e) {
        e.preventDefault();
    }).validate({
        highlight: function(input) {
            $(input).addClass('is-danger');
        },
        unhighlight: function(input) {
            $(input).removeClass('is-danger');
        },
        errorPlacement: function(error, element) {
            $(element).parents('.control').addClass('help').addClass('is-danger').append(error);
        },
        submitHandler: function(form) {
            $('button').attr('disabled', 'disabled');

            var formData = new FormData(form);

            setTimeout(() => {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    enctype: 'multipart/form-data',
                    data: formData,
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
                            setTimeout(() => {
                                loadURI(response.path);
                            }, 2000);
                        } else if (response.status == 203) {
                            vex.dialog.alert(response.message);
                            primary_table.ajax.reload(null, false);
                        } else if (response.status == 204) {
                            loadURI(response.path);
                        } else if (response.status == 300) {
                            vex.dialog.alert(response.message);
                        }
                    },
                    complete: function() {
                        $('button').removeAttr('disabled');
                    }
                });

            }, 1000);
        }
    });
</script>
