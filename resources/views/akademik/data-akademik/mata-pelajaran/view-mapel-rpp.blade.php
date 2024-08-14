<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/mata-pelajaran/rpp/add?id=' . $mata_pelajaran->id_mata_pelajaran) }}"><i
                    class="material-icons">note_add</i><span>Tambah RPP</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>DATA RPP untuk Mata Pelajaran {{ $mata_pelajaran->nm_mata_pelajaran }}
                        ({{ $mata_pelajaran->kd_mata_pelajaran }})</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Mapel</th>
                                    <th>Nama Mapel</th>
                                    <th>RPP Semester</th>
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
    var modul_url = '{{ Request::segment(2) }}';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' +
        'mata-pelajaran/rpp/datatables?id={{ $mata_pelajaran->id_mata_pelajaran }}';
    var edit_url = role_url + '#' + modul_url + '/' + 'mata-pelajaran/rpp/edit';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'mata-pelajaran/rpp/action/delete';
    let preview_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'mata-pelajaran/rpp/previewRPP/';

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
                data: 'mata_pelajaran.kd_mata_pelajaran'
            },
            {
                data: 'mata_pelajaran.nm_mata_pelajaran',
            },
            {
                data: 'semester.kode_semester',
            },
            {
                data: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    let preview =
                        `<button class='btn btn-primary btn-circle waves-effect waves-circle waves-float' onclick="preview('${data.id}')"><i class="material-icons">remove_red_eye</i></button>`
                    let hapus =
                        '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\'' +
                        delete_url + '\', this)" data-id="' + data.id + '">' +
                        '    <i class="material-icons">delete_forever</i>' +
                        '</button>';
                    return preview + hapus
                }
            }
        ],
        order: [
            [3, 'desc']
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

    function preview(id) {
        window.open(`${preview_url}?id=${id}`, '_blank')
    }
</script>
