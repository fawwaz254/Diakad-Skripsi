<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#guru/input-guru/add') }}"><i
                    class="material-icons">note_add</i><span>Input Guru Baru</span></a>
            <a class="btn bg-green waves-effect target-link" style="margin-left: 10px"
                href="{{ url(Request::segment(1) . '#guru/upload-data-guru') }}"><i
                    class="material-icons">note_add</i><span>Upload Data Guru</span></a>
        </h2>
    </div>

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>FILTER STATUS</h2>
                </div>
                <div class="body">

                    <div class="row clearfix">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Status
                            </h2>
                            <select class="form-control show-tick" name="id_status_pengguna" id="id_status_pengguna">
                                <option value="0">-- Semua --</option>
                                @foreach ($status as $r)
                                    <option value="{{ $r->id_status_pengguna }}">{{ $r->nm_status_pengguna }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-red waves-effect" type="button" onclick="filterData()"><i
                                    class="material-icons">save</i><span>Tampilkan</span></button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <br>

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>DATA GURU</h2>
                </div>
                <div class="body">
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table class="table table-bordered table-striped table-hover dataTable display"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Guru</th>
                                    <th>NIP</th>
                                    <!-- <th>Jabatan</th> -->
                                    <th>Unit Kerja</th>
                                    <th>Status</th>
                                    <th>Jumlah Mengajar Smt Aktif</th>
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
</div>

<script>
    function filterData() {
        primary_table.draw();
    }

    // var modul_url = location.hash.replace('#','').split('/')[0];
    var modul_url = 'guru';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'input-guru/datatables';
    var edit_url = role_url + '#' + modul_url + '/' + 'input-guru/edit';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-input-guru/delete';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        dom: 'Bfrtip',
        lengthMenu: dtLengButton,
        buttons: dtButtonConfig,
        ajax: {
            url: datatable_url,
            type: 'GET',
            data: function(d) {
                d.id_status_pengguna = $('select[name=id_status_pengguna]').val()
            }
        },
        columns: [{
                data: 'index_table',
                defaultContent: '',
                searchable: false,
                orderable: false
            },
            {
                data: 'nm_pengguna',
                name: 'pengguna.nm_pengguna'
            },
            {
                data: 'nip_guru',
                name: 'guru.nip_guru'
            },
            /*{ data: 'nm_jabatan_pegawai', name: 'nm_jabatan_pegawai' },*/
            {
                data: 'nm_unit_kerja',
                name: 'unit_kerja.nm_unit_kerja'
            },
            {
                data: 'nm_status_pengguna',
                name: 'status_pengguna.nm_status_pengguna'
            },
            {
                data: 'jml_mengajar_semester_aktif',
                name: 'jml_mengajar_semester_aktif',
                searchable: false,
                orderable: false
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                        edit_url + '/' + data.id + '">' +
                        '    <i class="material-icons">edit</i>' +
                        '</a> ' +
                        '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\'' +
                        delete_url + '\', this)" data-id="' + data.id + '">' +
                        '    <i class="material-icons">delete_forever</i>' +
                        '</button>';
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
