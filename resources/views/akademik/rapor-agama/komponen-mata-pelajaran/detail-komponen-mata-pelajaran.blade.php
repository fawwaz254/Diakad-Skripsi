<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card" style="margin-top: 10px">
                <div class="header">
                    <h2>
                        Komponen Mata Pelajaran
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3)) }}">
                        {{ csrf_field() }}
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Kelas
                                </h2>
                                <select class="form-control show-tick" name="id_kelas">
                                    <option selected="" disabled="">Pilih Kelas</option>
                                    @foreach ($kelas as $k)
                                        <option value="{{ $k->id_kelas }}"
                                            @if ($k->id_kelas == $id_kelas) selected @endif>{{ $k->nm_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    <br>
                                </h2>
                                <button class="btn btn-block bg-primary waves-effect" type="submit"><i
                                        class="material-icons">save</i><span>Tampilkan</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>



        </div>
    </div>
    <br>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="block-header">
                <h2><a class="btn bg-blue waves-effect target-link"
                        href="{{ url(Request::segment(1) . '#rapor-agama/komponen-mata-pelajaran/add/' . $id_kelas) }}"><i
                            class="material-icons">add</i><span>Tambah Komponen Mata Pelajaran</span></a><a
                        class="btn bg-blue waves-effect target-link" style="margin-left:10px"
                        href="{{ url(Request::segment(1) . '#rapor-agama/komponen-mata-pelajaran/copy/' . $id_kelas) }}"><i
                            class="material-icons">content_copy</i><span>Copy Komponen Mata Pelajaran</span></a></h2>
            </div>
            <div class="card">
                <div class="body">
                    <form form id="form-checkbox" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/komponen-mata-pelajaran/action-komponen-mata-pelajaran/delete/0') }}">
                        {{ csrf_field() }}
                        <div class="table-responsive">
                            <table
                                class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                                id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kelompok</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Urutan</th>
                                        <th>Kelas</th>
                                        <th>
                                            <input id="checkbox_select_all" type="checkbox" name="select_all"
                                                class="filled-in">
                                            <label for="checkbox_select_all" style="margin-bottom: -10px;"></label>
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                        <button class="btn btn-block bg-red waves-effect" style="margin-top: 1rem" type="submit"><i
                                class="material-icons">delete_forever</i><span>Delete</span></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<br>

@include('scriptjs')
<script>
    var modul_url = 'rapor-agama';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'komponen-mata-pelajaran/datatables';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' +
        'komponen-mata-pelajaran/action-komponen-mata-pelajaran/delete';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        ajax: {
            url: datatable_url,
            type: 'GET',
            data: function(d) {
                d.id_kelas = '{{ $id_kelas }}';
            }
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },

            {
                data: 'nm_kelompok_mapel_rapor',
                name: 'nm_kelompok_mapel_rapor'
            },
            {
                data: 'nm_mata_pelajaran',
                name: 'nm_mata_pelajaran'
            },
            {
                data: 'mata_pelajaran_rapor.urutan',
                name: 'mata_pelajaran_rapor.urutan'
            },
            {
                data: 'kelas.nm_kelas',
                name: 'kelas.nm_kelas'
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data, type, full, meta) {
                    return `
                        <input id="checkbox-${data.id}" type="checkbox" name="list_id_komponen[]" class="filled-in" value="${data.id}">
                        <label for="checkbox-${data.id}"></label>
                    `;
                }
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

    $('#form-checkbox').validate({
        rules: {
            'checkbox': {
                required: true
            },
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
                } else {
                    $('button').removeAttr('disabled', 'disabled');
                }
            });
        }
    });
</script>
