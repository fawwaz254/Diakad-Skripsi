<div class="container-fluid">

    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#'.Request::segment(2).'/mengajar-daring')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">

                <div class="header">
                    <h2>
                        UPLOAD MATERI
                    </h2>
                </div>

                <div class="body">
                    
                    <form id="form-upload" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/jadwal-kelas/materi/action/add-file')}}" enctype="multipart/form-data">

                        {{csrf_field()}}
                        <input type="hidden" name="id_kelas_mp_grup" value="0">
                        <input type="hidden" name="id_presensi_mp" value="0">

                        <div class="row clearfix">
                            <div class="col-md-6">
                                 <label>Nama File</label>
                                <input type="text" class="form-control" name="nm_materi" required="" aria-required="true" aria-invalid="true" >
                            </div>
                              <div class="col-md-6">
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
                                    <input type="text" class="form-control" disabled="" value="{{$data->kelas_mp->kelas_mp_grup->nm_kelas_mp_grup}}" />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label>Tanggal Pertemuan</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" disabled="" class="form-control" 
                                    value="{{date('d M Y',strtotime($data->tgl_presensi))}} {{$data->waktu_mulai}} - {{$data->waktu_selesai}} " />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label>Pertemuan Ke</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input type="text" class="form-control" disabled="" value="{{$data->pertemuan_ke}}" />
                                </div>
                            </div>
                        </div>

                    </div> 

                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/jadwal-kelas/materi/action/add-materi')}}" enctype="multipart/form-data">
                    {{csrf_field()}} 

                    <input type="hidden" name="id_kelas_mp_grup" value="{{$data->kelas_mp->id_kelas_mp_grup}}">
                    <input type="hidden" name="id_presensi_mp" value="{{$data->id_presensi_mp}}">

                    <label>Isi Materi</label>
                    <textarea name="uraian_materi" id="editor1" class="editor1" rows="10" cols="80">{{$data->uraian_materi}}</textarea>

                    <br>

                    <div class="row clearfix">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Simpan Materi</span></button>
                        </div>
                    </div>

                    </form>

                    <br>

                    @if($data_materi->count()>0)
                    <br>

                    <div class="row clearfix">
                        <div class="col-md-12">
                            <label>File file materi</label>
                            <ol>
                                @foreach($data_materi as $materi)
                                <li><a href="{{$materi->link_materi}}" target="_blank">{{$materi->nm_materi}}</a></li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                    @endif

                </div>
           
            </div>
        </div>

    </div>

    <div class="row clearfix">
        
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">

                <div class="header">
                    <h2>STATUS KELAS</h2>
                </div>
                
                <div class="body">

                    <div class="row clearfix">

                        <div class="col-md-4">
                            <label>Status Kelas</label>
                            <div class="form-group">
                                <div class="form-line">
                                    @if($data->tgl_entry)
                                     <input type="text" disabled="" class="form-control" value="Sudah Diadakan" />
                                    @else
                                     <input type="text" disabled="" class="form-control" value="Belum Diadakan" />
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if(!$data->tgl_entry)
                        <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/mengajar-daring/change-status/'.$data->id_presensi_mp)}}" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="col-md-4">
                            <button class="btn btn-danger" type="submit" style="margin-top:27px">Ubah Kelas Menjadi Sudah Di adakan</button>
                        </div>
                        </form>
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