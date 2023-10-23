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
                        TAMBAH FORM
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/list-form/action-list-form/add/0') }}">
                        {{ csrf_field() }}
                        <h2 class="card-inside-title">
                            Role
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_role" required="">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id_role }}">{{ $role->nm_role }}</option>
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
                                    aria-required="true" aria-invalid="true" value="">
                            </div>
                        </div>

                        <h2 class="card-inside-title">
                            Jenis Form
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="is_harian" required="">
                                    <option value="1">Harian</option>
                                    <option value="0">Bebas</option>
                                </select>
                            </div>
                        </div>

                        <h2 class="card-inside-title">
                            Jam Mulai Pengisian
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="input" class="timepicker form-control" name="start_time" required=""
                                    aria-required="true" aria-invalid="true" value="05:00">
                            </div>
                        </div>

                        <h2 class="card-inside-title">
                            Jam Akhir Pengisian
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="input" class="timepicker form-control" name="end_time" required=""
                                    aria-required="true" aria-invalid="true" value="22:00">
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
