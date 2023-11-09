<div class="container-fluid">
    <div class="block-header">
        <h2>

            <a class="btn bg-blue waves-effect target-link "
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3)) }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a>
            {{-- <a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#rapor-sisipan/cetak-rapor/addSub') }}"><i
                    class="material-icons">add</i><span>Tambah Sub</span></a> --}}
            <a class="btn bg-green waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3) . '/' . Request::segment(4) . '/' . 'add') }}"><i
                    class="material-icons">add</i><span>Tambah
                    Deskrispi</span></a>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>Deskripsi Mata Pelajaran</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Tingkat</th>
                                    <th>KD</th>
                                    <th>Deskripsi 1</th>
                                    <th>Deskripsi 2</th>
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
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'cetak-rapor/datatablesViewDeskripsi';
    // var edit_url = role_url + '#' + modul_url + '/' + 'manajemen-materi-ajar/edit';
    // var nilai_url = role_url + '#' + modul_url + '/' + 'daftar-nilai-sts/nilai';
    // var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'daftar-nilai-sts/action-daftar-nilai-sts/delete';
    // var print_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'daftar-nilai-sts/print';
    var add = base_url + '/' + role_url + '#' + modul_url + '/' + 'cetak-rapor/addSetting';
    var edit_url = base_url + '/' + role_url + '#' + modul_url + '/' + 'cetak-rapor/editDeskripsi';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
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
                data: 'nm_mata_pelajaran',
                name: 'nm_mata_pelajaran',
                className: 'align-center'
            },
            {
                data: 'tingkat',
                name: 'tingkat',
                className: 'align-center'
            },
            {
                data: 'kd_deskripsi',
                name: 'kd_deskripsi',
                className: 'align-center'
            },
            {
                data: 'deskripsi1',
                name: 'deskripsi1'
            },

            {
                data: 'deskripsi2',
                name: 'deskripsi2'
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
                        edit_url + '/' + data.id_rapor_sisipan_deskripsi + '" >' +
                        '    <i class="material-icons">edit</i>' +
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
