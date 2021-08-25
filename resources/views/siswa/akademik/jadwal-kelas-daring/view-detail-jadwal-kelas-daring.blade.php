<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#'.Request::segment(2).'/jadwal-kelas-daring')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
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

                    </div>  

                    <label>Isi Materi</label>
                    <textarea name="uraian_materi" id="editor1" class="editor1" rows="10" cols="80">{{$data->uraian_materi}}</textarea>

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

                     <div class="row clearfix">
                        <div class="col-md-12">

                            <p>Status Absen</p>

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
CKEDITOR.replace( 'editor1' );

// custom code to key binding ckeditor
timer = setInterval(updateDiv,100);
function updateDiv(){
    var editorText = CKEDITOR.instances.editor1.getData();
    $('#editor1').val(editorText);
}
</script>