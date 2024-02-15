<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3)) }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>TAMBAH SISWA ASRAMA</h2>
                </div>
                <div class="body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#belum" data-toggle="tab" aria-expanded="true">
                                <i class="material-icons">cancel_presentation</i> BELUM SET RUANGAN
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#sudah" data-toggle="tab">
                                <i class="material-icons">done_all</i> SUDAH SET RUANGAN
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="belum">
                            <div class="body">
                                <form id="primary_form" method="POST"
                                    action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/data-siswa-asrama/ruang-asrama-siswa/set') }}">
                                    {{ csrf_field() }}
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover dataTable display"
                                            id="primary_table">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>
                                                        <input id="primary_checkbox_all" type="checkbox"
                                                            name="select_all" class="filled-in">
                                                        <label for="primary_checkbox_all"
                                                            style="margin-bottom: -10px;"></label>
                                                    </th>
                                                    <th>NIS Siswa</th>
                                                    <th>Kelas</th>
                                                    <th>Nama Siswa</th>

                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <h2 class="card-inside-title">
                                        Ruangan
                                    </h2>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select class="form-control show-tick" name="id_ruangan">
                                                <option value="" disabled selected>-- Pilih Ruangan --
                                                </option>
                                                @foreach ($list_ruangan as $ruangan)
                                                    <option value="{{ $ruangan->id_ruangan }}">
                                                        {{ $ruangan->nm_ruangan }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                                class="material-icons">save</i><span>Apply</span></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="sudah">
                            <div class="body">
                                <form id="secondary_form" method="POST" action="">
                                    {{ csrf_field() }}
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover dataTable display"
                                            id="secondary_table" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>
                                                        <input id="secondary_checkbox_all" type="checkbox"
                                                            name="select_all" class="filled-in">
                                                        <label for="secondary_checkbox_all"
                                                            style="margin-bottom: -10px;"></label>
                                                    </th>
                                                    <th>NIS Siswa</th>
                                                    <th>Kelas</th>
                                                    <th>Nama Siswa</th>
                                                    <th>Ruangan</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <h2 class="card-inside-title">
                                        Edit Ruangan
                                    </h2>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select class="form-control show-tick" name="id_ruangan">
                                                <option value="" disabled selected>-- Pilih Ruangan --
                                                </option>
                                                @foreach ($list_ruangan as $ruangan)
                                                    <option value="{{ $ruangan->id_ruangan }}">
                                                        {{ $ruangan->nm_ruangan }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <button class="btn btn-block bg-blue waves-effect" type="button"
                                                onclick="submitSecondaryForm(this)" data-mode="edit"><i
                                                    class="material-icons">edit</i><span>Simpan Edit</span></button>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <button class="btn btn-block bg-red waves-effect" type="button"
                                                onclick="submitSecondaryForm(this)" data-mode="delete"><i
                                                    class="material-icons">delete_forever</i><span>Hapus</span></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#primary_checkbox_all').change(function() {
        var rows = primary_table.rows({
            'search': 'applied'
        }).nodes();

        $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });

    $('#secondary_checkbox_all').change(function() {
        var rows = secondary_table.rows({
            'search': 'applied'
        }).nodes();

        $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });

    $('#primary_form').validate({
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
                        primary_table.ajax.reload(null, false);
                        secondary_table.ajax.reload(null, false);
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
                    $('button').removeAttr('disabled');
                }
            });
        }
    });

    $('#secondary_form').validate({
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
                        primary_table.ajax.reload(null, false);
                        secondary_table.ajax.reload(null, false);
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
                    $('button').removeAttr('disabled');
                }
            });
        }
    });

    function submitSecondaryForm(el) {
        var action_url =
            "{{ url(Request::segment(1) . '/' . Request::segment(2) . '/data-siswa-asrama/ruang-asrama-siswa') }}";
        var mode = $(el).attr('data-mode');

        $('#secondary_form').attr('action', action_url + '/' + mode);
        $('#secondary_form').submit();
    }

    var modul_url = 'asrama';
    var datatable_belum = base_url + '/' + role_url + '/' + modul_url + '/' +
        'data-siswa-asrama/datatables-ruangan/belum';
    var datatable_sudah = base_url + '/' + role_url + '/' + modul_url + '/' +
        'data-siswa-asrama/datatables-ruangan/sudah';
    var set_url = role_url + '#' + modul_url + '/' + 'biaya-siswa/set';


    var primary_table = $('#primary_table').DataTable({
        processing: true,
        // serverSide: true,
        aLengthMenu: [
            [25, 50, 100, 200, -1],
            [25, 50, 100, 200, "All"]
        ],
        // iDisplayLength: -1,
        ajax: {
            url: datatable_belum,
            type: 'GET',

        },
        columns: [{
                data: 'index_table',
                defaultContent: '',
                searchable: false,
                orderable: false
            },
            {
                data: 'checkbox',
                name: 'checkbox',
                searchable: false,
                orderable: false,
                render: function(data, type, full, meta) {
                    return '<input id="checkbox-' + data.id_siswa +
                        '" type="checkbox" name="id_siswa[]" class="filled-in" value="' + data
                        .id_siswa + '">' +
                        '<label for="checkbox-' + data.id_siswa + '"></label>';

                }
            },

            {
                data: 'nis_siswa'
            },
            {
                data: 'kelas.nm_kelas',
            },
            {
                data: 'pengguna.nm_pengguna',

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
            primary_table.cell(cell).invalidate('dom');
        });
    }).draw();

    var secondary_table = $('#secondary_table').DataTable({
        processing: true,
        serverSide: true,
        aLengthMenu: [
            [25, 50, 100, 200, -1],
            [25, 50, 100, 200, "All"]
        ],
        // iDisplayLength: -1,
        ajax: {
            url: datatable_sudah,
            type: 'GET',
        },
        columns: [{
                data: 'index_table',
                defaultContent: '',
                searchable: false,
                orderable: false
            },
            {
                data: 'checkbox',
                name: 'checkbox',
                searchable: false,
                orderable: false,
                render: function(data, type, full, meta) {
                    // return '';
                    return '<input id="checkbox-' + data.id_siswa +
                        '" type="checkbox" name="id_siswa[]" class="filled-in" value="' + data
                        .id_siswa + '">' +
                        '<label for="checkbox-' + data.id_siswa + '"></label>';

                }
            },
            {
                data: 'nis_siswa'
            },
            {
                data: 'kelas.nm_kelas',
            },
            {
                data: 'pengguna.nm_pengguna',

            },
            {
                data: 'ruangan',
            }

        ]
    });

    secondary_table.on('draw', function() {
        secondary_table.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        });
    }).draw();
</script>
