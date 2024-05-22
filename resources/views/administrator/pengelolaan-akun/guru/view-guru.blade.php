<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        AKUN GURU
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/post-view-guru') }}">
                        {{ csrf_field() }}
                        <h2 class="card-inside-title">
                            Filter Role
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_role">
                                    <option value="" disabled selected>-- Pilih Role --</option>
                                    @foreach ($data_role as $data)
                                        @if ($id_role != null)
                                            @if ($data->id_role == $id_role)
                                                <option value="{{ $data->id_role }}" selected>{{ $data->nm_role }}
                                                    ({{ $data->total_role }})</option>
                                            @else
                                                <option value="{{ $data->id_role }}">{{ $data->nm_role }}
                                                    ({{ $data->total_role }})</option>
                                            @endif
                                        @else
                                            <option value="{{ $data->id_role }}">{{ $data->nm_role }}
                                                ({{ $data->total_role }})</option>
                                        @endif
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
                                        class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div>
                    </form>
                </div>

                @if ($id_role != null)
                    <div class="body">
                        <div class="table-responsive">
                            <table
                                class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                                id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No. </th>
                                        <th>
                                            <input id="checkbox_select_all_primary_table" type="checkbox"
                                                name="select_all" class="filled-in">
                                            <label for="checkbox_select_all_primary_table"
                                                style="margin-bottom: -10px;"></label>
                                        </th>
                                        <th>NIP</th>
                                        <th>Username</th>
                                        <th>Nama</th>
                                        <th>Role</th>
                                        <th>Unit Kerja</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-blue waves-effect" onclick="resetPasswordCollect()"><i
                                        class="material-icons">update</i><span>Reset Password Kolektif</span></button>
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
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var id_role = {!! json_encode($id_role) !!};

    var modul_url = 'pengelolaan-akun';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'guru/datatables/' + id_role;


    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        pageLength: 50,
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
                    return '<input id="checkbox-' + data.id_pengguna +
                        '" type="checkbox" name="id_pengguna" class="filled-in" value="' + data
                        .id_pengguna + '">' +
                        '<label for="checkbox-' + data.id_pengguna + '"></label>';
                }
            },
            {
                data: 'nip_guru',
                name: 'nip_guru'
            },
            {
                data: 'username',
                name: 'username'
            },
            {
                data: 'nm_pengguna',
                name: 'nm_pengguna'
            },
            {
                data: 'nm_role',
                name: 'nm_role'
            },
            {
                data: 'nm_unit_kerja',
                name: 'nm_unit_kerja'
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

    function resetPasswordCollect() {
        $('button').attr('disabled', 'disabled');
        var pengguna = [];
        $("input:checkbox[name=id_pengguna]:checked").each(function() {
            pengguna.push($(this).val());
        });

        $.ajax({
            url: base_url + '/{{ Request::segment(1) }}/{{ Request::segment(2) }}/reset-some-password',
            type: 'POST',
            data: {
                data_pengguna: pengguna
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
