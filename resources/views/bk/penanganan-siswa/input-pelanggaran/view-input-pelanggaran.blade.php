<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#penanganan-siswa/input-pelanggaran/add')}}"><i class="material-icons">note_add</i><span>Tambah Pelanggaran Siswa</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>DATA PELANGGARAN SISWA</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th>Nama Guru Input</th>
                                        <th>Semester</th>
                                        <th>Tingkat Pelanggaran</th>
                                        <th>Keterangan Sub-Kategori</th>
                                        <th>Catatan Pelanggaran</th>
                                        <th>Catatan Khusus</th>
                                        <th>Tanggal Pelanggaran</th>
                                        <th>Aktor Input Pelanggaran</th>
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
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var modul_url       = 'penanganan-siswa';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'input-pelanggaran/datatables';
    var edit_url        = role_url + '#' + modul_url + '/' + 'input-pelanggaran/edit';
    var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-input-pelanggaran/delete';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
responsive: true,
        ajax: {
            url: datatable_url,
            type: 'GET'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'nm_siswa', name: 'pengguna.nm_pengguna' },
            { data: 'nm_kelas', name: 'kelas.nm_kelas' },
            { data: 'nm_input', name: 'nm_input', searchable: false, orderable: false },
            { data: 'semester', name: 'semester.tahun_ajaran' },
            { data: 'tingkat_pelanggaran', name: 'tingkat_pelanggaran', searchable: false, orderable: false },
            { data: 'keterangan_subkategori_pelanggaran', name: 'subkategori_pelanggaran.keterangan_subkategori_pelanggaran' },
            { data: 'catatan_pelanggaran', name: 'pelanggaran_siswa.catatan_pelanggaran' },
            { data: 'catatan_pelanggaran_khusus', name: 'catatan_pelanggaran_khusus', searchable: false, orderable: false },
            { data: 'tgl_pelanggaran', name: 'pelanggaran_siswa.tgl_pelanggaran' },
            { data: 'aktor_input_pelanggaran', name: 'aktor_input_pelanggaran', searchable: false, orderable: false },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    if(data.is_sudah_tindakan == 1) {
                        return '<a>Sudah Ada Tindakan</a>';
                    }
                    else {
                        return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ edit_url + '/' + data.id +'">'+
                        '    <i class="material-icons">edit</i>'+
                        '</a>'+
                        '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\''+ delete_url +'\', this)" data-id="'+  data.id +'">'+
                        '    <i class="material-icons">delete_forever</i>'+
                        '</button>';
                    }
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