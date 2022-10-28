<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#rapor-sisipan/cetak-rapor/viewSetting') }}"><i
                    class="material-icons">add</i><span>Setting Urutan</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>Cetak Rapor</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kelas</th>
                                    <th>Jurusan</th>
                                    <th>Wali Kelas</th>
                                    <th>Jumlah Mapel yang sudah terisi</th>
                                    {{-- <th>Semester</th> --}}
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

<script type="text/javascript">
    var modul_url = 'rapor-sisipan';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'cetak-rapor/datatables';
    // var edit_url = role_url + '#' + modul_url + '/' + 'manajemen-materi-ajar/edit';
    // var nilai_url = role_url + '#' + modul_url + '/' + 'daftar-nilai-sts/nilai';
    // var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'daftar-nilai-sts/action-daftar-nilai-sts/delete';
    // var print_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'daftar-nilai-sts/print';
    var pdf_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'cetak-rapor/print';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url,
            type: 'GET'
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false,
                className: 'align-center'
            },
            {
                data: 'nm_kelas',
                name: 'nm_kelas',
                className: 'align-center'
            },
            {
                data: 'jurusan.nm_jurusan',
                name: 'jurusan.nm_jurusan',
            },
            {
                data: 'wali_kelas',
                name: 'wali_kelas'
            },
            {
                data: 'rapor_sisipan',
                name: 'rapor_sisipan',
                className: 'align-center'
            },
            // {
            //     data: 'jumlah',
            //     name: 'jumlah',
            //     className: 'align-center',
            //     searchable: false,
            //     orderable: false,
            //     className: 'align-center',
            //     render: function(data) {
            //         return `<p>` + data.terisi_siswa + ' / ' + data.jumlah_siswa + `</p>`  ;
            //     }},
            //     {
            //     data: 'semester',
            //     name: 'semester',
            //     className: 'align-center'
            // },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                className: 'align-center',
                render: function(data) {
                        return '<a class=" btn btn-success btn-circle waves-effect waves-circle waves-float" href="' +
                    pdf_url + '/' + data.id_kelas  + '"  target="_blank">' +
                        '    <i class="material-icons">picture_as_pdf</i>' +
                        '</a> ';
                }
            }
            // {
            //     data: 'pengguna.nm_pengguna',
            //     name: 'pengguna.nm_pengguna',
            //     className: 'align-center'
            // },
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
</script> 
