<style>
    .card .card-inside-title {
        margin-top: 10px;
        margin-bottom: 10px;
    }
</style>
<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#pendaftaran/petugas-penerimaan/' . $penerimaan->id_penerimaan) }}"><i
                    class="material-icons">keyboard_backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>PETUGAS PENERIMAAN - TAMBAH PETUGAS</h2>
                </div>

                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/petugas-penerimaan/' . $penerimaan->id_penerimaan . '/add') }}">
                        {{ csrf_field() }}
                        <input name="id_penerimaan" type="hidden" value="{{ $penerimaan->id_penerimaan }}">

                        <h2 class="card-inside-title">
                            Penerimaan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="penerimaan" aria-required="true"
                                    aria-invalid="true" value="{{ $penerimaan->nm_penerimaan }}" disabled>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Semester
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="semester" aria-required="true"
                                    aria-invalid="true"
                                    value="{{ $penerimaan->nm_semester_penerimaan . ', ' . $penerimaan->tahun_penerimaan }}"
                                    disabled>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Jabatan Petugas
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control selectpicker show-tick" name="jabatan_petugas">
                                    <option value="1">Admin</option>
                                    <option value="2">Verifikator</option>
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Petugas
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control selectpicker show-tick" id="id_pengguna_petugas"
                                    data-live-search="true" name="id_pengguna_petugas">
                                    <option value="">-</option>
                                    @forelse($pengguna as $pg)
                                        @php
                                            $nip = $nip = !empty($pg->nip_guru) ? $pg->nip_guru : (!empty($pg->nip_staff) ? $pg->nip_staff : '');
                                        @endphp
                                        <option value="{{ $pg->id_pengguna }}" data-tokens="{{ $pg->nm_pengguna }}"
                                            data-nip="{{ $nip }}"
                                            data-pg="{{ $pg->status_join_table == '1' ? 'Guru' : 'Staff' }}">
                                            {{ $pg->nm_pengguna }}</option>
                                    @empty
                                        <option value="">Tidak ada data pengguna</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        <div class="hide" id="details">
                            <h2 class="card-inside-title">
                                NIP/NIK
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" id="nip" name="nip"
                                        aria-required="true" aria-invalid="true" value="" disabled>
                                </div>
                            </div>

                            <h2 class="card-inside-title">
                                Status Kepegawaian
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" id="status" name="status"
                                        aria-required="true" aria-invalid="true" value="" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                        class="material-icons">save</i><span>Save</span></button>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <a class="btn bg-blue btn-block waves-effect target-link"
                                    href="{{ url(Request::segment(1) . '#pendaftaran/petugas-penerimaan/' . $penerimaan->id_penerimaan) }}"><i
                                        class="material-icons">cancel</i><span>Cancel</span></a>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
</div>

@include('scriptjs')
<script>
    $('#id_pengguna_petugas').on('change', function(e) {
        var optionSelected = $(this).find("option:selected");
        $('#nip').val(optionSelected.data('nip'));
        $('#status').val(optionSelected.data('pg'));
        $('#details').removeClass('hide');
    });

    $(function() {
        $('.selectpicker').selectpicker();
    });
</script>
