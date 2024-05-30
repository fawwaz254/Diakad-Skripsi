<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>Data List Rekap Form</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Form</th>
                                    <th>Role</th>
                                    <th>Jenis</th>
                                    <th>Status</th>
                                    <th>Rekap</th>
                                    <th>Rekap Harian</th>
                                    <th>Rekap Bulanan</th>
                                    <th>Jumlah Data</th>
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
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'rekap-form/datatables';
    var rekap_jawaban_harian_url = role_url + '#' + modul_url + '/' + 'rekap-form/detail';
    var rekap_jawaban_bulanan_url = role_url + '#' + modul_url + '/' + 'rekap-form/rekap-bulanan-form-harian';
    // var edit_url = role_url + '#' + modul_url + '/' + 'list-form/edit';
    // var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'list-form/action-list-form/delete';

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
                data: 'nm_form',
                name: 'nm_form'
            },
            {
                data: 'role.nm_role',
                name: 'role.nm_role',
                className: 'align-center',
            },
            {
                data: 'is_harian',
                name: 'is_harian',
                className: 'align-center',
                searchable: false,
                orderable: false,
            },
            {
                data: 'is_aktif',
                name: 'is_aktif',
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
                    if (!data.is_harian) {
                        return '<a type="button" class="btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                            rekap_jawaban_harian_url + '/' + data.id + '/0">' +
                            '    <i class="material-icons">remove_red_eye</i>' +
                            '</a>';
                    } else {
                        return '';
                    }
                }
            },
            {
                data: 'action',
                searchable: false,
                orderable: false,
                className: 'align-center',
                render: function(data) {
                    if (data.is_harian) {
                        return '<a type="button" class="btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                            rekap_jawaban_harian_url + '/' + data.id + '">' +
                            '    <i class="material-icons">remove_red_eye</i>' +
                            '</a>';
                    } else {
                        return '';
                    }
                }
            },
            {
                data: 'action',
                searchable: false,
                orderable: false,
                className: 'align-center',
                render: function(data) {
                    if (data.is_harian) {
                        return '<a type="button" class="btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                            rekap_jawaban_bulanan_url + '/' + data.id + '">' +
                            '    <i class="material-icons">remove_red_eye</i>' +
                            '</a>';
                    } else {
                        return '';
                    }
                }
            },
            {
                data: 'jumlah_jawaban',
                name: 'jumlah_jawaban',
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
