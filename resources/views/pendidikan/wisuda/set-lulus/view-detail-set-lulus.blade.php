<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{ url(Request::segment(1) . '#wisuda/set-lulus') }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <form id="form-validation-2" method="POST"
                    action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/action-set-lulus/set-lulus/0') }}">
                    {{ csrf_field() }}
                    <div class="header">
                        <h2>SET LULUS SISWA @if (is_null($data_periode_wisuda))
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
                                        {{-- <th>Tgl Pengajuan Wisuda</th>
                                        <th>Biodata</th>
                                        <th>Status Lab</th>
                                        <th>Status Perpus</th> --}}
                                        <th>Status Ijasah</th>
                                        <th>Nomor SK Kelulusan</th>
                                        <th>Tgl SK Kelulusan</th>
                                        <th>Nomor Ijasah</th>
                                        <th>Tgl Kelulusan</th>
                                        <!-- <th>Action</th> -->
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <button class="btn btn-block btn-info waves-effect waves-float" type="submit">
                            <i class="material-icons">verified_user</i><span>Set Lulus</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    var id_periode_wisuda = {!! json_encode($id_periode_wisuda) !!};
    var id_kelas = {!! json_encode($id_kelas) !!};

    var modul_url = 'wisuda';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'set-lulus/datatables/' +
        id_periode_wisuda + '/' + id_kelas;
    var set_lulus_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-set-lulus/set-lulus';


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
                    return '<input id="checkbox-' + data.id +
                        '" type="checkbox" name="id_pengajuan_wisuda[]" class="filled-in" value="' +
                        data.id + '">' +
                        '<label for="checkbox-' + data.id + '"></label>';
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
                data: 'tgl_pengajuan_wisuda',
                name: 'tgl_pengajuan_wisuda'
            },
            // { data: 'status_biodata', name: 'status_biodata'},
            // { data: 'status_lab', name: 'status_lab'},
            // { data: 'status_perpus', name: 'status_perpus'},
            // { data: 'status_ijasah', name: 'status_ijasah'},
            {
                data: 'nomor_sk_kelulusan',
                name: 'nomor_sk_kelulusan'
            },
            {
                data: 'tgl_sk_kelulusan',
                name: 'tgl_sk_kelulusan'
            },
            {
                data: 'nomor_ijasah',
                name: 'nomor_ijasah'
            },
            {
                data: 'tgl_kelulusan',
                name: 'tgl_kelulusan'
            },
            // { data: 'action', name: 'action', searchable: false, orderable: false,
            //     render: function(data){
            //         return '<button class="btn btn-info btn-circle waves-effect waves-circle waves-float" onclick="setLulusAction(\''+ set_lulus_url +'\', this)" data-id="'+  data.id +'" >'+
            //             '    <i class="material-icons">verified_user</i>'+
            //             '</button>';
            //     }
            // }
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

    function setLulusAction(set_lulus_url, element) {
        var item = $(element);
        $('button').attr('disabled', 'disabled');

        swal({
            title: "Apakah Anda Yakin?",
            text: "Set Lulus Untuk Siswa Ini (Menjadi Alumni)!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: "Ya, Set Lulus!",
            cancelButtonText: "Tidak, Batalkan!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(result) {
            if (result) {
                $.ajax({
                    type: "POST",
                    url: set_lulus_url + '/' + item.attr('data-id'),
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
