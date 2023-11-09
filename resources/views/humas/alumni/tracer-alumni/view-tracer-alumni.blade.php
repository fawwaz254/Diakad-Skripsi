<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/tracer-alumni/add') }}">
                <i class="material-icons">note_add</i><span>Tambah Alumni</span></a> <a
                class="btn bg-green waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/tracer-alumni/cetak2') }}">
                <i class="material-icons">local_printshop</i><span>Cetak Data Alumni</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>DATA ALUMNI</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table" style="wide : 100 %">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Tahun Lulus</th>
                                    <th>Kompetensi Keahlian</th>
                                    <th>Status</th>
                                    {{-- <th>Status Verifikasi</th> --}}
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
<script>
    var modul_url = '{{ Request::segment(2) }}';
    var menu_url = '{{ Request::segment(3) }}';

    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/tracer-alumni/datatables';
    var edit_url = role_url + '#' + modul_url + '/tracer-alumni/edit';
    var detail_url = role_url + '#' + modul_url + '/kategori-pertanyaan/detail';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/tracer-alumni/action/delete';

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
                orderable: false
            },
            {
                data: 'nm_c_siswa'
            },
            {
                data: 'nm_kelas'
            },
            {
                data: 'tahun_lulus'
            },
            {
                data: 'nm_jurusan'
            },
            {
                data: 'status'
            },
            // {
            //     data: 'status_verifikasi',
            //     name: 'status_verifikasi',
            //     searchable: false,
            //     orderable: false,
            //     render: function(data) {
            //         return `<span class="badge bg-` + data.color + `">` + data.status + `</span>`
            //     }
            // },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                        edit_url + '/' + data.id + '">' +
                        '    <i class="material-icons">edit</i>' +
                        '</a> ' +
                        '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\'' +
                        delete_url + '\', this)" data-id="' + data.id + '">' +
                        '    <i class="material-icons">delete_forever</i>' +
                        '</button>';
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
</script>
