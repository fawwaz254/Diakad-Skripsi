<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#manajemen-file/data-file')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        TAMBAH FILE
                    </h2>
                </div>
                <div class="body">
                    <form id="form-upload" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/data-file/action-data-file/add/0')}}">
                        {{csrf_field()}}

                        <div class="row clearfix">

                            <div class="col-md-6">
                                <label>Category File</label>
                                <select class="form-control show-tick" name="category" id="category">                            
                                    @foreach($category as $r)
                                    <option value="{{$r->category_file_id}}">{{$r->category_file_name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6" id="sub_category">
                                <label>Sub Category File</label>
                                <select class="form-control show-tick" name="sub_category_file_id" required=""></select>
                            </div>

                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Pilih Upload File Dari Mana</label>
                                <div class="demo-radio-button">
                                    <input name="file_from" onchange="change_file_from()" type="radio" id="radio_4" value="1" checked="" class="with-gap" />
                                    <label for="radio_4">File Dari Komputer</label>
                                    <input name="file_from" onchange="change_file_from()" type="radio" id="radio_5" value="2" class="with-gap" />
                                    <label for="radio_5">File Dari Google Drive</label>
                                </div>
                            </div>
                        </div>
                       
                        <div class="row clearfix" id="place_file">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>File ( pdf , pptx , docx , xlsx , xlsm , png , jpg , jpeg | Max 3 File | max 10 mb )</label>
                                <input type="file" class="form-control" id="file" accept=".pdf, .pptx, .docx, .xlsx, .xlsm, .png, .jpg, .jpeg" name="file[]" multiple/>
                            </div>
                        </div>

                         <div class="row clearfix" style="display:none;" id="place_drive">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Link Google Drive</label>
                                <input type="text" class="form-control" name="link_google_drive" />
                            </div>
                        </div>

                        <div class="row clearfix" style="display: none" id="judul">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Judul</label>
                                <input type="text" class="form-control" name="judul" required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Keterangan</label>
                                <textarea rows="4" cols="50" class="form-control" name="keterangan" required="" aria-required="true"
                                    aria-invalid="true"></textarea>
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
            </div>
        </div>
    </div>
</div>
@include('scriptjs')

<script>    

    function change_file_from(){

        var x = $("input[name='file_from']:checked").val()
        
        if(x==1){
            $('#place_file').show();
            $('#place_drive').hide();
            $('#judul').hide();
            $('#form-upload')[0].reset();
        }
        else{
            $('#place_file').hide();
            $('#place_drive').show();
            $('#judul').show();
        }

    }

    $(document).ready(function () {
        var x = $('#category').val();
        $.ajax({
            url: "{{(Request::segment(1).'/'.Request::segment(2).'/'.Request::segment(3).'/dropdown-category')}}",
            data: {
                category_file_id: x,
            },
            dataType: 'JSON',
            complete: function (data) {
                var html;
                data.responseJSON.forEach(d => {
                    html += `<option value="${d.sub_category_file_id}">${d.sub_category_file_name}</option>`;
                });
                $('#sub_category select').html(html);
            },
        });
    });

    $('#category').change(function () {
        var x = $(this).val();
        $.ajax({
            url: "{{(Request::segment(1).'/'.Request::segment(2).'/'.Request::segment(3).'/dropdown-category')}}",
            data: {
                category_file_id: x,
            },
            dataType: 'JSON',
            complete: function (data) {
                var html;
                data.responseJSON.forEach(d => {
                    html += `<option value="${d.sub_category_file_id}">${d.sub_category_file_name}</option>`;
                });
                $('#sub_category select').empty()
                $('#sub_category select').html(html);
            },
        });
    });
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
                complete: function(response) {
                    $('input').removeAttr('readonly', 'readonly');
                    $('button').removeAttr('disabled');
                }
            });
        }
    });
</script>
