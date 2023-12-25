<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Data Wali Murid
                    </h2>
                </div>
                <form action="" id="form1" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="body">
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No. </th>
                                        <th><input id="checkbox_select_all" type="checkbox" name="select_all" class="filled-in">
                                            <label for="checkbox_select_all" style="margin-bottom: -10px;"></label>
                                        </th>
                                        <th>Username</th>
                                        <th>Nama Wali Murid</th>
                                        <th>Siswa</th>
                                        <th>Kelas</th>
                                        <th>Last Time Login</th>
                                        <th>Last Time Reset Password</th>
                                        {{-- <th>Multi Role</th> --}}
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                            <button class="m-2 btn bg-blue waves-effect hidden" type="submit" id="deleteAll" method="POST"><i class="material-icons">update</i><span> Reset Password</button>
                        </div>
                    </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    @include('scriptjs')

    <script>
        $(document).ready(function() {
            var modul_url = 'wali-kelas';
            var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'wali-murid/datatables';
            // var reset_password_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-pencarian/delete';

            // alert(datatable_url);

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
                        data: 'checkbox',
                        name: 'checkbox',
                        searchable: false,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return '<input id="checkbox-' + data.id +
                                '" type="checkbox" name="id_pengguna[]" class="filled-in" value="' + data.id +
                                '">' +
                                '<label for="checkbox-' + data.id + '"></label>';

                        }
                    },
                    {
                        data: 'wali_murid.nomor_hp_wali_murid',
                        name: 'wali_murid.nomor_hp_wali_murid'
                    },
                    {
                        data: 'nm_wali_murid',
                        name: 'nm_wali_murid'
                    },
                    {
                        data: 'pengguna.nm_pengguna',
                        name: 'pengguna._nm_pengguna'
                    },
                    {
                        data: 'kelas.nm_kelas',
                        name: 'kelas.nm_kelas'
                    },
                    {
                        data: 'time_last_login',
                        name: 'time_last_login'
                    },
                    {
                        data: 'time_reset_password',
                        name: 'time_reset_password'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false,
                        orderable: false,
                        render: function(data) {
                            return '<button class="btn  bg-blue waves-effect" id="btn-reset-password" onclick="resetPasswordSiswa(\'' +
                                base_url + '\', this)" data-id="' +
                                data.id + '">' +
                                '<i class="material-icons">update</i><span> Reset Password' +
                                '</button>' +
                                '<button class="btn  bg-red waves-effect" id="btn-reset-password" style="margin-left:10px" onclick="hapusWaliMurid(\'' +
                                base_url + '\', this)" data-id="' +
                                data.id + '">' +
                                '<i class="material-icons">delete</i><span> Hapus Wali Murid' +
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

            $('input[name="select_all"]').change(function() {
                var select_all_checked = this.checked;
                var rows = primary_table.rows({
                    'search': 'applied'
                }).nodes();

                $('input[type="checkbox"]', rows).prop('checked', this.checked);

            });

            $('#primary_table').on('change', 'input[type="checkbox"]', function() {
                var anyCheckboxChecked = $('#primary_table').DataTable().$('input[type="checkbox"]:checked').length > 0;
                $('#deleteAll').toggleClass('hidden', !anyCheckboxChecked);
            });


        });

        $('#form1').validate({
            rules:{
                'checkbox': {
                    required : true
                }
            },
            submitHandler: function(form){
                swal({
                    title: 'Apakah Yakin Untuk Reset Seluruh Password?',
                    showCancelButton: true
                },
                function(isConfirm) {
                    if (isConfirm) {
                        $('#deleteAll').attr("disabled", true);
                        $.ajax({
                            url: base_url +
                                '/guru/wali-kelas/wali-murid/reset-password-multiple',
                            type: 'POST',
                            data: {
                                data: $('#form1 :checkbox').serializeArray(),
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
                                $('#deleteAll').removeAttr('disabled', 'disabled');
                                $('#checkbox_select_all').attr('checked',false)
                                $('#primary_table').DataTable().ajax.reload()
                            },
                        });
                    }
                    return;
                }
            )
            }
        })

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


        function hapusWaliMurid(id, element) {

            var item = $(element);
            var id = item.attr('data-id');
            swal({
                    title: 'Apakah Yakin Untuk Menghapus Wali Murid?',
                    showCancelButton: true
                },
                function(isConfirm) {
                    if (isConfirm) {
                        $('#btn-reset-password').attr("disabled", true);
                        $.ajax({
                            url: base_url +
                                '/{{ Request::segment(1) }}/{{ Request::segment(2) }}/{{ Request::segment(3) }}/hapus-wali-murid',
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
