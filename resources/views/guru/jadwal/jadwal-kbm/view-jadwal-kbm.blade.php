<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            {{-- <div class="card">
                <div class="header">
                    <h2>
                        PILIH SEMESTER
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/view-kbm-tanpa-jadwal-post') }}">
                        {{ csrf_field() }}
                        <h2 class="card-inside-title">
                            Semester
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_semester">
                                    @foreach ($data_semester as $data)
                                        <option value="{{ $data->id_semester }}"
                                            @if ($data->is_aktif_semester == 1) selected @endif>
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
            </div> --}}

            <div class="card" style="margin-top: 10px">
                {{ csrf_field() }}
                <div class="header">
                    <h2>JADWAL KBM SEMESTER {{ $semester_aktif->tahun_ajaran }}
                        {{ strtoupper($semester_aktif->nm_semester) }}</h2>
                </div>
                <div class="body">
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table class="table table-bordered table-striped table-hover dataTable display"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Hari</th>
                                    <th>Jam KBM</th>
                                    <th>Kelas</th>
                                    <th>Ruangan</th>
                                    <th>Status PJMP</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card" style="margin-top: 10px">
                {{ csrf_field() }}
                <div class="header">
                    <h2>JADWAL KBM TANPA JADWAL SEMESTER {{ $semester_aktif->tahun_ajaran }}
                        {{ strtoupper($semester_aktif->nm_semester) }}</h2>
                </div>
                <div class="body">
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table class="table table-bordered table-striped table-hover dataTable display"
                            id="primary_table2">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Kelas</th>
                                    <th>Status PJMP</th>
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
    var modul_url = 'jadwal';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'jadwal-kbm/datatables';
    var datatable_url2 = base_url + '/' + role_url + '/' + modul_url + '/' + 'kbm-tanpa-jadwal/datatables';

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
                data: 'nm_jadwal_hari',
                name: 'nm_jadwal_hari'
            },
            {
                data: 'jadwal_jam',
                name: 'jadwal_jam'
            },
            {
                data: 'nm_kelas',
                name: 'nm_kelas'
            },
            {
                data: 'nm_ruangan',
                name: 'nm_ruangan'
            },
            {
                data: 'status_pjmp',
                name: 'status_pjmp'
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

    var primary_table = $('#primary_table2').DataTable({
        processing: true,
        serverSide: true,
        dom: 'Bfrtip',
        lengthMenu: dtLengButton,
        buttons: dtButtonConfig,
        ajax: {
            url: datatable_url2,
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
                data: 'nm_kelas',
                name: 'nm_kelas'
            },
            {
                data: 'status_pjmp',
                name: 'status_pjmp'
            }
        ]
    });

    primary_table2.on('draw', function() {
        primary_table2.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
            primary_table2.cell(cell).invalidate('dom');
        });
    }).draw();
</script>
