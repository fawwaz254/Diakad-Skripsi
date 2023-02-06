<style>
    .is-required {
        color: red;
    }
</style>
<div class="container-fluid">
    <div class="block-header">
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        UPDATE DATA SISWA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/must-update-biodata/' . $siswa->nis_siswa) }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="demo-color-box bg-success">
                            WAJIB DIISI
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    NOMOR HP ORANG TUA<span class="is-required">*</span><br>
                                    <small>Diisi nomor telepon selular (milik orangtua, atau wali) tanpa tanda
                                        baca, contoh: 0815555555555</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="number" class="form-control" name="nomor_hp_ortu" aria-required="true"
                                    aria-invalid="true" value="{{ $siswa->wali_murid->nomor_hp_wali_murid ?? null }}"
                                    required>
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    NAMA ORANG TUA<span class="is-required">*</span><br>
                                    <small>Diisi Nama pemilik nomor hp</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="nm_ortu" aria-required="true"
                                    aria-invalid="true" value="{{ $siswa->wali_murid->nm_wali_murid ?? null }}"
                                    required>
                            </div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <h2 class="card-inside-title">
                                    EMAIL SISWA <span class="is-required">*</span><br>
                                    <small>digunakan untuk reset password jika lupa, harus email aktif</small>
                                </h2>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <input type="email" class="form-control" name="email_pengguna" aria-required="true"
                                    aria-invalid="true" value="{{ $siswa->pengguna->email_pengguna ?? null }}">
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        {{ csrf_field() }}
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
<script type="text/javascript">
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
