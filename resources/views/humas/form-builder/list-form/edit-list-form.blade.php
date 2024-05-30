<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3)) }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        EDIT FORM
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/list-form/action-list-form/edit/' . $form->id_form) }}">
                        {{ csrf_field() }}
                        <h2 class="card-inside-title">
                            Role
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_role" required="">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id_role }}"
                                            {{ $role->id_role == $form->id_role ? 'selected' : '' }}>
                                            {{ $role->nm_role }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <h2 class="card-inside-title">
                            Nama Form
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_form" required=""
                                    aria-required="true" aria-invalid="true" value="{{ $form->nm_form }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <h2 class="card-inside-title">
                                    Jenis Form
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <select class="form-control show-tick" name="is_harian" required="">
                                            <option value="1" {{ $form->is_harian == '1' ? 'selected' : '' }}>
                                                Harian
                                            </option>
                                            <option value="0" {{ $form->is_harian == '0' ? 'selected' : '' }}>Bebas
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h2 class="card-inside-title">
                                    Status Form
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <select class="form-control show-tick" name="is_aktif" required="">
                                            <option value="1" {{ $form->is_aktif == '1' ? 'selected' : '' }}>
                                                Aktif
                                            </option>
                                            <option value="0" {{ $form->is_aktif == '0' ? 'selected' : '' }}>
                                                Tidak Aktif
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <h2 class="card-inside-title">
                                    Jam Mulai Pengisian
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="input" class="timepicker form-control" name="start_time"
                                            required="" aria-required="true" aria-invalid="true"
                                            value="{{ $form->start_time }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h2 class="card-inside-title">
                                    Jam Akhir Pengisian
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <input type="input" class="timepicker form-control" name="end_time"
                                            required="" aria-required="true" aria-invalid="true"
                                            value="{{ $form->end_time }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col">
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
        $('.timepicker').bootstrapMaterialDatePicker({
            format: 'HH:mm',
            lang: 'id',
            time: true,
            date: false,
            shortTime: false
        });
    });
</script>
