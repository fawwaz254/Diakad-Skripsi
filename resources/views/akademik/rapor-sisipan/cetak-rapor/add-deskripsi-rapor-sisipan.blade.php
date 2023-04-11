<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#rapor-sisipan/cetak-rapor/viewDeskripsi') }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        EDIT DESKRIPSI
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/cetak-rapor/actionDeskripsi/add/0') }}">

                        {{ csrf_field() }}
                        <h2 class="card-inside-title">
                            Mata Pelajaran
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="nm_mata_pelajaran">
                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                    @foreach ($mata_pelajaran as $mapel)
                                        <option value="{{ $mapel->nm_mata_pelajaran }}"> {{ $mapel->nm_mata_pelajaran }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tingkat
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="tingkat" required=""
                                    aria-required="true" aria-invalid="true" value="">

                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            KD
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="kd_deskripsi" required=""
                                    aria-required="true" aria-invalid="true" value="">

                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Deskripsi 1
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="deskripsi1" required=""
                                    aria-required="true" aria-invalid="true" value="">

                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Deskripsi 2
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="deskripsi2" required=""
                                    aria-required="true" aria-invalid="true" value="">

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
