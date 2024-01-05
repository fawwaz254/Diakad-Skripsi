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
                        EDIT CUSTOM FORM
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/custom-form/action/edit/' . $form->id_custom_form) }}">
                        {{ csrf_field() }}

                        <h2 class="card-inside-title">
                            Nama Form
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_custom_form" required=""
                                    aria-required="true" aria-invalid="true" value="{{ $form->nm_custom_form }}">
                            </div>
                        </div>

                        <h2 class="card-inside-title">
                            Role
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_role" required="">
                                    <option selected disabled>Pilih Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id_role }}"
                                            {{ $form->id_role == $role->id_role ? 'selected' : '' }}>
                                            {{ $role->nm_role }}</option>
                                    @endforeach
                                    <option value="PUBLIC" {{ $form->id_role == 'PUBLIC' ? 'selected' : '' }}>Public
                                    </option>
                                </select>
                            </div>
                        </div>

                        <h2 class="card-inside-title">
                            Status
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="is_aktif" required="">
                                    <option value="1" {{ $form->id_role == '1' ? 'selected' : '' }}>Aktif
                                    </option>
                                    <option value="0" {{ $form->id_role == '0' ? 'selected' : '' }}>Non-aktif
                                    </option>
                                </select>
                            </div>
                        </div>

                        <h2 class="card-inside-title">
                            Jam Mulai Pengisian
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="input" class="timepicker form-control" name="start_time" required=""
                                    aria-required="true" aria-invalid="true" value="{{ $form->start_time }}">
                            </div>
                        </div>

                        <h2 class="card-inside-title">
                            Jam Akhir Pengisian
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="input" class="timepicker form-control" name="end_time" required=""
                                    aria-required="true" aria-invalid="true" value="{{ $form->end_time }}">
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
        $('.timepicker').bootstrapMaterialDatePicker({
            format: 'HH:mm',
            lang: 'id',
            time: true,
            date: false,
            shortTime: false
        });
    });
</script>
