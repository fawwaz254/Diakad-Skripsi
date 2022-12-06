<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Data Siswa
                    </h2>
                </div>
                <div class="body">
                    <div class="body">
                        <div class="table-responsive">
                            <table
                                class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                                id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No. </th>
                                        <th>Nama</th>
                                        <th>Kelas</th>
                                        <th>Kegiatan Siswa</th>
                                        <th>Prestasi Siswa</th>
                                        <th>Informasi Tambahan</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
    @include('scriptjs')

    <script>
        $(document).ready(function() {
            var modul_url = 'wali-kelas';
            var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' +
                'input-skpi-siswa/datatables';
            // var detail_url = role_url + '#' + modul_url + '/input-biodata-siswa/edit';
            var kegiatan_siswa_url = role_url + '#' + modul_url + '/input-skpi-siswa/kegiatan_siswa';
            var prestasi_siswa = role_url + '#' + modul_url + '/input-skpi-siswa/prestasi_siswa';
            var informasi_tambahan = role_url + '#' + modul_url + '/input-skpi-siswa/informasi_tambahan';

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
                        orderable: false
                    },
                    {
                        data: 'pengguna.nm_pengguna',
                        name: 'pengguna.nm_pengguna'
                    },
                    {
                        data: 'kelas.nm_kelas',
                        name: 'kelas.nm_kelas'
                    },
                    {
                        data: 'kegiatan_siswa',
                        name: 'kegiatan_siswa',
                        searchable: false,
                        orderable: false,
                        render: function(data) {
                            return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float text-center" href="' +
                                kegiatan_siswa_url + '/' + data.id + '">' +
                                '    <i class="material-icons">remove_red_eye</i>' +
                                '</a> ' + data.count + ' Data'
                        }
                    },
                    {
                        data: 'prestasi_siswa',
                        name: 'prestasi_siswa',
                        searchable: false,
                        orderable: false,
                        render: function(data) {
                            return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float text-center" href="' +
                                prestasi_siswa + '/' + data.id + '">' +
                                '    <i class="material-icons">remove_red_eye</i>' +
                                '</a> ' + data.count + ' Data'
                        }
                    },
                    {
                        data: 'informasi_tambahan',
                        name: 'informasi_tambahan',
                        searchable: false,
                        orderable: false,
                        render: function(data) {
                            return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float text-center" href="' +
                                informasi_tambahan + '/' + data.id + '">' +
                                '    <i class="material-icons">remove_red_eye</i>' +
                                '</a> ' + data.count + ' Data'
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
                });
            }).draw();


        });
    </script>
