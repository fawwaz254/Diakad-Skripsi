<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-indigo">
                    <h2>
                        DATA HOME VISIT
                    </h2>
                </div>
                <div class="body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#belum_lengkap" data-toggle="tab" aria-expanded="true">
                                <i class="material-icons">done</i> BERKAS BELUM LENGKAP
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#sudah_lengkap" data-toggle="tab">
                                <i class="material-icons">done_all</i> BERKAS LENGKAP
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="belum_lengkap">
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table_belum_lengkap">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Siswa</th>
                                                <th>NIS</th>
                                                <th>NISN</th>
                                                <th>Guru Wali Kelas</th>
                                                <th>Semester</th>
                                                <th>Nomor HP Wali Murid</th>
                                                <th>Alamat Wali Murid</th>
                                                <th>Rangkuman Home Visit</th>
                                                <th>Tanggal Home Visit</th>
                                                <th>Guru Kesiswaan</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="sudah_lengkap">
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table_sudah_lengkap">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Siswa</th>
                                                <th>NIS</th>
                                                <th>NISN</th>
                                                <th>Guru Wali Kelas</th>
                                                <th>Semester</th>
                                                <th>Nomor HP Wali Murid</th>
                                                <th>Alamat Wali Murid</th>
                                                <th>Rangkuman Home Visit</th>
                                                <th>Tanggal Home Visit</th>
                                                <th>Guru Kesiswaan</th>
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
    </div>
</div>
@include('scriptjs')
<script>
    var modul_url                   = 'data-kesiswaan';
    var datatable_belum_lengkap     = base_url + '/' + role_url + '/' + modul_url + '/' + 'home-visit/datatables/0';
    var datatable_sudah_lengkap     = base_url + '/' + role_url + '/' + modul_url + '/' + 'home-visit/datatables/1';
    var detail_url        = role_url + '#' + modul_url + '/' + 'home-visit/edit';

    // datatable jadwal UTS
    var primary_table_belum_lengkap = $('#primary_table_belum_lengkap').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_belum_lengkap,
            type: 'GET'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'nm_siswa', name: 'p1.nm_pengguna' },
            { data: 'nis_siswa', name: 'siswa.nis_siswa' },
            { data: 'nisn_siswa', name: 'siswa.nisn_siswa' },  
            { data: 'nm_wali_kelas', name: 'p2.nm_pengguna' },  
            { data: 'semester', name: 'semester.tahun_ajaran' },  
            { data: 'nomor_hp_wali_murid', name: 'home_visit.nomor_hp_wali_murid' },  
            { data: 'alamat_wali_murid', name: 'home_visit.alamat_wali_murid' },  
            { data: 'rangkuman_home_visit', name: 'home_visit.rangkuman_home_visit' },  
            { data: 'tgl_home_visit', name: 'home_visit.created_at' },  
            { data: 'nm_guru_kesiswaan', name: 'p3.nm_pengguna' },  
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ detail_url + '/' + data.id +'">'+
                    '    <i class="material-icons">edit</i>'+
                    '</a>';
                }
            }
        ]
    });

    primary_table_belum_lengkap.on( 'draw', function () {
        primary_table_belum_lengkap.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * 10;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();

     // datatable jadwal UTS
    var primary_table_sudah_lengkap = $('#primary_table_sudah_lengkap').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_sudah_lengkap,
            type: 'GET'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'nm_siswa', name: 'p1.nm_pengguna' },
            { data: 'nis_siswa', name: 'siswa.nis_siswa' },
            { data: 'nisn_siswa', name: 'siswa.nisn_siswa' },  
            { data: 'nm_wali_kelas', name: 'p2.nm_pengguna' },  
            { data: 'semester', name: 'semester.tahun_ajaran' },  
            { data: 'nomor_hp_wali_murid', name: 'home_visit.nomor_hp_wali_murid' },  
            { data: 'alamat_wali_murid', name: 'home_visit.alamat_wali_murid' },  
            { data: 'rangkuman_home_visit', name: 'home_visit.rangkuman_home_visit' },  
            { data: 'tgl_home_visit', name: 'home_visit.created_at' },  
            { data: 'nm_guru_kesiswaan', name: 'p3.nm_pengguna' },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ detail_url + '/' + data.id +'">'+
                    '    <i class="material-icons">edit</i>'+
                    '</a>';
                }
            }
        ]
    });

    primary_table_sudah_lengkap.on( 'draw', function () {
        primary_table_sudah_lengkap.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * 10;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();
</script>