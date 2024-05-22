<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        AKUN SISWA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/post-view-siswa') }}">
                        {{ csrf_field() }}
                        <h2 class="card-inside-title">
                            Filter Kelas
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_kelas">
                                    <option value="" disabled selected>-- Pilih Kelas --</option>
                                    @foreach ($data_kelas as $data)
                                        @if ($id_kelas != null)
                                            @if ($data->id_kelas == $id_kelas)
                                                <option value="{{ $data->id_kelas }}" selected>{{ $data->nm_kelas }}
                                                    ({{ $data->total_siswa }})</option>
                                            @else
                                                <option value="{{ $data->id_kelas }}">{{ $data->nm_kelas }}
                                                    ({{ $data->total_siswa }})</option>
                                            @endif
                                        @else
                                            <option value="{{ $data->id_kelas }}">{{ $data->nm_kelas }}
                                                ({{ $data->total_siswa }})</option>
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

                @if ($id_kelas != null)
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
                                        <th>NIS</th>
                                        <th>NISN</th>
                                        <th>Username</th>
                                        <th>Nama</th>
                                        <th>Kelas</th>
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
    var id_kelas = {!! json_encode($id_kelas) !!};

    var modul_url = 'pengelolaan-akun';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'siswa/datatables/' + id_kelas;


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
                data: 'nis_siswa',
                name: 'nis_siswa'
            },
            {
                data: 'nisn_siswa',
                name: 'nisn_siswa'
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
                data: 'nm_kelas',
                name: 'nm_kelas'
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
