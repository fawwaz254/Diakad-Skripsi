<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link "
                href="{{ url(Request::segment(1) . '#wisuda/pengajuan-wisuda') }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <form id="form-validation-2" method="POST"
                    action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/action-pengajuan-wisuda/pengajuan/0/0/' . $id_periode_wisuda) }}">
                    {{ csrf_field() }}
                    <div class="header">
                        <h2>PENGAJUAN WISUDA @if (is_null($data_periode_wisuda))
                            @else
                                {{ $data_periode_wisuda->nm_periode_wisuda }} SEMESTER
                                {{ $data_periode_wisuda->tahun_ajaran }} {{ $data_periode_wisuda->nm_semester }}
                            @endif
                        </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table
                                class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                                id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>
                                            <input id="checkbox_select_all_primary_table" type="checkbox"
                                                name="select_all" class="filled-in">
                                            <label for="checkbox_select_all_primary_table"
                                                style="margin-bottom: -10px;"></label>
                                        </th>
                                        <th>Periode Wisuda</th>
                                        <th>Semester</th>
                                        <th>NIS</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <button class="btn btn-block btn-info waves-effect waves-float" type="submit">
                            <i class="material-icons">autorenew</i><span>Ajukan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];

    var id_periode_wisuda = {!! json_encode($id_periode_wisuda) !!};
    var id_kelas = {!! json_encode($id_kelas) !!};

    var modul_url = 'wisuda';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'pengajuan-wisuda/datatables/' +
        id_periode_wisuda + '/' + id_kelas;
    var cancel_url = role_url + '#' + modul_url + '/' + 'pengajuan-wisuda/cancel';
    var pengajuan_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-pengajuan-wisuda/pengajuan';


    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        lengthMenu: [
            [50, 100, -1],
            [50, 100, "All"]
        ],
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
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data, type, full, meta) {
                    if (data.id != null) {
                        if (data.status_wisuda == 2) {
                            return '<a>LULUS</a>';
                        } else if (data.status_wisuda == 1) {
                            return 'Sudah diajukan';
                        }
                    } else {
                        return '<input id="checkbox-' + data.id_siswa +
                            '" type="checkbox" name="id_siswa[]" class="filled-in" value="' + data
                            .id_siswa + '">' +
                            '<label for="checkbox-' + data.id_siswa + '"></label>';
                    }

                }
            },
            {
                data: 'nm_periode_wisuda',
                name: 'nm_periode_wisuda'
            },
            {
                data: 'semester',
                name: 'semester'
            },
            {
                data: 'nis_siswa',
                name: 'nis_siswa'
            },
            {
                data: 'nm_pengguna',
                name: 'nm_pengguna'
            },
            {
                data: 'nm_kelas',
                name: 'nm_kelas'
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    if (data.id != null) {
                        if (data.status_wisuda == 2) {
                            return '<a>LULUS</a>';
                        } else if (data.status_wisuda == 1) {
                            return '<a class="target-link btn btn-danger btn-circle waves-effect waves-circle waves-float" href="' +
                                cancel_url + '/' + data.id + '/' + id_periode_wisuda + '/' + id_kelas +
                                '">' +
                                '    <i class="material-icons">delete_forever</i>' +
                                '</a>';
                        }

                    } else {
                        return '';
                        // return '<button class="btn btn-info btn-circle waves-effect waves-circle waves-float" onclick="pengajuanAction(\''+ pengajuan_url +'\', this)" data-id-siswa="'+  data.id_siswa +'" data-id-periode-wisuda="'+  id_periode_wisuda +'">'+
                        // '    <i class="material-icons">autorenew</i>'+
                        // '</button>';
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

    function pengajuanAction(pengajuan_url, element) {
        var item = $(element);
        $('button').attr('disabled', 'disabled');

        swal({
            title: "Apakah Anda Yakin?",
            text: "Pengajuan Wisuda Untuk Siswa Ini!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: "Ya, Ajukan Wisuda!",
            cancelButtonText: "Tidak, Batalkan!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(result) {
            if (result) {
                $.ajax({
                    type: "POST",
                    url: pengajuan_url + '/0/' + item.attr('data-id-siswa') + '/' + item.attr(
                        'data-id-periode-wisuda'),
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

<script type="text/javascript">
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

    $('#form-validation-2').validate({
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
                    $('button').removeAttr('disabled');
                }
            });
        }
    });
</script>
