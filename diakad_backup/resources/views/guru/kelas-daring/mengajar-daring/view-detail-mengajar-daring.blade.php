<div class="container-fluid">

    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#'.Request::segment(2).'/mengajar-daring')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
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

                    <label>Catatan (bisa digunakan untuk pembagian link zoom atau catatan tugas ke murid atau keperluan lainya )</label>
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

                </div>
           
            </div>
        </div>

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
                        <input type="hidden" name="id_kelas_mp_grup" value="{{$data->kelas_mp->id_kelas_mp_grup}}">
                        <input type="hidden" name="id_presensi_mp" value="{{$data->id_presensi_mp}}">

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
                    <h2>ABSENSI SISWA</h2>
                </div>

                <div class="body">

                    @if($data->tgl_presensi < \Carbon\Carbon::now()->format('Y-m-d') || ( $data->tgl_presensi == \Carbon\Carbon::now()->format('Y-m-d') && ($data->waktu_selesai <= Carbon\Carbon::now()->format('H:i')) ) )

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Keterangan</th>
                                    @if($data->is_task==1)
                                    <th>Tugas</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($peserta as $r)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$r->siswa->pengguna->nm_pengguna}}</td>
                                        <td>{{$r->siswa->kelas->nm_kelas}}</td>
                                        @if($r->kehadiran == 1)
                                        <td class="is-center bg-light-green" style="text-align:center;">H</td>
                                        @elseif($r->kehadiran == 2)
                                        <td class="is-center bg-amber" style="text-align:center;">S</td>
                                        @elseif($r->kehadiran == 3)
                                        <td class="is-center bg-cyan" style="text-align:center;">I</td>
                                        @elseif($r->kehadiran == 4)
                                        <td class="is-center bg-red" style="text-align:center;">A</td>
                                        @endif
                                        @if($data->is_task==1)
                                        @if($r->link_tugas)
                                        <td style="text-align:center;"> <a href="{{Storage::disk('spaces')->url($r->link_tugas)}}" style="font-size:40px;" target="_blank"><i class="material-icons">description</i></a></td>
                                        @else
                                        <td></td>
                                        @endif
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @else

                    <p>Hasil Absen Akan Keluar Pada Tanggal {{$data->tgl_presensi}} Jam {{$data->waktu_selesai}}</p>

                    @endif

                </div>

            </div>
        </div>

    </div>

<!--     <div class="row clearfix">
        
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

    </div> -->

</div>

@include('scriptjs')

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