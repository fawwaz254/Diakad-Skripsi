<div class="container-fluid">
    <div class="block-header">
        
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>List Custom Form</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Form</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                    <th>Diserahkan</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('scriptjs')
<script>
    var modul_url = '{{ Request::segment(2) }}';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'data/datatables';
    var add_url = role_url + '#' + modul_url + '/' + 'custom-form/';
    var history_url = role_url + '#' + modul_url + '/' + 'custom-form/submitted/';

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
                orderable: false,
                className: 'align-center',
            },
            {
                data: 'nm_custom_form',
                name: 'nm_custom_form'
            },
            {
                data: 'is_aktif',
                name: 'is_aktif',
                className: 'align-center',
                searchable: false,
                render: function(data) {

                    return data == 1 ? 'AKTIF' : 'TIDAK AKTIF'
                }
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                className: 'align-center',

                render: function(data) {
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float text-center" href="'+
                     add_url + data.id + '/form/create/' + '">' +
                        '    <i class="material-icons">add</i>' +
                        '</a> '
                }
            },
            {
                data: 'action',
                name: 'history',
                searchable: false,
                orderable: false,
                className: 'align-center',

                render: function(data) {
                    return '<a class="target-link btn btn-danger btn-circle waves-effect waves-circle waves-float text-center" href="'+
                     history_url + data.id + '">' +
                        '    <i class="material-icons">history</i>' +
                        '</a> '
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