<div class="container-fluid">
    <div class="block-header" style=" display: flex;
    justify-content: space-between;">
        <div class="dropdown" style="display: inline; margin-right:50px">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Tahun Ajaran
                <span class="caret"></span></button>
            <ul class="dropdown-menu">
                @foreach ($data_semester as $data)
                    <li> <a onclick="changeThn(this)" data-id=" {{ $data->id_semester }} ">
                            {{ $data->tahun_ajaran . ' ' . $data->nm_semester }}
                            @if ($semester_aktif->id_semester == $data->id_semester)
                                (Aktif)
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        <input type="hidden" id="id_semester" value="">
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>Daftar Nilai Tengah Semester</h2>
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
                                    <th>Jenis Mata Pelajaran</th>
                                    <th>Kelas</th>
                                    <th>Nilai Siswa Terisi</th>
                                    <th>Semester</th>
                                    <th>Action</th>
                                    <th>Pembuat</th>
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
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'daftar-nilai-sts/datatables';
    var print_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'daftar-nilai-sts/print';
    var pdf_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'daftar-nilai-sts/pdf';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url,
            type: 'GET',
            data: function(d) {
                d.id_semester = $('#id_semester').val();
            }
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false,
                className: 'align-center'
            },
            {
                data: 'mata_pelajaran.nm_mata_pelajaran',
                name: 'mata_pelajaran.nm_mata_pelajaran',
                className: 'align-center',
                orderable: false,
            },
            {
                data: 'mata_pelajaran.jenis_mata_pelajaran.nm_jenis_mata_pelajaran',
                name: 'mata_pelajaran.jenis_mata_pelajaran.nm_jenis_mata_pelajaran',
                className: 'align-center',
                orderable: false,
            },
            {
                data: 'kelas.nm_kelas',
                name: 'kelas.nm_kelas',
                className: 'align-center',
                orderable: false,
            },
            {
                data: 'jumlah',
                name: 'jumlah',
                className: 'align-center',
                searchable: false,
                orderable: false,
                className: 'align-center',
                render: function(data) {
                    return `<p>` + data.terisi_siswa + ' / ' + data.jumlah_siswa + `</p>`;
                }
            },
            {
                data: 'semester',
                name: 'semester',
                className: 'align-center',
                searchable: false,
                orderable: false,
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                className: 'align-center',
                render: function(data) {
                    return '<a class=" btn btn-success btn-circle waves-effect waves-circle waves-float " href="' +
                        print_url + '/' + data.id + '"  target="_blank">' +
                        ' <i class="material-icons">print</i>' +
                        '</a> ' +
                        '<a class=" btn btn-success btn-circle waves-effect waves-circle waves-float" href="' +
                        pdf_url + '/' + data.id + '"  target="_blank">' +
                        '    <i class="material-icons">picture_as_pdf</i>' +
                        '</a> ';
                }
            },
            {
                data: 'pengguna.nm_pengguna',
                name: 'pengguna.nm_pengguna',
                className: 'align-center',
                orderable: false,
            },
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

    function changeThn(value) {
        var item = $(value);
        $('#id_semester').val(item.attr('data-id'));
        primary_table.draw();
    }
</script>
