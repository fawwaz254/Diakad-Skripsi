<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>JADWAL UJIAN SEMESTER {{$semester_aktif->tahun_ajaran}} {{strtoupper($semester_aktif->nm_semester)}}</h2>
                    </div>
                    <div class="body">
                        <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#uts_with_icon_title" data-toggle="tab" aria-expanded="true">
                                        <i class="material-icons">assignment</i> JADWAL UTS
                                    </a>
                                </li>
                                <li role="presentation">
                                    <a href="#uas_with_icon_title" data-toggle="tab">
                                        <i class="material-icons">send</i> JADWAL UAS
                                    </a>
                                </li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade active in" id="uts_with_icon_title">
                                <div class="body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover dataTable display" id="primary_table_uts">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Mata Pelajaran</th>
                                                    <th>Guru Pengampu</th>
                                                    <th>Tanggal</th>
                                                    <th>Jam UTS</th>
                                                    <th>Kelas</th>
                                                    <th>Ruangan</th>
                                                    <th>Status Online</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="uas_with_icon_title">
                                <div class="body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover dataTable display" id="primary_table_uas">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Mata Pelajaran</th>
                                                    <th>Guru Pengampu</th>
                                                    <th>Tanggal</th>
                                                    <th>Jam UAS</th>
                                                    <th>Kelas</th>
                                                    <th>Ruangan</th>
                                                    <th>Status Online</th>
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

    var modul_url           = 'akademik';
    var datatable_uts_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'jadwal-ujian/datatables-uts';
    var datatable_uas_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'jadwal-ujian/datatables-uas';

    // datatable jadwal UTS
    var primary_table_uts = $('#primary_table_uts').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: datatable_uts_url,
            type: 'GET'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'mata_pelajaran', name: 'mata_pelajaran' },
            { data: 'nm_pengguna', name: 'nm_pengguna'},
            { data: 'tgl_ujian', name: 'tgl_ujian' },
            { data: 'jadwal_jam', name: 'jadwal_jam'},
            { data: 'nm_kelas', name: 'nm_kelas'},
            { data: 'nm_ruangan', name: 'nm_ruangan'},
            { data: 'is_online', name: 'is_online'}
        ]
    });

    primary_table_uts.on( 'draw', function () {
        primary_table_uts.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();


    // datatable jadwal UAS
    var primary_table_uas = $('#primary_table_uas').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: datatable_uas_url,
            type: 'GET'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'mata_pelajaran', name: 'mata_pelajaran' },
            { data: 'nm_pengguna', name: 'nm_pengguna'},
            { data: 'tgl_ujian', name: 'tgl_ujian' },
            { data: 'jadwal_jam', name: 'jadwal_jam'},
            { data: 'nm_kelas', name: 'nm_kelas'},
            { data: 'nm_ruangan', name: 'nm_ruangan'},
            { data: 'is_online', name: 'is_online'}
        ]
    });

    primary_table_uas.on( 'draw', function () {
        primary_table_uas.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();
</script>