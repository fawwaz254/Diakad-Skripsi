<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#e-learning/manajemen-materi-ajar/add') }}"><i
                    class="material-icons">add</i><span>Tambah Materi Ajar</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>DATA MATERI AJAR</h2>
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
                                    <th>Judul Materi</th>
                                    <th>Kelas</th>
                                    <th>Jumlah View</th>
                                    <th>File Materi</th>
                                    <th>Status</th>
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
    var modul_url = 'e-learning';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'manajemen-materi-ajar/datatables';
    var edit_url = role_url + '#' + modul_url + '/' + 'manajemen-materi-ajar/edit';
    var view_url = role_url + '#' + modul_url + '/' + 'manajemen-materi-ajar/view';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-manajemen-materi-ajar/delete';

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
                data: 'mapel',
                name: 'mapel',
                className: 'align-center'
            },
            {
                data: 'judul_materi',
                name: 'judul_materi',
                className: 'align-center'
            },
            {
                data: 'kelas.nm_kelas',
                name: 'kelas.nm_kelas',
                className: 'align-center'
            },
            {
                data: 'jumlah',
                name: 'jumlah',
                className: 'align-center',
                searchable: false,
                orderable: false,
                className: 'align-center',
                render: function(data) {
                    return  '<a href="' +
                        view_url + '/' + data.id + '">' +
                        `<p>` + data.jumlah_view + '/' + data.jumlah_siswa + `</p>` +
                        '</a> ' ;
                }},
            {
                data: 'action',
                name: 'action',
                render: function(data) {
                    console.log(data.materi_ajar_file);
                    let html = '';
                    html += '<ul>';
                    $.each(data.materi_ajar_file, function(i, value) {
                        html += `<li><a href="` + value.link_file + `" target="_blank">` + value
                            .nm_file + ` ( ` + value.type_file + ` ) </a></li>`;
                    })
                    html += '</ul>';

                    return html;

                }
            },
            {
                data: 'action',
                name: 'action',
                className: 'align-center',
                render: function(data) {
                    if (data.status == 0) {
                        return `<span class="badge bg-red">Tidak Aktif</span>`;
                    } else {
                        return `<span class="badge bg-teal">Aktif</span>`;
                    }
                }
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                className: 'align-center',
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
