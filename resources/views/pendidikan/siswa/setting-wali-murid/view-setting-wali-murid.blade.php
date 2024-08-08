<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link "
                href="{{ url(Request::segment(1) . '#siswa/setting-wali-murid') }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#siswa/setting-wali-murid/upload-setting-wali-murid/' . $id_jurusan . '/' . $id_kelas) }}"><i
                    class="material-icons">cloud_upload</i><span>Upload Wali Murid</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>DATA WALI MURID {{ $kelas ? 'KELAS ' . $kelas->nm_kelas : '' }}</h2>
                </div>
                <div class="body">
                    <form id="form-validation1" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/post-view-setting-wali-murid') }}">
                        {{ csrf_field() }}
                        <h2 class="card-inside-title">
                            Jurusan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_jurusan" onchange="changeJurusan(this)">
                                    <option value="0" @if ($id_jurusan == '0') selected="" @endif>--
                                        Semua---</option>
                                    @foreach ($data_jurusan as $data)
                                        <option value="{{ $data->id_jurusan }}"
                                            @if ($data->id_jurusan == $id_jurusan) selected="" @endif>{{ $data->nm_jurusan }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kelas
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_kelas">
                                    <option value="0" @if ($id_kelas == '0') selected="" @endif>-- Semua
                                        --
                                    </option>
                                    @foreach ($data_kelas as $data)
                                        <option value="{{ $data->id_kelas }}"
                                            @if ($data->id_kelas == $id_kelas) selected="" @endif>
                                            {{ $data->nm_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                        class="material-icons">save</i><span>Filter</span></button>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No. </th>
                                    <th style="text-align: center">
                                        <input id="checkbox_select_all_primary_table" type="checkbox" name="select_all"
                                            class="filled-in">
                                        <label for="checkbox_select_all_primary_table"
                                            style="margin-bottom: -10px;"></label>
                                    </th>
                                    <th>NIS</th>
                                    <th>KELAS</th>
                                    <th>Nama Siswa</th>
                                    <th>Nama Wali Murid</th>
                                    <th>Telp Wali Murid</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-blue waves-effect" onclick="deleteWaliMuridCollect()"><i
                                    class="material-icons">update</i><span>Delete Walimurid Kolektif</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var id_kelas = '{{ $id_kelas }}';
    var id_jurusan = '{{ $id_jurusan }}';

    var modul_url = 'siswa';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'setting-wali-murid/datatables/' +
        id_jurusan + '/' + id_kelas;
    var edit_url = role_url + '#' + modul_url + '/' + 'setting-wali-murid/edit';
    var delete_url = role_url + '/' + modul_url + '/' + 'action-setting-wali-murid/delete';

    var primary_table = $('#primary_table').DataTable({
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
                class: 'text-center',
                searchable: false,
                orderable: false,
                render: function(data, type, full, meta) {
                    if (data.wali_murid) {
                        return '<input id="checkbox-' + data.id_siswa +
                            '" type="checkbox" name="id_siswa" class="filled-in" value="' + data
                            .id_siswa +
                            '">' +
                            '<label for="checkbox-' + data.id_siswa + '"></label>';
                    } else {
                        return '';
                    }

                }
            },
            {
                data: 'nis_siswa',
                name: 'nis_siswa'
            },
            {
                data: 'nm_kelas',
                name: 'kelas.nm_kelas',
                searchable: false
            },
            {
                data: 'nm_siswa',
                name: 'nm_siswa'
            },
            {
                data: 'nm_wali_murid',
                name: 'pengguna.nm_pengguna',
                searchable: false
            },
            {
                data: 'nomor_hp_wali_murid',
                searchable: false,
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    if (data.wali_murid) {
                        return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                            edit_url + '/' + data.id + '">' +
                            '    <i class="material-icons">edit</i>' +
                            '</a>' +
                            '<button class="btn  bg-red waves-effect btn-circle" id="btn-reset-password" style="margin-left:10px" onclick="hapusWaliMurid(\'' +
                            delete_url + '\', this)" data-id="' +
                            data.id + '">' +
                            '<i class="material-icons">delete</i>' +
                            '</button>';
                    } else {
                        return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                            edit_url + '/' + data.id + '">' +
                            '    <i class="material-icons">edit</i>' +
                            '</a>';
                    }
                }
            }
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


    $(document).ready(function() {
        /* Select All Checkbox */
        $('#checkbox_select_all_primary_table').change(function() {
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

    function hapusWaliMurid(id, element) {

        var url_delete = id;
        var item = $(element);
        var id = item.attr('data-id');
        swal({
                title: 'Apakah Yakin Untuk Menghapus Wali Murid?',
                showCancelButton: true
            },
            function(isConfirm) {
                if (isConfirm) {
                    $('#btn-reset-password').attr("disabled", true);
                    $.ajax({
                        url: url_delete + '/' + id,
                        type: 'POST',
                        success: function(response) {
                            if (response.status_code == 200) {
                                vex.dialog.alert(response.message);
                            } else if (response.status_code == 201) {
                                vex.dialog.alert(response.message);
                                window.location.href = response.link;
                            } else if (response.status_code == 202) {
                                vex.dialog.alert(response.message);
                                loadURI(response.path);
                            } else if (response.status_code == 203) {
                                vex.dialog.alert(response.message);
                                primary_table.ajax.reload(null, false);
                            } else if (response.status_code == 204) {
                                loadURI(response.path);
                            } else if (response.status_code == 300) {
                                vex.dialog.alert(response.message);
                            }
                        },
                        complete: function() {
                            $('#btn-reset-password').removeAttr('disabled', 'disabled');
                        },
                    });
                }
                return;
            }
        );
    }

    function deleteWaliMuridCollect() {
        $('button').attr('disabled', 'disabled');
        var id_siswa = [];
        $("input:checkbox[name=id_siswa]:checked").each(function() {
            id_siswa.push($(this).val());
        });

        swal({
                title: 'Apakah Yakin Untuk Menghapus Kolektif?',
                showCancelButton: true
            },
            function(isConfirm) {
                if (isConfirm) {
                    $('#btn-reset-password').attr("disabled", true);
                    $.ajax({
                        url: base_url +
                            '/{{ Request::segment(1) }}/{{ Request::segment(2) }}/{{ Request::segment(3) }}/reset-wali-murid-collect',
                        type: 'POST',
                        data: {
                            data_siswa: id_siswa
                        },
                        success: function(response) {
                            if (response.status_code == 200) {
                                vex.dialog.alert(response.message);
                            } else if (response.status_code == 201) {
                                vex.dialog.alert(response.message);
                                window.location.href = response.link;
                            } else if (response.status_code == 202) {
                                vex.dialog.alert(response.message);
                                loadURI(response.path);
                            } else if (response.status_code == 203) {
                                vex.dialog.alert(response.message);
                                primary_table.ajax.reload(null, false);
                            } else if (response.status_code == 204) {
                                loadURI(response.path);
                            } else if (response.status_code == 300) {
                                vex.dialog.alert(response.message);
                            }
                        },
                        complete: function() {
                            $('button').removeAttr('disabled', 'disabled');
                        }
                    });
                }
                $('button').removeAttr('disabled', 'disabled');
                return;
            }
        );
    }

    function changeJurusan(el) {
        $('select[name=id_kelas]').html('');
        var html = '<option value="0">-- Semua --</option>';
        $('select[name=id_kelas]').html(html);
        $.ajax({
            url: '{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/getDataKelas') }}',
            type: 'POST',
            data: {
                jurusan: $('select[name=id_jurusan]').val(),
            },
            success: function(kelas) {

                $('select[name=id_kelas]').html('');
                var html = '<option value="0">-- Semua --</option>';
                $.each(kelas, function(key, item) {
                    html += '<option value="' + item.id_kelas + '">' + item.nm_kelas + '</option>'
                });
                $('select[name=id_kelas]').html(html);
            }
        });
    }
</script>
