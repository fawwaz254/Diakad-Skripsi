<style>
    .status-header {
        margin: 0 1.5rem;
        font-size: 18px;
        font-weight: normal;
        color: #111;
    }
</style>

<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/list') }}">
                <i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        {{ !empty($kerjasama) ? 'EDIT' : 'TAMBAH' }} KERJASAMA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" class="row"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2)) }}/{{ !empty($kerjasama) ? 'update/' . $kerjasama->id_kerjasama : 'store' }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="id_kerjasama"
                            value="{{ !empty($kerjasama) ? $kerjasama->id_kerjasama : '' }}">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title"> Nama Kerjasama </h2>
                            <input type="text" class="form-control" name="nm_kerjasama" aria-required="true"
                                aria-invalid="true" value="{{ !empty($kerjasama) ? $kerjasama->nm_kerjasama : '' }}"
                                required="">
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title"> Instansi </h2>
                            <div class="form-group">
                                <div class="form-line">
                                    <select class="form-control show-tick" name="id_instansi">
                                        <option value="" selected disabled> Pilih Instansi </option>
                                        @foreach ($data_instansi as $instansi)
                                            <option value="{{ $instansi->id_instansi }}" required=""
                                                {{ isset($kerjasama) && $kerjasama->id_instansi == $instansi->id_instansi ? 'selected' : '' }}>
                                                {{ $instansi->nm_instansi }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title"> Jenis Kerjasama </h2>
                            <div class="form-group">
                                <div class="form-line">
                                    <select class="form-control show-tick" name="id_jenis_kerjasama">
                                        <option value="" selected disabled> Pilih Jenis Kerjasama </option>
                                        @foreach ($data_jenis_kerjasama as $jenis_kerjasama)
                                            <option value="{{ $jenis_kerjasama->id_jenis_kerjasama }}"
                                                {{ isset($kerjasama) && $kerjasama->id_jenis_kerjasama == $jenis_kerjasama->id_jenis_kerjasama ? 'selected' : '' }}>
                                                {{ $jenis_kerjasama->nm_jenis_kerjasama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title"> Tanggal Awal Kerjasama </h2>
                            <input type="date" class="form-control" name="tanggal_kerjasama" aria-required="true"
                                aria-invalid="true" required=""
                                value="{{ !empty($kerjasama) ? $kerjasama->tanggal_kerjasama : '' }}">
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title"> Tanggal Akhir Kerjasama </h2>
                            <input type="date" class="form-control" name="tanggal_akhir_kerjasama"
                                aria-required="true" aria-invalid="true" required=""
                                value="{{ !empty($kerjasama) ? $kerjasama->tanggal_akhir_kerjasama : '' }}">
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title"> Status </h2>
                            <input id="aktif" class="with-gap radio-col-light-green form-control validate"
                                type="radio" name="status" value="1" required=""
                                {{ isset($kerjasama) && $kerjasama->status == '1' ? 'checked' : '' }}>
                            <label for="aktif"> Aktif </label>
                            <input id="non-aktif" class="with-gap radio-col-light-green form-control validate"
                                type="radio" name="status" value="0" required="required"
                                {{ isset($kerjasama) && $kerjasama->status == '0' ? 'checked' : '' }}>
                            <label for="non-aktif"> Tidak Aktif </label>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <button id="submit" class="btn btn-block bg-red waves-effect" type="submit">
                                <i class="material-icons">save</i><span> {{ !empty($kerjasama) ? 'Update' : 'Save' }}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
