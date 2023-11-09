<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Data Siswa
                    </h2>
                </div>
                <div class="body">
                    <div class="body">
                        <div class="table-responsive">
                            <table
                                class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                                id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No. </th>
                                        <th>Nama</th>
                                        <th>Kelas</th>
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
    @include('scriptjs')

    <script>
        $(document).ready(function() {
            var modul_url = 'wali-kelas';
            var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' +
                'input-biodata-siswa/datatables';
            var detail_url = role_url + '#' + modul_url + '/input-biodata-siswa/edit';

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
                        data: 'pengguna.nm_pengguna',
                        name: 'pengguna.nm_pengguna'
                    },
                    {
                        data: 'kelas.nm_kelas',
                        name: 'kelas.nm_kelas'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false,
                        orderable: false,
                        render: function(data) {
                            return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float text-center" href="' +
                                detail_url + '/' + data.id + '">' +
                                '    <i class="material-icons">edit</i>' +
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


        });

        function resetPasswordSiswa(id, element) {

            var item = $(element);
            var id = item.attr('data-id');
            swal({
                    title: 'Apakah Yakin Untuk Reset Password?',
                    showCancelButton: true
                },
                function(isConfirm) {
                    if (isConfirm) {
                        $('#btn-reset-password').attr("disabled", true);
                        $.ajax({
                            url: base_url +
                                '/{{ Request::segment(1) }}/{{ Request::segment(2) }}/{{ Request::segment(3) }}/reset-password',
                            type: 'POST',
                            data: {
                                id_pengguna: id,
                            },
                            success: function(response) {
                                if (response.status_code == 200) {
                                    vex.dialog.alert(response.message);
                                } else if (response.status_code == 201) {
                                    vex.dialog.alert(response.message);
                                    window.location.href = response.link;
                                } else if (response.status_code == 202) {
                                    vex.dialog.alert(response.message);
                                    loadURI(response.path);
                                } else if (response.status_code == 203) {
                                    vex.dialog.alert(response.message);
                                    primary_table.ajax.reload(null, false);
                                } else if (response.status_code == 204) {
                                    loadURI(response.path);
                                } else if (response.status_code == 300) {
                                    vex.dialog.alert(response.message);
                                }
                            },
                            complete: function() {
                                $('#btn-reset-password').removeAttr('disabled', 'disabled');
                            },
                        });
                    }
                    return;
                }
            );
        }
    </script>
