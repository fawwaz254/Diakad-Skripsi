<div class="container-fluid">
    <div class="block-header">
        <h2 style="float: right; margin-bottom: 1rem">
            <button class="btn bg-green waves-effect" onclick="showModalAction()"><i class="material-icons">add</i><span>Tambah Rapor</span></button>
        </h2>
        <div style="clear: right;"></div>
    </div>

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>List Rapor Pendukung</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Rapor Pendukung</th>
                                    <th>Komponen</th>
                                    <th>Predikat Nilai</th>
                                    <th>Cetak</th>
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

{{-- MODAL ACTION --}}
<div class="modal" tabindex="-1" role="dialog" id="modal-action">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header">
                <h5 class="modal-title">Tambah Rapor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-validation" method="POST" action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3)) . '/action' }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="id_rapor_pendukung">
                    <input type="hidden" name="mode" value="add">

                    <label for="nm_rapor">Nama Rapor</label>
                    <input class="form-control" type="text" name="nm_rapor">

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success  waves-effect" onclick="hideModalCreate()">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('scriptjs')
<script>
    var modul_url = 'wali-kelas';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'rapor-pendukung/datatables';
    var print_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'rapor-pendukung/print';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        order: [1, 'asc'],
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
                data: 'nm_rapor',
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                className: 'align-center',
                render: function(data) {
                    return `
                    ${data.total_komponen} Komponen
                    <a class="btn btn-success btn-circle waves-effect waves-circle waves-float text-center" href="${base_url}/${role_url}#${modul_url}/rapor-pendukung/komponen/${data.id}">
                        <i class="material-icons">settings</i>
                    </a>`;
                }
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                className: 'align-center',
                render: function(data) {
                    // var html = `<a class="btn btn-success btn-circle waves-effect waves-circle waves-float text-center" disabled="true" href="${base_url}/${role_url}#${modul_url}/rapor-pendukung/predikat/${data.id}">
                    var html = `<a class="btn btn-success btn-circle waves-effect waves-circle waves-float text-center" disabled="true">
                        <i class="material-icons">add</i>
                    </a>`;

                    html += `<a class="btn" target="_blank" href="${base_url}/${role_url}/${modul_url}/rapor-pendukung/indikator/${data.id}/template">
                        Template Excel
                    </a>`;

                    return html;
                }
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                className: 'align-center',
                render: function(data) {
                    return `
                    <a class="btn btn-success btn-circle waves-effect waves-circle waves-float text-center" href="${base_url}/${role_url}/${modul_url}/rapor-pendukung/cetak/${data.id}" target="_blank">
                        <i class="material-icons">picture_as_pdf</i>
                    </a>`;
                }
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                className: 'align-center',
                render: function(data) {
                    return `
                    <button class="btn bg-blue waves-effect" onclick="showModalAction('${data.id}')"><i class="material-icons">edit</i><span>Edit</span></button>
                    <button class="btn bg-red waves-effect" onclick="actionHapus('${data.id}')"><i class="material-icons">delete</i><span>Hapus</span></button>
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

    function showModalAction(id = null) {
        $('.modal-title').text('Tambah Rapor');
        $('input[name=mode]').val('add');
        $('input[name=id_rapor_pendukung]').val('');
        $('input[name=nm_rapor]').val('');


        $('#modal-action').modal('show');

        if (id !== null) {
            $('.modal-title').text('Edit Rapor');

            $.ajax({
                type: "POST",
                url: base_url + '/' + role_url + '/wali-kelas/rapor-pendukung/get',
                data: {
                    id_rapor_pendukung: id,
                },
                success: function(response) {
                    $('input[name=mode]').val('update');
                    $('input[name=id_rapor_pendukung]').val(id);
                    $('input[name=nm_rapor]').val(response.data.nm_rapor);
                },
            });
        }
    }

    function hideModalCreate() {
        setTimeout(() => {
            $('#modal-action').modal('hide');
        }, 500);
    }

    function actionHapus(id_rapor_pendukung) {
        $('button').attr('disabled', 'disabled');

        swal({
            title: "Apakah Anda yakin ingin menghapus?",
            text: "ini akan menghapus semua nilai siswa yang sudah ditambahkan pada kelas ini!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, tetap hapus!",
            cancelButtonText: "Cancel",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(result) {
            if (result) {
                $.ajax({
                    type: 'POST',
                    url: `${base_url}/${role_url}/wali-kelas/rapor-pendukung/action`,
                    data: {
                        id_rapor_pendukung,
                        mode: 'delete'
                    },
                    success: function(response) {
                        vex.dialog.alert(response.message);
                        primary_table.ajax.reload(null, false);
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

    $(document).ready(function() {
        var pathname = window.location.pathname;
        var segments = pathname.split('/');
        var role = segments[1];

        if (role == 'guru') {
            $('.block-header').hide();
            primary_table.column(2).visible(false);
            primary_table.column(5).visible(false);
        } else if (role == 'akademik') {
            primary_table.column(3).visible(false);
            primary_table.column(4).visible(false);
        }
    });
</script>