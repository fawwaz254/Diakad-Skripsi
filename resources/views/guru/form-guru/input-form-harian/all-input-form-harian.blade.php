<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Data Input Form {{ $form->nm_form }}</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Dikumpulkan Pada</th>
                                    <th>Diubah Pada</th>
                                    <th>Edit Data</th>
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
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'input-form-harian/all/datatables/' + '{{Request::segment(5)}}';
    var add_url = role_url + '#' + modul_url + '/' + 'input-form-harian/add';
    // var detail_url = role_url + '#' + modul_url + '/' + 'input-form-harian/detail';

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
                data: 'time',
                name: 'time',
                className: 'align-center',
                searchable: false,
                orderable: false,
            },
            {
                data: 'last_update',
                name: 'last_update',
                className: 'align-center',
                searchable: false,
                orderable: false,
            },
            {
                data: 'action',
                searchable: false,
                orderable: false,
                className: 'align-center',
                render: function(data) {
                    return '<a type="button" class="btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                        add_url + '/' + data.id + '">' +
                        '    <i class="material-icons">add</i>' +
                        '</a>';

                }
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
</script>
