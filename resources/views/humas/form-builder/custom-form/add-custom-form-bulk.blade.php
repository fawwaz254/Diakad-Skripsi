@push('assets')
<style>

</style>
@endpush

<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3)) }}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <form id="form-validation" method="POST" action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/custom-form/action/add') }}">
                <div class="card">
                    {{ csrf_field() }}

                    <div class="header" style="border-top: 8px solid #555;">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input style="height: 50px; font-size:x-large; outline: none; border: none; width: 100%; " placeholder="Formulir Tanpa Judul" type="text" class="" name="nm_custom_form" required="" aria-required="true" aria-invalid="true" value="">
                            </div>
                        </div>
                    </div>
                    <div class="body">

                        <h2 style="font-size:large">
                            Pengaturan
                        </h2>


                        <h2 class="card-inside-title">
                            Ditujukkan Kepada
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_role" required="">
                                    <option selected disabled>Pilih Role</option>
                                    @foreach ($roles as $role)
                                    <option value="{{ $role->id_role }}">{{ $role->nm_role }}</option>
                                    @endforeach
                                    <option value="PUBLIC">Public</option>
                                </select>
                            </div>
                        </div>

                        <h2 class="card-inside-title">
                            Status
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="is_aktif" required="">
                                    <option value="1">Aktif</option>
                                    <option value="0">Non-aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                                <h2 class="card-inside-title">
                                    Jam Mulai Pengisian
                                </h2>
                                <div>
                                    <input type="input" class="timepicker form-control" name="start_time" required="" aria-required="true" aria-invalid="true">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                                <h2 class="card-inside-title">
                                    Jam Akhir Pengisian
                                </h2>
                                <div>
                                    <input type="input" class="timepicker form-control" name="end_time" required="" aria-required="true" aria-invalid="true">
                                </div>
                            </div>

                        </div>




                        <!-- <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div> -->
                    </div>
                </div>

                <div class="card" style="margin: 15px 0;">
                    <div class="header">
                        <div class="row clearfix">
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                <input style="padding: 5px;border-radius: 5px; height: max-content; background-color: rgba(204, 204, 204, 0.2); font-size:larger; outline: none; border: none; width: 100%; border-bottom: 2px solid rgba(204, 204, 204, 0.35);" placeholder="Pertanyaan Tanpa Judul" type="text" class="" name="nm_custom_form" required="" aria-required="true" aria-invalid="true" value="">
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <select class="form-control show-tick" name="tipe_custom_form_komponen" required="">
                                    <option value="text">Text (BASIC)</option>
                                    <option value="number">Number (BASIC)</option>
                                    <option value="select">Select (BASIC)</option>
                                    <option value="checkbox">Checkbox (BASIC)</option>
                                    <option value="custom_kelas">Kelas (CUSTOM)</option>
                                    <option value="custom_siswa">Siswa (CUSTOM)</option>
                                    <option value="custom_ttd">Tanda Tangan (CUSTOM)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_pertanyaan_form" required="" aria-required="true" aria-invalid="true" value="">
                            </div>
                        </div>
                    </div>
                    <div class="footer">
                        <div class="row clearfix">
                            <div class="p-5">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('scriptjs')
<script>
    $(function() {
        $('.timepicker').bootstrapMaterialDatePicker({
            format: 'HH:mm',
            lang: 'id',
            time: true,
            date: false,
            shortTime: false
        });
    });
</script>