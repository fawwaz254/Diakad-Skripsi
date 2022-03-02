<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#e-learning/manajemen-materi-ajar')}}"><i class="material-icons">keyboard_backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>
                        EDIT MATERI AJAR
                    </h2>
                </div>
                <div class="body">

                    <form id="form-upload" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-manajemen-materi-ajar/edit/'.$materi_ajar->id_materi_ajar)}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="row clearfix">

                        <div class="col-md-4">
                            <label>Jurusan</label>
                             <select class="form-control show-tick" name="id_jurusan">
                                @foreach($list_jurusan as $r)
                                <option value="{{$r->id_jurusan}}" {{$materi_ajar->id_jurusan == $r->id_jurusan ? 'selected' : ''}}>{{$r->nm_jurusan}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Tingkat</label>
                            <select class="form-control show-tick" name="tingkat">
                            @foreach($list_tingkat as $r)
                                <option value="{{$r->tingkat}}" {{$materi_ajar->tingkat == $r->tingkat ? 'selected' : ''}}>{{$r->tingkat}}</option>
                            @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Status</label>
                            <select class="form-control show-tick" name="status">
                                <option value="0" {{$materi_ajar->status == 0 ? 'selected' : ''}}>Tidak Aktif</option>
                                <option value="1" {{$materi_ajar->status == 1 ? 'selected' : ''}}>Aktif</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>Mata Pelajaran</label>
                            <select class="form-control show-tick" name="id_mata_pelajaran">
                                @foreach($list_mapel as $r)
                                <option value="{{$r->id_mata_pelajaran}}" {{$materi_ajar->id_mata_pelajaran == $r->id_mata_pelajaran ? 'selected' : ''}}>{{$r->nm_mata_pelajaran}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>Judul Materi</label>
                            <input type="text" class="form-control" name="judul_materi" value="{{$materi_ajar->judul_materi}}" required="" aria-required="true" aria-invalid="true">
                        </div>

                    </div>

                    <div class="row clearfix">
                        <div class="col-md-12">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama File</th>
                                        <th>Tipe File</th>
                                        <th>Dilihat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($materi_ajar->materi_ajar_file as $r)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$r->nm_file}}</td>
                                        <td>{{$r->type_file}}</td>
                                        <td>{{$r->views}} kali</td>
                                        <td style="text-align:center;">
                                            <a href="{{Storage::disk('spaces')->url($r->link_file)}}" target="_blank"><button class="btn btn-success" type="button">Download</button></a>
                                            <button class="btn btn-warning lihat" type="button" data-type="{{$r->type_file}}" data-link="{{Storage::disk('spaces')->url($r->link_file)}}">Lihat Disini</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                         </div>
                    </div>                    

                    <hr>

                    <div class="row clearfix">

                        <div class="col-md-5">
                            <label>Nama File</label>
                            <input type="text" class="form-control" name="nm_file[]"  aria-required="true" aria-invalid="true">
                        </div>

                        <div class="col-md-5">
                            <label>File ( pdf, ppt, docx, xlsx | max 10 mb )</label>
                            <input type="file" class="form-control" name="file[]" aria-required="true" aria-invalid="true" accept=".pdf,.doc,.docx,.ppt,.xlsx">
                        </div>

                        <div class="col-md-2" style="margin-top: 23px;">
                            <button class="btn btn-success btn-block" type="button" id="tambah_file"><i class="material-icons">add</i> Tambah</button>
                        </div>

                    </div>

                    <div id="place_file">
                            
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-indigo waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                        </div>
                    </div>

                    </form>
                    
                </div>
            </div>

            <br>

            <div class="card" id="card-materi" style="display:none;">
                <div class="body" id="frame">
                  
                </div>    
            </div>

            <br>

        </div>
    </div>
</div>

@include('scriptjs')

<script type="text/javascript">

    $('.lihat').click(function(){

        $('#card-materi').show();

        var link = $(this).data('link');
        var type = $(this).data('type');

        if(type=='pdf'){
            $('#frame').empty();
            $('#frame').append(`
                <iframe src="`+link+`" style="width:100%; height:535px;" frameborder="0"></iframe>
            `);
        }

        else{
            $('#frame').empty();
            $('#frame').append(`
                  <iframe  src='https://view.officeapps.live.com/op/embed.aspx?src=`+link+`' style="width:100%;" height='535px' frameborder='0'></iframe>
            `);
        }

        
    })

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
                    $('button').removeAttr('disabled');
                }
            });
        }
    });
</script>

<script type="text/javascript">
    
    $('#tambah_file').click(function(){

        $('#place_file').append(`

            <div class="row clearfix">

            <div class="col-md-5">
                <label>Nama File</label>
                <input type="text" class="form-control" name="nm_file[]" aria-required="true" aria-invalid="true">
            </div>

            <div class="col-md-5">
                <label>File</label>
                <input type="file" class="form-control" name="file[]" aria-required="true" aria-invalid="true">
            </div>

            <div class="col-md-2" style="margin-top: 23px;">
                <button class="btn btn-danger btn-block delete_file" type="button"><i class="material-icons">delete</i> Hapus</button>
            </div>

            </div>
        `);

    })

   $("#place_file").on("click", ".delete_file", function() {
        $(this).parent().parent().remove();
    })

</script>