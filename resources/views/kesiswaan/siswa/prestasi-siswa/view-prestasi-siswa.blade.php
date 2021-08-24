<div class="container-fluid">
    <div class="block-header">
        <h2>
            <a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#data-kesiswaan/prestasi-siswa/add')}}"><i class="material-icons">note_add</i><span>Tambah Prestasi Siswa</span></a>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>Data Prestasi Siswa</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Prestasi</th>
                                        <th>Tingkat Prestasi</th>
                                        <th>Jenis Prestasi</th>
                                        <th>Jenis Lomba</th>
                                        <th>Peringkat</th>
                                        <th>Nama Siswa</th>
                                        <th>NISN</th>
                                        <th>NIS</th>
                                        <th>Semester</th>
                                        <th>Kelas</th>
                                        <th>Lokasi</th>
                                        <th>Penyelenggara</th>
                                        <th>Tanggal</th>
                                        <th>Ekstrakurikuler</th>
                                        <th>Guru Pendamping</th>
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
@include('scriptjs')
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var modul_url       = 'data-kesiswaan';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'prestasi-siswa/datatables';
    var edit_url        = role_url + '#' + modul_url + '/' + 'prestasi-siswa/edit';
    var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-prestasi-siswa/delete';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        // responsive: true,
        dom: 'Bfrtip',
        lengthMenu: dtLengButton,
        buttons: dtButtonConfig,
        ajax: {
            url: datatable_url,
            type: 'GET'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'nm_prestasi_siswa', name: 'nm_prestasi_siswa' },
            { data: 'nm_tingkat_prestasi_siswa', name: 'nm_tingkat_prestasi_siswa' },
            { data: 'jenis_prestasi', name: 'jenis_prestasi' },
            { data: 'jenis_lomba_siswa', name: 'jenis_lomba_siswa' },
            { data: 'peringkat_prestasi_siswa', name: 'peringkat_prestasi_siswa' },
            { data: 'nm_siswa', name: 'nm_siswa' },
            { data: 'nisn_siswa', name: 'nisn_siswa' },
            { data: 'nis_siswa', name: 'nis_siswa' },
            { data: 'semester', name: 'semester' },
            { data: 'nm_kelas', name: 'nm_kelas' },
            { data: 'lokasi_prestasi_siswa', name: 'lokasi_prestasi_siswa' },
            { data: 'penyelenggara_prestasi_siswa', name: 'penyelenggara_prestasi_siswa' },
            { data: 'tgl_prestasi_siswa', name: 'tgl_prestasi_siswa' },
            { data: 'nm_ekskul', name: 'nm_ekskul' },
            { data: 'nm_guru_pendamping', name: 'nm_guru_pendamping' },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ edit_url + '/' + data.id +'">'+
                    '    <i class="material-icons">edit</i>'+
                    '</a> '+
                    '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\''+ delete_url +'\', this)" data-id="'+  data.id +'">'+
                    '    <i class="material-icons">delete_forever</i>'+
                    '</button>';
                }
            }
        ]
    });

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();
</script>
