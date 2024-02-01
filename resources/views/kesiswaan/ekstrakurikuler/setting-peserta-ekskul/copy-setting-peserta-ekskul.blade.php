<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link "
                href="{{ url(Request::segment(1) . '#ekstrakurikuler/setting-peserta-ekskul/view-ekskul/' . $semester_aktif->id_semester . '/' . $id_ekskul) }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        Copy Peserta Ekskul {{ $ekskul_pilih->nm_ekskul }}
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/post-view-copy-setting-peserta-ekskul') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="id_ekskul" value="{{ $id_ekskul }}">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Dari Semester</label>
                                <select class="form-control show-tick" name="id_semester" required="">
                                    @foreach ($data_semester as $data)
                                        @if (
                                            $data->id_semester == $semester_aktif->id_semester ||
                                                $data->thn_akademik_semester > $semester_aktif->thn_akademik_semester)
                                            @continue
                                        @endif
                                        <option value="{{ $data->id_semester }}"
                                            @if ($data->id_semester == $id_semester) selected @endif>
                                            {{ $data->tahun_ajaran }}
                                            {{ $data->nm_semester }}
                                        </option>
                                    @endforeach
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

            @if ($id_ekskul != null && $id_semester != null)
                <div class="card">
                    <div class="body">
                        <div class="row clearfix">
                            <div class="body">
                                <form form id="form-validation1" method="POST"
                                    action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/action-setting-peserta-ekskul/copy/' . $id_ekskul) }}">
                                    {{ csrf_field() }}
                                    <div class="table-responsive">
                                        <table
                                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                                            id="primary_table">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>
                                                        <input id="checkbox_select_all" type="checkbox"
                                                            name="select_all" class="filled-in">
                                                        <label for="checkbox_select_all"
                                                            style="margin-bottom: -10px;"></label>
                                                    </th>
                                                    <th>NIS - Nama Siswa</th>
                                                    <th>Kelas</th>
                                                </tr>
                                            </thead>
                                        </table>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                                    class="material-icons">content_copy</i><span>Simpan</span></button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
</div>
@include('scriptjs')
<script>
    var id_ekskul = {!! json_encode($id_ekskul) !!};
    var id_semester = {!! json_encode($id_semester) !!};

    var modul_url = 'ekstrakurikuler';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'setting-peserta-ekskul/copy/datatables/' +
        id_semester + '/' + id_ekskul;


    var primary_table = $('#primary_table').DataTable({
        lengthMenu: [
            [-1, 10, 25, 50],
            ['All', 10, 25, 50],
        ],
        processing: true,
        serverSide: true,
        responsive: false,
        ajax: {
            url: datatable_url,
            type: 'GET'
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'checkbox',
                name: 'checkbox',
                searchable: false,
                orderable: false,
                render: function(data, type, full, meta) {
                    return '<input id="checkbox-' + data.id +
                        '" type="checkbox" name="id_siswa[]" class="filled-in" value="' + data.id +
                        '">' +
                        '<label for="checkbox-' + data.id + '"></label>';

                }
            },
            {
                data: 'nm_siswa',
                name: 'nm_siswa'
            },
            {
                data: 'nm_kelas',
                name: 'nm_kelas'
            },

        ]
    });

    primary_table.on('draw', function() {
        primary_table.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        });
    }).draw();
</script>
<script type="text/javascript">
    $(document).ready(function() {
        /* Select All Checkbox */
        $('input[name="select_all"]').change(function() {
            var select_all_checked = this.checked;
            var rows = primary_table.rows({
                'search': 'applied'
            }).nodes();

            $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });
    });
</script>
<script>
    $('#form-validation1').validate({
        rules: {
            'checkbox': {
                required: true
            },
            'gender': {
                required: true
            }
        },
        highlight: function(input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function(input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function(error, element) {
            $(element).parents('.form-group').append(error);
        },
        submitHandler: function(form) {
            $('button').attr('disabled', 'disabled');
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
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
                    $('button').removeAttr('disabled', 'disabled');
                }
            });
        }
    });
</script>
