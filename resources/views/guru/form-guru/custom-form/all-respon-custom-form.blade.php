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
                                    <th>Terakhir Di Perbaharui</th>
                                    <th>Action</th>
                                    <th>Delete</th>
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
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'data/all-datatables/' + {!!json_encode($form->id_custom_form) !!};
    var delete_url = role_url + '#' + modul_url + '/' + 'form';
    var history_url = role_url + '#' + modul_url + '/' + 'form';

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
                data: 'last_update',
                name: 'last_update'
            },

            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                className: 'align-center',

                render: function(data) {
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float text-center" href="' +
                        history_url + '/' + data.id + '">' +
                        '    <i class="material-icons">edit</i>' +
                        '</a> '
                }
            },
            {
                data: 'history',
                name: 'action',
                searchable: false,
                orderable: false,
                className: 'align-center',

                render: function(data) {
                    return '<button class="target-link btn btn-danger btn-circle waves-effect waves-circle waves-float text-center" onclick="deleteData(\'' + data.id + '\')" href="' +
                        delete_url + '/' + data.id + '">' +
                        '    <i class="material-icons">delete</i>' +
                        '</button> '
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

    function deleteData(id_form) {
        var del = "{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . 'form/') }}"

        vex.dialog.confirm({
            message: 'Hapus Jawaban?',
            callback: function(value) {
                if (value) {
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    var idValue = 0;

                    $.ajax({
                        type: 'DELETE',
                        url: del + '/' + id_form,
                        data: {
                            _token: csrfToken
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                vex.dialog.alert(response.message);
                            } else if (response.status == 201) {
                                vex.dialog.alert(response.message);
                                window.location.href = response.link;
                            } else if (response.status == 202) {
                                vex.dialog.alert(response.message);
                                setTimeout(() => {
                                    loadURI(response.path);
                                }, 2000);
                            } else if (response.status == 203) {
                                vex.dialog.alert(response.message);
                                primary_table.ajax.reload(null, false);
                            } else if (response.status == 204) {
                                loadURI(response.path);
                            } else if (response.status == 300) {
                                vex.dialog.alert(response.message);
                            }
                        },
                        complete: function() {
                            primary_table.ajax.reload()
                        },
                        error: function(error) {}
                    });
                } else {

                }
            }
        })

    }
</script>