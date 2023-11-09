<div class="container-fluid">
    <div class="block-header">
        <h2>
            <a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#ujian/try-out-reguler-online') }}"><i
                    class="material-icons">backspace</i><span>Kembali ke Daftar Ujian</span></a>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>Daftar Mata Pelajaran</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Kelas</th>
                                    <th>Semester</th>
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
</div>
@include('scriptjs')
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var ids = {!! json_encode($id) !!};

    var modul_url = 'ujian';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'try-out-reguler-online/datatablesMapel/' +
        ids;
    var add_url = role_url + '#' + modul_url + '/' + 'try-out-reguler-online/addUjian';

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
                data: 'kd_mata_pelajaran',
                name: 'kd_mata_pelajaran'
            },
            {
                data: 'nm_mata_pelajaran',
                name: 'nm_mata_pelajaran'
            },
            {
                data: 'nm_kelas',
                name: 'nm_kelas'
            },
            {
                data: 'semester',
                name: 'semester'
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                        add_url + '/' + ids + '/' + data.id + '">' +
                        '    <i class="material-icons">edit</i>' +
                        '</a>';
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
