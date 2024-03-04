<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3) . '/komponen/' . $id_custom_form) }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        TAMBAH KOMPONEN
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/custom-form/komponen/action/add') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="id_custom_form" value="{{ $id_custom_form }}">

                        <h2 class="card-inside-title">
                            Label Komponen Field
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="label_custom_form_komponen"
                                    required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>

                        <h2 class="card-inside-title">
                            Tipe Komponen Field
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="tipe_custom_form_komponen" required="">
                                    <option selected disabled>Pilih Tipe Input</option>
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
