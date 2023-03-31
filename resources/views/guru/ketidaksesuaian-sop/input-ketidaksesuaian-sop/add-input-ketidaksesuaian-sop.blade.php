<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/input-ketidaksesuaian-sop') }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        INPUT KETIDAKSESUAIAN SOP
                    </h2>
                </div>
                <div class="body">
                    <form id="form-upload" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/input-ketidaksesuaian-sop/action/add/0') }}">
                        {{ csrf_field() }}

                        <div class="row clearfix">
                            <div class="col-md-6">
                                <label>Unit Kerja</label>
                                <select class="form-control show-tick" name="unitKerja" onchange="changeUnitKerja(this)"
                                    required="">
                                    <option value="">-- Pilih Unit Kerja --</option>
                                    <option value="guru">Guru</option>
                                    <option value="tendik">Tendik</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Nama Pengguna</label>
                                <select class="form-control show-tick" name="id_pengguna" required="">
                                    <option value="">-- Pilih Pengguna --</option>
                                </select>
                            </div>
                        </div>


                        <h2 class="card-inside-title">
                            Catatan Pelanggaran
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="catatan" required=""
                                    aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal Pelanggaran
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl" required=""
                                    aria-required="true" aria-invalid="true">
                            </div>
                        </div>

                        <h2 class="card-inside-title">
                            File Pendukung ( png , jpg , jpeg | max 5 mb )
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>
                                    <input type="file" class="form-control" name="file" />
                                </label>
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
            format: 'DD MMMM YYYY HH:mm:00',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: true
        });
    });

    function changeUnitKerja(el) {
        $.ajax({
            url: '{{ url(Request::segment(1) . '/' . Request::segment(2) . '/input-ketidaksesuaian-sop/getUnitKerja') }}',
            type: 'POST',
            data: {
                unitKerja: $('select[name=unitKerja]').val()
            },
            success: function(result) {
                $('select[name=id_pengguna]').html('');
                var html = '<option value="">-- Pilih Pengguna --</option>';
                $.each(result, function(key, item) {
                    html += '<option value="' + item.id_pengguna + '">' + item.nm_pengguna +
                        '  </option>'
                });
                $('select[name=id_pengguna]').html(html);
            }
        });
    }

    $('#form-upload').validate({
        highlight: function(input) {
            $(input).parents('.form-group').addClass('error');
        },
        unhighlight: function(input) {
            $(input).parents('.form-group').removeClass('error');
        },
        errorPlacement: function(error, element) {
            $(element).parents('.form-group').append(error);
        },
        submitHandler: function(form) {
            $('button').attr('disabled', 'disabled');
            $.ajax({
                url: form.action,
                type: form.method,
                enctype: 'multipart/form-data',
                data: new FormData($('#form-upload')[0]),
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status == 200) {
                        vex.dialog.alert(response.message);
                    } else if (response.status == 201) {
                        vex.dialog.alert(response.message);
                        window.location.href = response.link;
                    } else if (response.status == 202) {
                        vex.dialog.alert(response.message);
                        loadURI(response.path);
                    } else if (response.status == 203) {
                        vex.dialog.alert(response.message);
                        primary_table.ajax.reload(null, false);
                    } else if (response.status == 204) {
                        loadURI(response.path);
                    } else if (response.status == 300) {
                        vex.dialog.alert(response.message);
                    }
                },
                complete: function() {
                    $('input').removeAttr('readonly', 'readonly');
                    $('button').removeAttr('disabled');
                }
            });
        }
    });
</script>
