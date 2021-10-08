<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#'.Request::segment(2).'/jadwal-kelas-daring')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>

    <div class="row clearfix">

        <div class="col-md-6">
            <div class="card is-gap">

                <div class="header">
                    <h2>
                        STATUS ABSEN
                    </h2>
                </div>

                <div class="body">
                    
                        <div class="row clearfix">
                            <div class="col-md-12">

                                <div class="alert alert-warning">
                                    <strong>Catatan!</strong> Anda akan dianggap melakuan absensi ketika mendownload semua file materi
                                </div>

                                <hr>

                                @if($presensi_mp_siswa->kehadiran==1)

                                <p id="tulisan_absen">Anda sudah melakukan absensi</p>

                                @elseif($presensi_mp_siswa->kehadiran==4 && 
                                (
                                (\Carbon\Carbon::now()->format('Y-m-d') == $presensi_mp_siswa->presensi_mp->tgl_presensi) && 
                                (\Carbon\carbon::now()->format('H:i') < $presensi_mp_siswa->presensi_mp->waktu_selesai) 
                                )
                                )

                                <p id="tulisan_absen">Anda belum melakuan absensi</p>

                                @else

                                <p id="tulisan_absen">Anda tidak mengikuti kelas ini</p>
                               
                                @endif 

                            </div>
                        </div>

                </div>

            </div>
        </div>

        <div class="col-md-6">
                
            <div class="card is-gap">

                <div class="header">
                    <h2>
                        UPLOAD TUGAS
                    </h2>
                </div>

                <div class="body">

                    @if($data->is_task == 1)

                    @if($presensi_mp_siswa->link_tugas)

                    <center>
                        <p>Tugas Saya</p>
                        <a href="{{Storage::disk('spaces')->url($presensi_mp_siswa->link_tugas)}}" style="font-size:40px;" target="_blank"><i class="material-icons">description</i></a>
                    </center>

                    @else
                    <form id="form-upload" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/jadwal-kelas-daring/upload-tugas/'.$presensi_mp_siswa->id_presensi_mp_siswa)}}" enctype="multipart/form-data">

                    {{csrf_field()}}

                    <div class="row clearfix">
                          <div class="col-md-12">
                            <label>Upload File</label>
                            <input type="file" class="form-control" name="file" />
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Upload</span></button>
                        </div>
                    </div>

                    </form>
                    @endif

                    @else

                    <center>
                        <h5>Tidak ada tugas pada kelas ini</h5>
                    </center>

                    @endif

                </div>

            </div>

        </div>

    </div>

    <div class="row clearfix">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">

                <div class="header">
                    <h2>
                        DATA DETAIL
                    </h2>
                </div>

                <div class="body">

                     <div class="row clearfix">

                        <div class="col-md-4">
                            <label>Nama Kelas Daring</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" value="{{$data->kelas_mp->kelas_mp_grup->nm_kelas_mp_grup}}" />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label>Tanggal Pertemuan</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" 
                                    value="{{date('d M Y',strtotime($data->tgl_presensi))}} {{$data->waktu_mulai}} - {{$data->waktu_selesai}} " />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label>Pertemuan Ke</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" value="{{$data->pertemuan_ke}}" />
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Catatan</label>
                                <div class="form-line">
                                    <textarea rows="4" class="form-control no-resize" ><?php echo strip_tags($data->uraian_materi)?></textarea>
                                </div>
                            </div>
                        </div>

                    </div>  

                    @if($data_materi->count()>0)
                    <br>

                    <div class="row clearfix">
                        <div class="col-md-12">

                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th style="width:10%">No</th>
                                        <th>File Materi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data_materi as $materi)
                                    <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$materi->nm_materi}}</td>
                                    @if($loop->last)
                                    <td><button class="btn btn-success download-materi" data-link="{{$materi->link_materi}}"> <i class="material-icons">file_download</i><span>Download</span></button></td>
                                    @else>
                                    <td><a href="{{$materi->link_materi}}" class="btn btn-success" target="_blank"><i class="material-icons">file_download</i><span>Download</span></a></td>
                                    @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                    @endif

                </div>
           
            </div>
        </div>

        <input type="hidden" id="id_presensi_mp_siswa" value="{{$presensi_mp_siswa->id_presensi_mp_siswa}}">

</div>



@include('scriptjs')
<!-- CKeditor Plugin Js -->
<script src="{{asset('plugins/ckeditor/ckeditor.js')}}"></script>

<script type="text/javascript">


    $('.download-materi').click(function(){

        var link = $(this).data('link');
        var id = $('#id_presensi_mp_siswa').val();  
        var url = base_url+'/siswa/akademik/jadwal-kelas-daring/download/'+id;

        $.ajax({
            url : url,
            type : 'get',
            dataType :'json',
            success : function(response){
                vex.dialog.alert('anda berhasil melakukan absensi pada kelas ini');
                $('#tulisan_absen').html(`Anda sudah melakukan absensi`);
                window.open(link);
            },
            error:function(){
                alert('mohon maaf terjadi kesahalahan, silahkan hubungi admin');
            }
        })

    })


</script>

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
