<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Data List Form Harian</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Form</th>
                                    <th>Jam Pengisian</th>
                                    <th>Input Data</th>
                                    <th>Total Data</th>
                                    <th>View Data</th>
                                    <th>Data Terakhir</th>
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
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'input-form-harian/datatables';
    var add_url = role_url + '#' + modul_url + '/' + 'input-form-harian/add';
    var detail_url = role_url + '#' + modul_url + '/' + 'input-form-harian/detail';

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
                className: 'align-center',
            },
            {
                data: 'nm_form',
                name: 'nm_form'
            },
            {
                data: 'time',
                name: 'time',
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
            {
                data: 'jumlah_jawaban',
                name: 'jumlah_jawaban',
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
                        '    <i class="material-icons">remove_red_eye</i>' +
                        '</a>';

                }
            },
            {
                data: 'last_data',
                name: 'last_data',
                className: 'align-center',
                searchable: false,
                orderable: false,
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
