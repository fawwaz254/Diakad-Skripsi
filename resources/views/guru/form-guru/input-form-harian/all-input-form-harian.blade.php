<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Data Input Form {{ $form->nm_form }}</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
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
    var add_url = role_url + '#' + modul_url + '/' + 'input-form-harian/edit';
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
                orderable: true,
            },
            {
                data: 'action',
                searchable: false,
                orderable: false,
                className: 'align-center',
                render: function(data) {
                    return '<a type="button" class="btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                        add_url + '/' + data.id + '">' +
                        '    <i class="material-icons">edit</i>' +
                        '</a>' + '&nbsp' + '<a type="button" class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteJawaban(\'{{ Request::segment(5) }}\', \'' + data.id + '\')">' +
                        '<i class="material-icons">delete</i>' +
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

    function deleteJawaban(form, jawaban) {
        vex.dialog.confirm({
            message: 'Hapus Jawaban?',
            callback: function(value) {
                if (value) {


                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    var idValue = 0;

                    $.ajax({
                        type: 'POST',
                        url: "{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/action-list-form/delete/0') }}",
                        data: {
                            _token: csrfToken,
                            id_form: form,
                            id_jawaban: jawaban,
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
                        complete: function (){
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