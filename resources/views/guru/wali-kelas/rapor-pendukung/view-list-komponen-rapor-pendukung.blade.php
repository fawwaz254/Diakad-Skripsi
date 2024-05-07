<div class="container-fluid">
    <div class="block-header">
        <h2>
            <a class="btn bg-white waves-effect target-link" style="color: black"
                href="{{ url(Request::segment(1) . '#wali-kelas/rapor-pendukung') }}"><i class="material-icons">arrow_back
                </i><span>Kembali</span></a>

            <button style="position: absolute; right: 30px;" class="btn bg-green waves-effect d-flex flex-row-reverse"
                onclick="showModalAction()"><i class="material-icons">add</i><span>Tambah Komponen</span></button>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>List Komponen Rapor <strong><u>{{ $rapor_pendukung->nm_rapor }}</u></strong></h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Komponen</th>
                                    <th>Urutan Komponen</th>
                                    <th>Indikator</th>
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
<br>

{{-- MODAL ACTION --}}
<div class="modal" tabindex="-1" role="dialog" id="modal-action">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header">
                <h5 class="modal-title">Tambah Komponen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-validation" method="POST"
                    action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/' . Request::segment(4) . '/' . Request::segment(5)) . '/action' }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="id_komponen_rapor_pendukung">
                    <input type="hidden" name="mode" value="add">

                    <label for="nm_komponen">Nama Komponen</label>
                    <input class="form-control" type="text" name="nm_komponen">

                    <label for="urutan">Urutan Komponen</label>
                    <input class="form-control" type="number" name="urutan">

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success  waves-effect"
                            onclick="hideModalCreate()">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('scriptjs')
<script>
    var modul_url = 'wali-kelas';
    var rapor_pendukung = @json($rapor_pendukung);
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'rapor-pendukung/komponen/' +
        rapor_pendukung.id_rapor_pendukung + '/datatables';

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
                data: 'nm_komponen',
            },
            {
                data: 'urutan',
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                className: 'align-center',
                render: function(data) {
                    return `
                    <a class="btn btn-success btn-circle waves-effect waves-circle waves-float text-center" href="${base_url}/${role_url}#${modul_url}/rapor-pendukung/indikator/${data.id}">
                        <i class="material-icons">add</i>
                    </a>
                    `;
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
        $('.modal-title').text('Tambah Komponen');
        $('input[name=mode]').val('add');
        $('input[name=id_komponen_rapor_pendukung]').val('');
        $('input[name=nm_komponen]').val('');
        $('input[name=urutan]').val('');


        $('#modal-action').modal('show');

        if (id !== null) {
            $('.modal-title').text('Edit Komponen');

            $.ajax({
                type: "POST",
                url: base_url + '/' + role_url + '/wali-kelas/rapor-pendukung/komponen/get',
                data: {
                    id_komponen_rapor_pendukung: id,
                },
                success: function(response) {
                    $('input[name=mode]').val('update');
                    $('input[name=id_komponen_rapor_pendukung]').val(id);
                    $('input[name=nm_komponen]').val(response.data.nm_komponen);
                    $('input[name=urutan]').val(response.data.urutan);
                },
            });
        }
    }

    function hideModalCreate() {
        setTimeout(() => {
            $('#modal-action').modal('hide');
        }, 500);
    }

    function actionHapus(id_komponen_rapor_pendukung) {
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
                    url: `${base_url}/${role_url}/wali-kelas/rapor-pendukung/komponen/${id_komponen_rapor_pendukung}/action`,
                    data: {
                        id_komponen_rapor_pendukung,
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
</script>
