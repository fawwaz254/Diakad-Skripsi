<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/hasil-test') }}">
                <span>Hasil Test</span>
            </a>
            <a class="btn bg-teal waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/pengguna-terkunci') }}">
                <span>UnLock Siswa</span>
            </a>
            <a class="btn bg-red waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/pengguna-dikunci') }}">
                <span>Lock Siswa</span>
            </a>
            <br />
            <br />
            <div class="card">
                <div class="header">
                    <h2>
                        List Hasil Test
                    </h2>

                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table id="primary_table" class="table table-bordered table-striped table-hover dataTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Mapel</th>
                                    <th>Waktu</th>
                                    <th>Total Siswa</th>
                                    <th>Siswa Mengerjakan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->
</div>
<script>
    var modul_url = '{{ Request::segment(2) }}';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'hasil-test/table';
    var detail_url = role_url + '#' + modul_url + '/' + 'hasil-test';
    var print_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'hasil-test/print';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,

        ajax: {
            url: datatable_url,
            type: 'POST'
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'text',
                name: 'text'
            },
            {
                data: 'action',
                name: 'action',
                render: function(data) {
                    let html = '';
                    data.nm_kelas.forEach(element => {
                        html += '- ' +
                            element + ` <br>`;
                    });
                    return html;
                }
            },
            {
                data: 'kategori_soal.nm_kategori_soal'
            },
            {
                data: 'waktu_mulai',
                searchable: false,
                orderable: true,
                render: function(data, type, row) {
                    return moment(data).format('dddd, DD MMM YYYY HH:mm') + ' - ' + moment(row
                        .waktu_selesai).format('HH:mm');
                }
            },
            {
                data: 'total_siswa',
                name: 'total_siswa',
                className: 'align-center',
                searchable: false,
                orderable: false
            },
            {
                data: 'total_mengerjakan',
                name: 'total_mengerjakan',
                className: 'align-center',
                searchable: false,
                orderable: false
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<a type="button" class="btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                        detail_url + '/detail/' + data.id + '">' +
                        '    <i class="material-icons">remove_red_eye</i>' +
                        '</a>' +
                        '<a target="_blank" class="btn btn-success btn-circle waves-effect waves-circle waves-float text-center" href="' +
                        print_url + '/' + data.id + '">' +
                        '    <i class="material-icons">print</i>' +
                        '</a>';
                }
            }
        ],
        order: [
            [4, 'desc']
        ]
    });

    primary_table.on('draw', function() {
        primary_table.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = i + 1;
        });
    }).draw();
</script>
