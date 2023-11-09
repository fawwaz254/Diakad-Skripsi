<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        FILTER KELAS
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Semester</label>
                                        <select class="form-control show-tick" name="id_semester" required="">
                                            @foreach ($data_semester as $data)
                                                @if (!empty($selected_semester))
                                                    <option value="{{ $data->id_semester }}"
                                                        {{ $selected_semester->id_semester == $data->id_semester ? 'selected' : '' }}>
                                                        {{ $data->tahun_ajaran }} {{ $data->nm_semester }}
                                                        @if ($data->is_aktif_semester == 1)
                                                            (Aktif)
                                                        @endif
                                                    </option>
                                                @else
                                                    <option value="{{ $data->id_semester }}"
                                                        {{ $data->is_aktif_semester == 1 ? 'selected' : '' }}>
                                                        {{ $data->tahun_ajaran }} {{ $data->nm_semester }}
                                                        @if ($data->is_aktif_semester == 1)
                                                            (Aktif)
                                                        @endif
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Kelas</label>
                                        <select class="form-control show-tick" name="id_kelas" required="">
                                            <option value="">-- Pilih Kelas --</option>
                                            @foreach ($data_kelas as $data)
                                                <option
                                                    {{ !empty($selected_kelas) && $selected_kelas->id_kelas == $data->id_kelas ? 'selected' : '' }}
                                                    value="{{ $data->id_kelas }}">{{ $data->nm_kelas }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-btn-submit waves-effect" type="submit"><i
                                        class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@if (!empty($selected_semester) && !empty($selected_kelas))
    <div class="container-fluid">
        <div class="block-header">
            <h2><a class="btn bg-blue waves-effect target-link "
                    href="{{ url(Request::segment(1) . '#' . $auth_data->modul_url . '/' . $auth_data->menu_url . '/manage/' . $selected_semester->id_semester . '/' . $selected_kelas->id_kelas) }}"><i
                        class="material-icons">note_add</i><span>Tambah Presensi Harian</span></a></h2>
        </div>
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2>ABSENSI HARIAN KELAS {{ $selected_kelas->nm_kelas }} Semester
                            {{ $selected_semester->tahun_ajaran }} ({{ $selected_semester->nm_semester }})</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table
                                class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                                id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Bulan</th>
                                        <th>Tahun</th>
                                        <th>Sudah presensi</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let modul_url = '{{ $auth_data->modul_url }}';
        let menu_url = '{{ $auth_data->menu_url }}';

        let id_kelas = '{{ $selected_kelas->id_kelas }}',
            id_semester = '{{ $selected_semester->id_semester }}';

        let datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + menu_url + '/datatables/' + id_semester +
            '/' + id_kelas;
        let delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + menu_url + '/action/delete';

        let detail_url = role_url + '#' + modul_url + '/' + menu_url + '/detail/' + id_semester + '/' + id_kelas;

        let primary_table = $('#primary_table').DataTable({
            processing: true,
            serverSide: true,
            responsive: false,
            ajax: {
                url: datatable_url,
                type: 'POST'
            },
            columns: [{
                    data: null,
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'bulan',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'tahun',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'jml_record',
                    searchable: false,
                    orderable: false,
                    render: function(data) {
                        return data + 'x';
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    searchable: false,
                    orderable: false,
                    render: function(data) {
                        return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                            detail_url + '/' + data.tahun + '/' + data.bulan + '">' +
                            '    <i class="material-icons">remove_red_eye</i>' +
                            '</a> ';
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
    </script>
@endif
<script>
    let modul_url = '{{ $auth_data->modul_url }}';
    let menu_url = '{{ $auth_data->menu_url }}';

    $('select:not(.ms)').selectpicker();
    $('#form-validation').validate({
        rules: {
            'checkbox': {
                required: true
            },
            'gender': {
                required: true
            }
        },
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
            let params = $(form).serializeArray();
            let load_url = modul_url + '/' + menu_url;
            $.each(params, function(i, field) {
                load_url += '/' + field.value;
            });
            loadURI(load_url);
            $('button').removeAttr('disabled');
        }
    });
</script>
