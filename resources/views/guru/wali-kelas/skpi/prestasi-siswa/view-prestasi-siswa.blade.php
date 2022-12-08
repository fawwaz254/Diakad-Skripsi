<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link "
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3)) }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a>
            <a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3) . '/' . Request::segment(4) . '/add/' . $id_siswa) }}"><i
                    class="material-icons">note_add</i><span>Tambah Prestasi Siswa</span></a>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <input type="hidden" name="id_siswa" value="{{ $id_siswa }}">
                <div class="header">
                    <h2>Data Prestasi Siswa</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Prestasi</th>
                                    <th>Tingkat Prestasi</th>
                                    <th>Jenis Lomba</th>
                                    <th>Peringkat</th>
                                    <th>Link Sertifikat</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th>Semester</th>
                                    <th>Kelas</th>
                                    <th>Lokasi</th>
                                    <th>Penyelenggara</th>
                                    <th>Tanggal</th>
                                    {{-- <th>Ekstrakurikuler</th> --}}
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
    var modul_url = '{{ Request::segment(2) }}';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' +
        'input-skpi-siswa/prestasi-siswa/datatables';
    var edit_url = role_url + '#' + modul_url + '/' + 'input-skpi-siswa/prestasi-siswa/edit';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' +
        'input-skpi-siswa/prestasi-siswa/action/delete';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        // serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url,
            type: 'GET',
            data: function(params) {
                params.id_siswa = $('input[name=id_siswa]').val();
            }
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'nm_prestasi_siswa',
                name: 'nm_prestasi_siswa'
            },
            {
                data: 'nm_tingkat_prestasi_siswa',
                name: 'nm_tingkat_prestasi_siswa'
            },
            {
                data: 'jenis_lomba_siswa',
                name: 'jenis_lomba_siswa'
            },
            {
                data: 'peringkat_prestasi_siswa',
                name: 'peringkat_prestasi_siswa'
            },
            {
                data: 'action',
                name: 'link_sertifikat',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<a class="btn btn-success btn-circle waves-effect waves-circle waves-float text-center" target="_blank" href="' +
                        data.link_sertifikat + '">' +
                        '    <i class="material-icons">link</i>' +
                        '</a>';
                }
            },
            {
                data: 'keterangan_status',
                name: 'keterangan_status',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return `<span class="badge bg-` + data.color + `">` + data.status + `</span>`
                }
            },
            {
                data: 'keterangan',
                name: 'keterangan'
            },
            {
                data: 'semester',
                name: 'semester'
            },
            {
                data: 'nm_kelas',
                name: 'nm_kelas'
            },
            {
                data: 'lokasi_prestasi_siswa',
                name: 'lokasi_prestasi_siswa'
            },
            {
                data: 'penyelenggara_prestasi_siswa',
                name: 'penyelenggara_prestasi_siswa'
            },
            {
                data: 'tgl_prestasi_siswa',
                name: 'tgl_prestasi_siswa'
            },
            // { data: 'nm_ekskul', name: 'nm_ekskul' },
            {
                data: 'nm_guru_pendamping',
                name: 'nm_guru_pendamping'
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    if (data.status == 0) {
                        return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float text-center" href="' +
                            edit_url + '/' + data.id + '">' +
                            '    <i class="material-icons">edit</i>' +
                            '</a> ' +
                            '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\'' +
                            delete_url + '\', this)" data-id="' + data.id + '">' +
                            '    <i class="material-icons">delete_forever</i>' +
                            '</button> ';
                    } else {
                        return '-';
                    }
                }
            }
        ],
        columnDefs: [{
            className: 'text-center',
            targets: [5]
        }, ]
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
