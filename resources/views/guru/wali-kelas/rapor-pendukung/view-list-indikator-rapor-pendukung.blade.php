<div class="container-fluid">
    <div class="block-header">
        <h2>
            <a class="btn bg-white waves-effect target-link" style="color: black"
                href="{{ url(Request::segment(1) . '#wali-kelas/rapor-pendukung/komponen/' . $komponen->id_rapor_pendukung) }}">
                <i class="material-icons">arrow_back</i>
                <span>Kembali</span>
            </a>

            <button style="position: absolute; right: 30px;" class="btn bg-green waves-effect d-flex flex-row-reverse"
                onclick="showModalAction()"><i class="material-icons">add</i><span>Tambah Indikator</span></button>
        </h2>
    </div>

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>List Indikator Komponen <u><strong>{{ $komponen->nm_komponen }}</strong></u></h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Semester</th>
                                    <th>Tingkat Kelas</th>
                                    <th>Nama Indikator</th>
                                    <th>Urutan Indikator</th>
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
                <h5 class="modal-title">Tambah Indikator Komponen <u><strong>{{ $komponen->nm_komponen }}</strong></u>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-validation" method="POST"
                    action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/' . Request::segment(4) . '/' . Request::segment(5)) . '/action' }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="id_indikator_rapor_pendukung">
                    <input type="hidden" name="mode" value="add">

                    <label for="id_semester">Semester</label>
                    <select class="form-control show-tick" name="id_semester" id="id_semester">
                        <option value="" disabled>-- Pilih Semester --</option>
                        @foreach ($semester as $semester)
                            @if ($semester->is_aktif_semester == 1)
                                <option value="{{ $semester->id_semester }}" selected>{{ $semester->nm_semester }}
                                    {{ $semester->tahun_ajaran }} (Aktif)</option>
                            @else
                                <option value="{{ $semester->id_semester }}">{{ $semester->nm_semester }}
                                    {{ $semester->tahun_ajaran }}</option>
                            @endif
                        @endforeach
                    </select>

                    <label for="tingkat_kelas">Tingkat Kelas</label>
                    <select class="form-control show-tick" name="tingkat_kelas">
                        <option selected="" disabled="">Pilih Kelas</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                    </select>

                    <label for="nm_indikator">Nama Indikator</label>
                    <input class="form-control" type="text" name="nm_indikator">

                    <label for="urutan">Urutan Indikator</label>
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
    var komponen = @json($komponen);
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'rapor-pendukung/indikator/' + komponen
        .id_komponen_rapor_pendukung + '/datatables';

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
                data: 'semester',
            },
            {
                data: 'tingkat_kelas',
            },
            {
                data: 'nm_indikator',
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
        $('.modal-title').html('Tambah Indikator Komponen <u><strong>{{ $komponen->nm_komponen }}</strong>');
        $('input[name=mode]').val('add');
        $('input[name=id_indikator_rapor_pendukung]').val('');
        $('select[name=tingkat_kelas]').val('');
        $('input[name=nm_indikator]').val('');
        $('input[name=urutan]').val('');

        $('#modal-action').modal('show');

        if (id !== null) {
            $('.modal-title').html('Edit Indikator Komponen <u><strong>{{ $komponen->nm_komponen }}</strong>');

            $.ajax({
                type: "POST",
                url: base_url + '/' + role_url + '/wali-kelas/rapor-pendukung/indikator/get',
                data: {
                    id_indikator_rapor_pendukung: id,
                },
                success: function(response) {
                    console.log(response);
                    $('input[name=mode]').val('update');
                    $('input[name=id_indikator_rapor_pendukung]').val(id);
                    $('input[name=nm_indikator]').val(response.data.nm_indikator);
                    $('input[name=urutan]').val(response.data.urutan);
                    $('select[name=tingkat_kelas]').val(response.data.tingkat_kelas);
                    $('select[name=id_semester]').val(response.data.id_semester);
                },
            });
        }
    }

    function hideModalCreate() {
        setTimeout(() => {
            $('#modal-action').modal('hide');
        }, 500);
    }

    function actionHapus(id_indikator_rapor_pendukung) {
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
                    url: `${base_url}/${role_url}/wali-kelas/rapor-pendukung/indikator/${id_indikator_rapor_pendukung}/action`,
                    data: {
                        id_indikator_rapor_pendukung,
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
