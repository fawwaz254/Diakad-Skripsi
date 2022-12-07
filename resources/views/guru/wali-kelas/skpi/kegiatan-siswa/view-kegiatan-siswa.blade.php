<div class="container-fluid">
    <div class="block-header">
        <h2>
            <h2><a class="btn bg-blue waves-effect target-link "
                    href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3)) }}"><i
                        class="material-icons">backspace</i><span>Kembali</span></a>
                <a class="btn bg-blue waves-effect target-link"
                    href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3) . '/' . Request::segment(4) . '/add/' . $id_siswa) }}"><i
                        class="material-icons">note_add</i><span>Tambah Kegiatan Siswa</span></a>
            </h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>Data Kegiatan Siswa</h2>
                </div>
                <input type="hidden" name="id_siswa" value="{{ $id_siswa }}">
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kegiatan</th>
                                    <th>Tanggal</th>
                                    <th>Lokasi</th>
                                    <th>Penyelenggara</th>
                                    <th>Link Sertifikat</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
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
        'input-skpi-siswa/kegiatan-siswa/datatables';
    var edit_url = role_url + '#' + modul_url + '/' + 'input-skpi-siswa/kegiatan-siswa/edit';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' +
        'input-skpi-siswa/kegiatan-siswa/action/delete';
    // alert(delete_url);
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
                data: 'nm_kegiatan_siswa',
                name: 'nm_kegiatan_siswa'
            },
            // { data: 'nm_tingkat_prestasi_siswa', name: 'nm_tingkat_prestasi_siswa' },
            {
                data: 'tgl_kegiatan_siswa',
                name: 'tgl_kegiatan_siswa'
            },
            {
                data: 'lokasi_kegiatan_siswa',
                name: 'lokasi_kegiatan_siswa'
            },
            {
                data: 'penyelenggara_kegiatan_siswa',
                name: 'penyelenggara_kegiatan_siswa'
            },
            {
                data: 'action',
                name: 'nm_kegiatan_siswa',
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
            targets: [4]
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
