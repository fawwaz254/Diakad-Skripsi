<div class="container-fluid">
    @if ($role_aktif == 2)
        <div class="block-header">
            <h2>
                <a class="btn bg-blue waves-effect target-link "
                    href="{{ url(Request::segment(1) . '#' . Request::segment(2)) . '/add-kunjungan-magang' }}">
                    <i class="material-icons">add</i>
                    Tambah kunjungan
                </a>
            </h2>
        </div>
    @endif
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        DATA KUNJUNGAN MAGANG
                    </h2>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            @if ($role_aktif == 19)
                                <form id="form-validation" method="POST"
                                    action="{{ url(Request::segment(1) . '/' . Request::segment(2)) . '/kunjungan-magang' }}">
                                    {{ csrf_field() }}
                                    <h2 class="card-inside-title">
                                        Periode magang
                                    </h2>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <select class="form-control show-tick" name="periode_magang">
                                                @foreach ($periode_magang as $magang)
                                                    <option value="{{ $magang->id_periode_magang }}"
                                                        @if ($id_periode_magang == $magang->id_periode_magang) selected @endif>
                                                        {{ $magang->nm_periode_magang . ' ' }}{{ $magang->nomor_sk_periode_magang }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <button class="btn btn-block bg-blue waves-effect" type="submit">
                                                <i class="material-icons">search</i>
                                                <span>Filter</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Rekanan magang</th>
                                    <th>Guru pembimbing</th>
                                    <th>Keterangan kunjungan</th>
                                    <th>Foto kunjungan</th>
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
@include('scriptjs')
<script>
    var role = @json($role_aktif);
    var datatable_url = base_url;
    var action_url = base_url + '/guru/magang-siswa/action-kunjungan-magang/';
    var edit_url = base_url + '/guru#magang-siswa/edit-kunjungan-magang/';
    if (role == 2) {
        datatable_url += '/guru/magang-siswa/list-kunjungan-magang-datatables';
    } else {
        datatable_url += '/humas/magang-siswa/kunjungan-magang-datatables';
    }

    function deleteAction(id) {
        $('button').attr('disabled', 'disabled');

        swal({
            title: "Yakin hapus?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(result) {
            if (result) {
                $.ajax({
                    type: "POST",
                    url: action_url + 'delete/' + id,
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

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        ajax: {
            url: datatable_url,
            type: 'GET',
            data: {
                periode_magang: $('select[name=periode_magang]').val(),
            },
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false,
            },
            {
                data: 'rekanan_magang',
                name: 'rekanan_magang'
            },
            {
                data: 'guru_pembimbing',
                name: 'guru_pembimbing'
            },
            {
                searchable: false,
                orderable: false,
                data: 'keterangan_kunjungan',
                name: 'keterangan_kunjungan'
            },
            {
                searchable: false,
                orderable: false,
                data: 'foto_kunjungan',
                name: 'foto_kunjungan',
                render: function(data) {
                    if (data) {
                        return `<a href=` + data + ` target="_blank"><img src=` + data +
                            ` width=150></a>`;
                    } else {
                        return ``;
                    }
                }
            },
            {
                searchable: false,
                orderable: false,
                data: 'action',
                name: 'action',
                render: function(data) {
                    if (data && role == 2) {
                        return `
                            <span>
                                <a class="btn btn-info btn-circle waves-effect waves-circle waves-float" href=` +
                            edit_url + data.id + `><i class="material-icons">edit</i></a>
                                <button onclick="deleteAction('` + data.id + `')" class="btn btn-danger btn-circle waves-effect waves-circle waves-float" type="submit"><i class="material-icons">delete</i></button>
                            </span>
                        `;
                    } else {
                        return ``;
                    }
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
</script>
