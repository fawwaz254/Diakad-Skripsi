<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#wali-kelas/rekap-pelanggaran-kelas')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        EDIT TINDAKAN PELANGGARAN
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-tindakan-pelanggaran/edit/'.$data_tindakan_pelanggaran->id_tindakan_pelanggaran)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Nama Siswa
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                @if($data_tindakan_pelanggaran->id_pelanggaran_siswa != null)
                                    <input type="text" class="form-control" aria-invalid="true" readonly value="{{$data_tindakan_pelanggaran->nm_siswa}}">
                                @else
                                    <input type="text" class="form-control" aria-invalid="true" readonly value="{{$data_tindakan_pelanggaran->nm_siswa_presensi}}">
                                @endif
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Catatan Pelanggaran
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                @if($data_tindakan_pelanggaran->id_pelanggaran_siswa != null)
                                    <input type="text" class="form-control" aria-invalid="true" readonly value="{{$data_tindakan_pelanggaran->catatan_pelanggaran}}">
                                @else
                                    <input type="text" class="form-control" aria-invalid="true" readonly value="{{$data_tindakan_pelanggaran->catatan_pelanggaran_presensi}}">
                                @endif
                            </div>
                        </div>
                        @if($data_tindakan_pelanggaran->id_pelanggaran_siswa != null)
                            @if($id_pengguna == $data_tindakan_pelanggaran->created_by)
                                <h2 class="card-inside-title">
                                    Catatan Khusus <small><b>* Ditampilkan Khusus, Tidak Untuk Diakses User Lain</b></small>
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <textarea id="editor2" class="editor2" rows="10" cols="80" readonly >{{$data_tindakan_pelanggaran->catatan_pelanggaran_khusus}}</textarea>
                                    </div>
                                </div>
                            @endif
                        @endif
                        <h2 class="card-inside-title">
                            Tanggal Pelanggaran
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" aria-invalid="true" readonly value="{{$tgl_pelanggaran}}" >
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Jenis Tindakan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_jenis_tindakan">
                                    <option value="" disabled selected >-- Pilih Jenis Tindakan --</option>
                                    @foreach($data_jenis_tindakan as $data)
                                        @if($data_tindakan_pelanggaran->id_jenis_tindakan == $data->id_jenis_tindakan)
                                            <option value="{{$data->id_jenis_tindakan}}" selected >{{$data->nm_jenis_tindakan}}</option>
                                        @else
                                            <option value="{{$data->id_jenis_tindakan}}" >{{$data->nm_jenis_tindakan}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Catatan Tindakan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="catatan_tindakan_pelanggaran" required="" aria-required="true" aria-invalid="true" value="{{$data_tindakan_pelanggaran->catatan_tindakan_pelanggaran}}">
                            </div>
                        </div>
                        @if($id_pengguna == $data_tindakan_pelanggaran->created_by_tindakan)
                            <h2 class="card-inside-title">
                                Catatan Tindakan Khusus <small><b>* Ditampilkan Khusus, Tidak Untuk Diakses User Lain</b></small>
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <textarea name="catatan_tindakan_pelanggaran_khusus" id="editor1" class="editor1" rows="10" cols="80">{{$data_tindakan_pelanggaran->catatan_tindakan_pelanggaran_khusus}}</textarea>
                                </div>
                            </div>
                        @endif
                        <h2 class="card-inside-title">
                            Tanggal Tindakan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="datetime-local" class="form-control" name="tgl_tindakan_pelanggaran" required="" aria-required="true" aria-invalid="true" value="{{\Carbon\carbon::parse($tgl_tindakan_pelanggaran)->format('Y-m-d\TH:i')}}">
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
<!-- CKeditor Plugin Js -->
<script src="{{asset('plugins/ckeditor/ckeditor.js')}}"></script>

<script>
CKEDITOR.replace( 'editor1' );
CKEDITOR.replace( 'editor2' );

// custom code to key binding ckeditor
timer = setInterval(updateDiv,100);
function updateDiv(){
    var editorText = CKEDITOR.instances.editor1.getData();
    $('#editor1').val(editorText);
    $('#editor2').val(editorText);
}
</script>

<script>
$(function(){    
    $('.datepicker').bootstrapMaterialDatePicker({
        format: 'DD MMMM YYYY HH:mm:00',
        //lang : 'id',
        clearButton: true,
        weekStart: 1,
        time: true
    });
});
</script>