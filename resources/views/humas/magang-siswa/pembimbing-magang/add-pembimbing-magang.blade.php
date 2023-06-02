<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#magang-siswa/rekanan-magang') }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        TAMBAH PEMBIMBING MAGANG
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/action-input-pembimbing-magang/add/0') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="username" value="{{ $randomString }}">
                        <input type="hidden" name="id_rekanan_magang"
                            value="{{ $data_pengambil_magang->rekanan->id_rekanan_magang }}">
                        <input type="hidden" name="id_periode_magang"
                            value="{{ $data_pengambil_magang->periode->id_periode_magang }}">
                        <h2 class="card-inside-title">
                            Nama Pembimbing Magang
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_pembimbing_magang" required=""
                                    aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Username
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="usernameView" required=""
                                    aria-required="true" aria-invalid="true" value="{{ $randomString }}" disabled>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Rekanan Magang
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_rekanan_magang" required=""
                                    aria-required="true" aria-invalid="true"
                                    value="{{ $data_pengambil_magang->rekanan->nm_rekanan_magang }}" disabled>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Periode Magang
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_periode_magang" required=""
                                    aria-required="true" aria-invalid="true"
                                    value="{{ $data_pengambil_magang->periode->nm_periode_magang }}" disabled>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Semester
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="semester" required=""
                                    aria-required="true" aria-invalid="true"
                                    value="{{ $data_pengambil_magang->periode->semester->tahun_ajaran . ' ' . $data_pengambil_magang->periode->semester->nm_semester }}"
                                    disabled>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
<script>
    $(function() {
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'DD MMMM YYYY',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: false
        });
    });
</script>
