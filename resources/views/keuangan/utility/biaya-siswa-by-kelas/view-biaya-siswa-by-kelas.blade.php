<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        FILTER DATA BIAYA
                    </h2>
                </div>
                <div class="body">
                    <h2 class="card-inside-title">
                        Kelas
                    </h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <select class="form-control show-tick" name="id_kelas" onchange="filterAction()">
                                <option value="notset">-- Pilih Kelas --</option>
                                @foreach ($data_kelas as $data)
                                    <option value="{{ $data->id_kelas }}">
                                        {{ $data->nm_kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>DATA BIAYA SISWA</h2>
                </div>
                <div class="body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#belum" data-toggle="tab" aria-expanded="true">
                                <i class="material-icons">cancel_presentation</i> BELUM SET BIAYA
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#sudah" data-toggle="tab">
                                <i class="material-icons">done_all</i> SUDAH SET BIAYA
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="belum">
                            <div class="body">
                                <form id="primary_form" method="POST"
                                    action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/action-batch-biaya-siswa/set') }}">
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
                                                    <th>Nama Siswa</th>
                                                    <th>Gender</th>
                                                    <th>Kelas</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <h2 class="card-inside-title">
                                        Kelompok Biaya
                                    </h2>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select class="form-control show-tick" name="id_kelompok_biaya">
                                                <option value="" disabled selected>-- Pilih Kelompok Biaya --
                                                </option>
                                                @foreach ($data_kelompok_biaya as $data)
                                                    <option value="{{ $data->id_kelompok_biaya }}">
                                                        {{ $data->nm_kelompok_biaya }}</option>
                                                    {{-- @if ($data->status_kelompok_biaya == 1)
                                                        <option value="{{$data->id_kelompok_biaya}}">{{$data->nm_kelompok_biaya}} (Reguler)</option>
                                                    @else
                                                        <option value="{{$data->id_kelompok_biaya}}">{{$data->nm_kelompok_biaya}} (Khusus)</option>
                                                    @endif --}}
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
                                                    <th>Nama Siswa</th>
                                                    <th>Gender</th>
                                                    <th>Kelas</th>
                                                    <th>Kelompok Biaya</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <h2 class="card-inside-title">
                                        Edit Kelompok Biaya
                                    </h2>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select class="form-control show-tick" name="id_kelompok_biaya">
                                                <option value="" disabled selected>-- Pilih Kelompok Biaya --
                                                </option>
                                                @foreach ($data_kelompok_biaya as $data)
                                                    @if ($data->status_kelompok_biaya == 1)
                                                        <option value="{{ $data->id_kelompok_biaya }}">
                                                            {{ $data->nm_kelompok_biaya }} </option>
                                                    @else
                                                        <option value="{{ $data->id_kelompok_biaya }}">
                                                            {{ $data->nm_kelompok_biaya }} </option>
                                                    @endif
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
        var action_url = "{{ url(Request::segment(1) . '/' . Request::segment(2) . '/action-batch-biaya-siswa') }}";
        var mode = $(el).attr('data-mode');
        $('#secondary_form').attr('action', action_url + '/' + mode);
        $('#secondary_form').submit();
    }
</script>
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var modul_url = 'utility';
    var datatable_url_belum = base_url + '/' + role_url + '/' + modul_url + '/' + 'biaya-siswa/datatables-belum';
    var datatable_url_sudah = base_url + '/' + role_url + '/' + modul_url + '/' + 'biaya-siswa/datatables-sudah';
    var set_url = role_url + '#' + modul_url + '/' + 'biaya-siswa/set';
    var edit_url = role_url + '#' + modul_url + '/' + 'biaya-siswa/edit';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-biaya-siswa/delete';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        // serverSide: true,
        ajax: {
            url: datatable_url_belum,
            type: 'GET',
            data: function(params) {
                params.id_kelas = encodeURIComponent($('select[name=id_kelas]').val());
            },
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
                data: 'nis_siswa',
                name: 'siswa.nis_siswa'
            },
            {
                data: 'nm_pengguna',
                name: 'pengguna.nm_pengguna'
            },
            {
                data: 'jenis_kelamin',
                name: 'calon_siswa_baru.jenis_kelamin'
            },
            {
                data: 'nm_kelas',
                name: 'kelas.nm_kelas'
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
        ajax: {
            url: datatable_url_sudah,
            type: 'GET',
            data: function(params) {
                params.id_kelas = encodeURIComponent($('select[name=id_kelas]').val());
            },
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
                data: 'nis_siswa',
                name: 'siswa.nis_siswa'
            },
            {
                data: 'nm_pengguna',
                name: 'pengguna.nm_pengguna'
            },
            {
                data: 'jenis_kelamin',
                name: 'calon_siswa_baru.jenis_kelamin'
            },
            {
                data: 'nm_kelas',
                name: 'kelas.nm_kelas'
            },
            {
                data: 'kelompok_biaya',
                name: 'kelompok_biaya.nm_kelompok_biaya'
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

    function filterAction() {
        primary_table.ajax.reload(null, false);
        secondary_table.ajax.reload(null, false);
    }

    function deleteActionBiaya(delete_url, element) {
        var item = $(element);
        $('button').attr('disabled', 'disabled');

        swal({
            title: "Are you sure?",
            text: "You won't be able to delete this!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(result) {
            if (result) {
                $.ajax({
                    type: "POST",
                    url: delete_url + '/' + item.attr('data-id'),
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
                            secondary_table.ajax.reload(null, false);
                        } else if (response.status == 300) {
                            vex.dialog.alert(response.message);
                        }
                    },
                    complete: function() {
                        $('button').removeAttr('disabled', 'disabled');
                    }
                });
            } else {
                $('button').removeAttr('disabled', 'disabled');
            }
        });
    }
</script>
