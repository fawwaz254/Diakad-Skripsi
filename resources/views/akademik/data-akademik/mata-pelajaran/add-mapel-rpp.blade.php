<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/mata-pelajaran/rpp?id=' . $mata_pelajaran->id_mata_pelajaran) }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        TAMBAH Desain Pembelajaran
                    </h2>
                </div>
                <div class="body">
                    <form id="form-upload" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/mata-pelajaran/rpp/action/add/new') }}"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <input type="hidden" name="id_mata_pelajaran"
                            value="{{ $mata_pelajaran->id_mata_pelajaran }}" />
                            <input type="hidden" name="role" value='{{ Request::segment(1) }}'>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Semester <span style="color:red">*</span></label>
                                <select class="form-control show-tick" name="id_semester">
                                    <option value="0">Pilih Mapel</option>
                                    @foreach ($data_semester as $semester)
                                        <option value="{{ $semester->id_semester }}"
                                            {{ $semester->isAktif() ? 'selected' : '' }}>
                                            {{ $semester->semesterLengkap() }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>File Desain Pembelajaran *</label>
                                <a href="{{ url('excel/ContohUploadRPP.xls') }}">Download template di sini</a>
                                <input type="file" class="form-control show-tick" name="file" />
                                <small style="color:red">Perhatian: nilai karakter yang diinput hanya bisa 4C (Komunikasi, Kolaborasi, Berpikir kritis, Kreatif)</small>
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

<script>
    let role = '{{ Request::segment(1) }}';
    $('#form-upload').submit(function(e) {
        e.preventDefault();
    }).validate({
        highlight: function(input) {
            $(input).addClass('is-danger');
        },
        unhighlight: function(input) {
            $(input).removeClass('is-danger');
        },
        errorPlacement: function(error, element) {
            $(element).parents('.control').addClass('help').addClass('is-danger').append(error);
        },
        submitHandler: function(form) {
            $('button').attr('disabled', 'disabled');

            setTimeout(() => {
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
                            console.log("Redirecting to:", response.path);
                            loadURI(response.path);
                        } else if (response.status == 203) {
                            vex.dialog.alert(response.message);
                            primary_table.ajax.reload(null, false);
                        } else if (response.status == 204) {
                            loadURI(response.path);
                        } else if (response.status == 300) {
                            vex.dialog.alert(response.message);
                        }
                        console.log('Server response:', response);
                    },
                    complete: function() {
                        $('button').removeAttr('disabled');
                    }
                });

            }, 1000);
        }
    });
</script>
