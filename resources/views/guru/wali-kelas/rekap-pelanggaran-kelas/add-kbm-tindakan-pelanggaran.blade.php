<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#wali-kelas/rekap-pelanggaran-kelas') }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        INPUT TINDAKAN PELANGGARAN KBM
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/action-tindakan-pelanggaran/add-kbm/' . $id_tindakan_pelanggaran) }}">
                        {{ csrf_field() }}
                        <h2 class="card-inside-title">
                            Nama Siswa<sup style="color: red"> Readonly</sup>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" aria-invalid="true" readonly
                                    value="{{ $data_presensi_mp_pelanggaran->nm_siswa }}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Mapel<sup style="color: red"> Readonly</sup>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" aria-invalid="true" readonly
                                    value="{{ $data_presensi_mp_pelanggaran->kd_mata_pelajaran }} - {{ $data_presensi_mp_pelanggaran->nm_mata_pelajaran }}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Catatan Pelanggaran<sup style="color: red"> Readonly</sup>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" aria-invalid="true" readonly
                                    value="{{ $data_presensi_mp_pelanggaran->catatan_pelanggaran }}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal Pelanggaran<sup style="color: red"> Readonly</sup>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" aria-invalid="true" readonly
                                    value="{{ $tgl_pelanggaran }}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Jenis Tindakan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_jenis_tindakan">
                                    <option value="" disabled selected>-- Pilih Jenis Tindakan --</option>
                                    @foreach ($data_jenis_tindakan as $data)
                                        <option value="{{ $data->id_jenis_tindakan }}">{{ $data->nm_jenis_tindakan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Catatan Tindakan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="catatan_tindakan_pelanggaran"
                                    required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Catatan Tindakan Khusus <small><b>* Ditampilkan Khusus, Tidak Untuk Diakses User
                                    Lain</b></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <textarea name="catatan_tindakan_pelanggaran_khusus" id="editor1" class="editor1" rows="10" cols="80"></textarea>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal Tindakan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="datetime-local" class="form-control" name="tgl_tindakan_pelanggaran"
                                    required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="hidden" class="form-control" name="id_presensi_mp_pelanggaran"
                                    required="" aria-required="true" aria-invalid="true"
                                    value="{{ $data_presensi_mp_pelanggaran->id_presensi_mp_pelanggaran }}">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                        class="material-icons">save</i><span>Save</span></button>
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
    $(function() {
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'DD MMMM YYYY HH:mm:00',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: true
        });
    });
</script>
