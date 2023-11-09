<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link "
                href="{{ url(Request::segment(1) . '#penilaian/komponen-nilai/view-kelas/' . $id_kelas_mp) }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#penilaian/komponen-nilai/add-sub-komponen/' . $id_kelas_mp . '/' . $id_komponen_mp) }}"><i
                    class="material-icons">note_add</i><span>Tambah Sub Komponen Nilai</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>SUB KOMPONEN NILAI</h2>
                    <p>Mapel: {{ $data_kelas->nm_mata_pelajaran . ' (' . $data_kelas->nm_kelas . ')' }} | Komponen:
                        {{ $data_komponen->nm_komponen_mp }}</p>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>KD Sub Komponen</th>
                                    <th>Nama Sub Komponen</th>
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
    var id_kelas_mp = {!! json_encode($id_kelas_mp) !!};
    var id_komponen_mp = {!! json_encode($id_komponen_mp) !!};

    var modul_url = 'penilaian';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'komponen-nilai/datatables-subkomponen/' +
        id_komponen_mp;

    // add subkomponen
    var edit_url = role_url + '#' + modul_url + '/' + 'komponen-nilai/edit-sub-komponen/' + id_kelas_mp + '/' +
        id_komponen_mp;
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-subkomponen-nilai/delete';

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
                data: 'kd_subkomponen_mp',
                name: 'kd_subkomponen_mp'
            },
            {
                data: 'nm_subkomponen_mp',
                name: 'nm_subkomponen_mp'
            },
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
