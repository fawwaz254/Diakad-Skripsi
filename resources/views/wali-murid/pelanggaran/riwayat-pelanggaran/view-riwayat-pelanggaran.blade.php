<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>RIWAYAT PELANGGARAN SISWA</h2>
                    </div>
                    <div class="body">
                        <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#nonkbm_with_icon_title" data-toggle="tab" aria-expanded="true">
                                        <i class="material-icons">send</i> PELANGGARAN NON-KBM
                                    </a>
                                </li>
                                <li role="presentation">
                                    <a href="#kbm_with_icon_title" data-toggle="tab">
                                        <i class="material-icons">assignment</i> PELANGGARAN KBM
                                    </a>
                                </li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade active in" id="nonkbm_with_icon_title">
                                <div class="body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table_nonkbm">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Catatan Pelanggaran</th>
                                                    <th>Tanggal Pelanggaran</th>
                                                    <th>Guru Input Pelanggaran</th>
                                                    <th>Jenis Tindakan</th>
                                                    <th>Catatan Tindakan</th>
                                                    <th>Tanggal Tindakan</th>
                                                    <th>Guru Input Tindakan</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="kbm_with_icon_title">
                                <div class="body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table_kbm">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Semester</th>
                                                    <th>Mata Pelajaran</th>
                                                    <th>Guru Pengampu</th>
                                                    <th>Catatan Pelanggaran</th>
                                                    <th>Tanggal Pelanggaran</th>
                                                    <th>Jenis Tindakan</th>
                                                    <th>Catatan Tindakan</th>
                                                    <th>Tanggal Tindakan</th>
                                                    <th>Guru Input Tindakan</th>
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
        </div>
    </div>
</div>
<script>

    var modul_url           = 'pelanggaran';
    var datatable_nonkbm_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'riwayat-pelanggaran/datatables-non-kbm';
    var datatable_kbm_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'riwayat-pelanggaran/datatables-kbm';

    // datatable pelanggaran non-KBM
    var primary_table_nonkbm = $('#primary_table_nonkbm').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_nonkbm_url,
            type: 'GET'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'catatan_pelanggaran', name: 'catatan_pelanggaran' },
            { data: 'tgl_pelanggaran', name: 'tgl_pelanggaran'},
            { data: 'aktor_input_pelanggaran', name: 'aktor_input_pelanggaran' },
            { data: 'nm_jenis_tindakan', name: 'nm_jenis_tindakan'},
            { data: 'catatan_tindakan_pelanggaran', name: 'catatan_tindakan_pelanggaran'},
            { data: 'tgl_tindakan_pelanggaran', name: 'tgl_tindakan_pelanggaran'},
            { data: 'aktor_input_tindakan_pelanggaran', name: 'aktor_input_tindakan_pelanggaran'}
        ]
    });

    primary_table_nonkbm.on( 'draw', function () {
        primary_table_nonkbm.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();


    // datatable pelanggaran KBM
    var primary_table_kbm = $('#primary_table_kbm').DataTable({
        processing: true,
        serverSide: true,
responsive: true,
        ajax: {
            url: datatable_kbm_url,
            type: 'GET'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'semester', name: 'semester' },
            { data: 'mata_pelajaran', name: 'mata_pelajaran' },
            { data: 'nm_pengguna', name: 'nm_pengguna'},
            { data: 'catatan_pelanggaran', name: 'catatan_pelanggaran' },
            { data: 'tgl_pelanggaran', name: 'tgl_pelanggaran'},
            { data: 'nm_jenis_tindakan', name: 'nm_jenis_tindakan'},
            { data: 'catatan_tindakan_pelanggaran', name: 'catatan_tindakan_pelanggaran'},
            { data: 'tgl_tindakan_pelanggaran', name: 'tgl_tindakan_pelanggaran'},
            { data: 'aktor_input_tindakan_pelanggaran', name: 'aktor_input_tindakan_pelanggaran'}
        ]
    });

    primary_table_kbm.on( 'draw', function () {
        primary_table_kbm.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();
</script>