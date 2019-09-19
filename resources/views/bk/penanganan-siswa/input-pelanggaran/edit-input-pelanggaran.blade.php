<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#penanganan-siswa/input-pelanggaran')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-deep-purple">
                    <h2>
                        EDIT PELANGGARAN
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-input-pelanggaran/edit/'.$data_pelanggaran_siswa->id_pelanggaran_siswa)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Semester
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_semester" required="">
                                  <option value="" disabled selected >-- Pilih Semester --</option>
                                    @foreach($data_semester as $data)
                                        @if($data->is_aktif_semester == 1)
                                            @if($data->id_semester == $data_pelanggaran_siswa->id_semester)
                                                <option value="{{$data->id_semester}}" selected>{{$data->tahun_ajaran}} {{$data->nm_semester}} (Aktif)</option>
                                            @else
                                                <option value="{{$data->id_semester}}">{{$data->tahun_ajaran}} {{$data->nm_semester}} (Aktif)</option>
                                            @endif
                                        @else
                                            @if($data->id_semester == $data_pelanggaran_siswa->id_semester)
                                                <option value="{{$data->id_semester}}" selected >{{$data->tahun_ajaran}} {{$data->nm_semester}}</option>
                                            @else
                                                <option value="{{$data->id_semester}}">{{$data->tahun_ajaran}} {{$data->nm_semester}}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kelas
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="kelas" onchange="changeKelas(this)" required="">
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($data_kelas as $data)
                                        @if($data_siswa->id_kelas == $data->id_kelas)
                                        <option value="{{$data->id_kelas}}" selected>{{$data->nm_kelas}}</option>
                                        @else
                                        <option value="{{$data->id_kelas}}">{{$data->nm_kelas}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Siswa
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_siswa" required="">
                                    <option value="">-- Pilih Siswa --</option>
                                    @foreach($data_siswa_sekelas as $data)
                                        @if($data_siswa->id_siswa == $data->id_siswa)
                                        <option value="{{$data->id_siswa}}" selected>{{$data->nm_pengguna}} ({{$data->nis_siswa}})</option>
                                        @else
                                        <option value="{{$data->id_siswa}}">{{$data->nm_pengguna}} ({{$data->nis_siswa}})</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kategori Pelanggaran
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="kategori" onchange="changeKategori(this)">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($data_kategori as $data)
                                        @if($data->id_kategori_pelanggaran == $data_pelanggaran_siswa->id_kategori_pelanggaran)
                                            <option value="{{$data->id_kategori_pelanggaran}}" selected >{{$data->tingkat_kategori_pelanggaran}} - {{$data->nm_kategori_pelanggaran}}</option>
                                        @else
                                            <option value="{{$data->id_kategori_pelanggaran}}">{{$data->tingkat_kategori_pelanggaran}} - {{$data->nm_kategori_pelanggaran}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Sub-Kategori Pelanggaran
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_subkategori_pelanggaran">
                                    <option value="">-- Pilih Sub-Kategori --</option>
                                    @foreach($data_subkategori as $data)
                                        @if($data->id_subkategori_pelanggaran == $data_pelanggaran_siswa->id_subkategori_pelanggaran)
                                            <option value="{{$data->id_subkategori_pelanggaran}}" selected>{{$data->tingkat_kategori_pelanggaran}}.{{$data->tingkat_subkategori_pelanggaran}} {{$data->keterangan_subkategori_pelanggaran}}</option>
                                        @else
                                            <option value="{{$data->id_subkategori_pelanggaran}}">{{$data->tingkat_kategori_pelanggaran}}.{{$data->tingkat_subkategori_pelanggaran}} {{$data->keterangan_subkategori_pelanggaran}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Catatan Pelanggaran
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="catatan_pelanggaran" required="" aria-required="true" aria-invalid="true" value="{{$data_pelanggaran_siswa->catatan_pelanggaran}}">
                            </div>
                        </div>
                        @if($is_khusus == 1)
                            <h2 class="card-inside-title">
                                Catatan Khusus <small><b>* Ditampilkan Khusus, Tidak Untuk Diakses User Lain</b></small>
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <textarea name="catatan_pelanggaran_khusus" id="editor1" class="editor1" rows="10" cols="80">{{$data_pelanggaran_siswa->catatan_pelanggaran_khusus}}</textarea>
                                </div>
                            </div>
                        @endif
                        <h2 class="card-inside-title">
                            Tanggal Pelanggaran
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_pelanggaran" required="" aria-required="true" aria-invalid="true" value="{{$tgl_pelanggaran}}">
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

// custom code to key binding ckeditor
timer = setInterval(updateDiv,100);
function updateDiv(){
    var editorText = CKEDITOR.instances.editor1.getData();
    $('#editor1').val(editorText);
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

function changeKelas(el){
    $.ajax({
        url: '{{url(Request::segment(1).'/'.Request::segment(2).'/siswa-bykelas')}}',
        type: 'POST',
        data: {
            kelas: $('select[name=kelas]').val()
        },
        success: function(result) {
            $('select[name=id_siswa]').html('');
            var html = '<option value="">-- Pilih Siswa --</option>';
            $.each(result, function( key, item ) {
                html += '<option value="'+item.id_siswa+'">'+item.nm_pengguna+' ('+item.nis_siswa+')</option>'
            });
            $('select[name=id_siswa]').html(html);
        }
    });
}

function changeKategori(el){
    $.ajax({
        url: '{{url(Request::segment(1).'/'.Request::segment(2).'/subkategori-bykategori')}}',
        type: 'POST',
        data: {
            kategori: $('select[name=kategori]').val()
        },
        success: function(result) {
            $('select[name=id_subkategori_pelanggaran]').html('');
            var html = '<option value="">-- Pilih Sub-Kategori --</option>';
            $.each(result, function( key, item ) {
                html += '<option value="'+item.id_subkategori_pelanggaran+'">'+item.tingkat_kategori_pelanggaran+'.'+item.tingkat_subkategori_pelanggaran+' '+item.keterangan_subkategori_pelanggaran+'</option>'
            });
            $('select[name=id_subkategori_pelanggaran]').html(html);
        }
    });
}
</script>