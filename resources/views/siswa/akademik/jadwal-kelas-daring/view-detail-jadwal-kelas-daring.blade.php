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
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data_materi as $materi)
                                    <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td><a href="{{$materi->link_materi}}" target="_blank">{{$materi->nm_materi}}</a></td>
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

                            <hr>

                            @if($presensi_mp_siswa->kehadiran==1)

                            Anda sudah melakukan absensi

                            @elseif($presensi_mp_siswa->kehadiran==4 && 
                            (
                            (\Carbon\Carbon::now()->format('Y-m-d') == $presensi_mp_siswa->presensi_mp->tgl_presensi) && 
                            (\Carbon\carbon::now()->format('H:i') < $presensi_mp_siswa->presensi_mp->waktu_selesai) 
                            )
                            )

                            Anda belum melakuan absensi

                            @else

                            Anda tidak mengikuti kelas ini
                           
                            @endif 

                        </div>
                    </div>

                </div>
           
            </div>
        </div>

</div>

@include('scriptjs')
<!-- CKeditor Plugin Js -->
<script src="{{asset('plugins/ckeditor/ckeditor.js')}}"></script>

<script>
CKEDITOR.replace( 'editor1' );

// custom code to key binding ckeditor
timer = setInterval(updateDiv,100);
function updateDiv(){
    var editorText = CKEDITOR.instances.editor1.getData();
    $('#editor1').val(editorText);
}
</script>