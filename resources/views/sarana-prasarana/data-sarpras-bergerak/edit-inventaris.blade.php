<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link "
                href="{{ url(Request::segment(1) . '#data-inventaris-bergerak/data-inventaris') }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        EDIT INVENTARIS
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/action-inventaris/edit/' . $data_inventaris_ruangan->id_inventaris_bergerak) }}">
                        {{ csrf_field() }}
                        <h2 class="card-inside-title">
                            Nama Inventaris Bergerak
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_inventaris_ruangan" required=""
                                    aria-required="true" aria-invalid="true"
                                    value="{{ $data_inventaris_ruangan->nm_inventaris_ruangan }}">
                            </div>
                        </div>

                        <h2 class="card-inside-title">
                            Jumlah Inventaris
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="jumlah_inventaris_ruangan" required=""
                                    aria-required="true" aria-invalid="true"
                                    value="{{ $data_inventaris_ruangan->jumlah_inventaris_ruangan }}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kondisi Baik
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="jumlah_kondisi_baik" required=""
                                    aria-required="true" aria-invalid="true"
                                    value="{{ $data_inventaris_ruangan->jumlah_kondisi_baik }}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kondisi Rusak
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="jumlah_kondisi_rusak" required=""
                                    aria-required="true" aria-invalid="true"
                                    value="{{ $data_inventaris_ruangan->jumlah_kondisi_rusak }}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Spesifikasi
                            <small>*Detail Spesifikasi Sarana seperti: ukuran, bahan, dan merk</small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="spesifikasi_inventaris_ruangan"
                                    required="" aria-required="true" aria-invalid="true"
                                    value="{{ $data_inventaris_ruangan->spesifikasi_inventaris_ruangan }}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Keterangan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="keterangan_inventaris_ruangan"
                                    required="" aria-required="true" aria-invalid="true"
                                    value="{{ $data_inventaris_ruangan->keterangan_inventaris_ruangan }}">
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
