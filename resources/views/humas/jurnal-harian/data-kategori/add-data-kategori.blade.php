<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3) ) }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        TAMBAH DATA KATEGORI MAPEL
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/kelompok-jurnal-harian-tendik/action-data-kategori/add/0') }}">
                        {{ csrf_field() }}
                        <h2 class="card-inside-title">
                            Unit Kerja
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_unit_kerja" required="">
                                    @foreach ($unit_kerja as $r)
                                        <option value="{{ $r->id_unit_kerja }}">{{ $r->nm_unit_kerja }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Keterangan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="description"
                                    required="" aria-required="true" aria-invalid="true" value="">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Pilih tendik yang diizinkan mengakses
                        </h2>
                        @foreach ($pengguna as $tendik)
                            <div class="form-check">
                                <input class="form-check-input" name="allowed_tendik[{{ $tendik->id_pengguna }}]"
                                    type="checkbox" value={{ $tendik->id_pengguna }}
                                    id="role-checkbox[{{ $tendik->id_pengguna }}]">
                                <label class="form-check-label"
                                    for="role-checkbox[{{ $tendik->id_pengguna }}]">{{ $tendik->nm_pengguna }}</label>
                            </div>
                        @endforeach
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
