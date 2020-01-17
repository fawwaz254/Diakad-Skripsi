<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#magang-siswa/rekanan-magang')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        EDIT REKANAN MAGANG
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-rekanan-magang/edit/'.$data_rekanan_magang->id_rekanan_magang)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Nama Rekanan Magang
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_rekanan_magang" required="" aria-required="true" aria-invalid="true" value="{{$data_rekanan_magang->nm_rekanan_magang}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kuota Rekanan Magang
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="kuota_rekanan_magang" required="" aria-required="true" aria-invalid="true" value="{{$data_rekanan_magang->kuota_rekanan_magang}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nomor Telepon Rekanan Magang
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nomor_telp_rekanan_magang" required="" aria-required="true" aria-invalid="true" value="{{$data_rekanan_magang->nomor_telp_rekanan_magang}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nomor HP Rekanan Magang
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nomor_hp_rekanan_magang" required="" aria-required="true" aria-invalid="true" value="{{$data_rekanan_magang->nomor_hp_rekanan_magang}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Alamat Rekanan Magang
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="alamat_rekanan_magang" required="" aria-required="true" aria-invalid="true" value="{{$data_rekanan_magang->alamat_rekanan_magang}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal Awal Kerjasama
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_awal_kerjasama" required="" aria-required="true" value="{{$tgl_mulai}}"
                                        aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal Akhir Kerjasama
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_akhir_kerjasama" required="" aria-required="true" value="{{$tgl_selesai}}"
                                        aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Contact Person Rekanan Magang
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="contact_person_rekanan_magang" required="" aria-required="true" aria-invalid="true" value="{{$data_rekanan_magang->contact_person_rekanan_magang}}">
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
<script>
    $(function(){
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'DD MMMM YYYY',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: false
        });
    });
</script>
