<div class="container-fluid">
    <div>
        <h2 style="float: left"><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#aktivitas-semester/input-nilai/view-guru-input-nilai/' . $id_pengguna . '/' . $id_semester) }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>

        <h2 style="float: right"><a class="btn bg-green waves-effect target-link"
                href="{{ url(Request::segment(1) . '#aktivitas-semester/input-nilai/add/' . $id_pengguna . '/' . $id_semester . '/' . $id_kelas_mp) }}"><i
                    class="material-icons">note_add</i><span>Tambah Komponen Nilai</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>KOMPONEN NILAI MAPEL
                        {{ $data_kelas->nm_mata_pelajaran . ' (' . $data_kelas->kd_mata_pelajaran . ') Kelas ' . $data_kelas->nm_kelas }}
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Komponen</th>
                                    <th>Persentase Komponen</th>
                                    <th>Urutan Komponen</th>
                                    <th>Sub Komponen</th>
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
    var id_pengguna = {!! json_encode($id_pengguna) !!};
    var id_semester = {!! json_encode($id_semester) !!};

    var modul_url = 'aktivitas-semester';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'input-nilai/datatables/' + id_kelas_mp;
    var edit_url = base_url + '/' + role_url + '#' + modul_url + '/' + 'input-nilai/edit/' + id_pengguna + '/' +
        id_semester + '/' + id_kelas_mp;
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-komponen-nilai/delete';
    // add subkomponen
    var add_url = base_url + '/' + role_url + '#' + modul_url + '/' + 'input-nilai/view-sub-komponen';

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
                data: 'nm_komponen_mp',
                name: 'nm_komponen_mp'
            },
            {
                data: 'persentase_komponen_mp',
                name: 'persentase_komponen_mp'
            },
            {
                data: 'urutan_komponen_mp',
                name: 'urutan_komponen_mp'
            },
            {
                data: 'jumlah_sub_komponen_mp',
                name: 'jumlah_sub_komponen_mp'
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                        add_url + '/' + data.id + '/' + id_kelas_mp + '/' + id_pengguna + '/' +
                        id_semester + '">' +
                        '    <i class="material-icons">add-box</i>' +
                        '</a> ' +
                        '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
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
