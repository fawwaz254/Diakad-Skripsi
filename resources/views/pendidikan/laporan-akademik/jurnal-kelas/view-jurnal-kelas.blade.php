<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        FILTER DATA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Kelas</label>
                                        <select class="form-control show-tick" name="id_kelas" required="">
                                            @foreach ($data_kelas as $data)
                                                <option value="{{ $data->id_kelas }}"
                                                    {{ !empty($selected_kelas) && $selected_kelas->id_kelas == $data->id_kelas ? 'selected' : '' }}>
                                                    {{ $data->nm_kelas }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Semester</label>
                                        <select class="form-control show-tick" name="id_semester" required="">
                                            @foreach ($data_semester as $data)
                                                <option value="{{ $data->id_semester }}"
                                                    {{ (!empty($selected_semester) && $selected_semester->id_semester == $data->id_semester ? 'selected' : $data->is_aktif_semester == 1 && empty($selected_semester)) ? 'selected' : '' }}>
                                                    {{ $data->tahun_ajaran }}
                                                    {{ $data->nm_semester }}
                                                    @if ($data->is_aktif_semester == 1)
                                                        (Aktif)
                                                    @endif
                                                </option>
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

@if (!empty($selected_kelas) && !empty($selected_semester))
    <div class="container-fluid">
        <div class="card">
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                        id="primary_table">
                        <thead>
                            <tr>
                                <th>No. </th>
                                <th>Mata Pelajaran</th>
                                <th>Hari</th>
                                <th>Jam KBM</th>
                                <th>Ruangan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        let id_kelas = '{{ $selected_kelas->id_kelas }}',
            id_semester = '{{ $selected_semester->id_semester }}';

        var modul_url = 'laporan-akademik';
        var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'jurnal-kelas/datatables/' + id_kelas +
            '/' + id_semester;

        var print_pdf = base_url + '/' + role_url + '/' + modul_url + '/jurnal-kelas/print-pdf';

        var primary_table = $('#primary_table').DataTable({
            processing: true,
            serverSide: true,
            dom: 'Bfrtip',
            lengthMenu: dtLengButton,
            buttons: dtButtonConfig,
            ajax: {
                url: datatable_url,
                type: 'GET'
            },
            columns: [{
                    data: 'index_table',
                    defaultContent: '',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'mata_pelajaran',
                    name: 'mata_pelajaran'
                },
                {
                    data: 'jadwal_hari',
                    name: 'jadwal_hari'
                },
                {
                    data: 'jadwal_jam',
                    name: 'jadwal_jam'
                },
                {
                    data: 'ruangan',
                    name: 'ruangan'
                },
                {
                    data: 'id_kelas_mp',
                    name: 'action',
                    searchable: false,
                    orderable: false,
                    render: function(data) {
                        return '<a class="btn bg-red btn-circle waves-effect waves-circle waves-float" target="_blank" href="' +
                            print_pdf + '/' + data + '">' +
                            '    <i class="material-icons">print</i>' +
                            '</a>'

                        ;
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
                primary_table.cell(cell).invalidate('dom');
            });
        }).draw();
    </script>
@endif

<script type="text/javascript">
    $('select:not(.ms)').selectpicker();
</script>

<script type="text/javascript">
    let modul_url = '{{ $auth_data->modul_url }}';
    let menu_url = '{{ $auth_data->menu_url }}';

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
