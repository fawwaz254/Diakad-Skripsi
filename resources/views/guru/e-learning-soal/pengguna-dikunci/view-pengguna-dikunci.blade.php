<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/hasil-test') }}">
                <span>Hasil Test</span>
            </a>
            <a class="btn bg-teal waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/pengguna-terkunci') }}">
                <span>UnLock Siswa</span>
            </a>
            <a class="btn bg-red waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/pengguna-dikunci') }}">
                <span>Lock Siswa</span>
            </a>
            <br />
            <br />
            <div class="card">
                <div class="header">
                    <h2>
                        List Pengguna dengan status *Lock*
                    </h2>

                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table id="primary_table" class="table table-bordered table-striped table-hover dataTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Lock & Logout</th>
                                    <th>NIS</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <button id="next_process" disabled class="btn bg-red waves-effect">
                        <span>Kunci pengguna yang dicentang</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->
</div>
<script>
    var modul_url = '{{ Request::segment(2) }}';
    var action_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'pengguna-dikunci';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        ajax: {
            url: action_url + '/table',
            type: 'POST'
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: null,
                searchable: false,
                orderable: false,
                render: function(data, type, row) {
                    return `<input id="checkbox-${row.id_pengguna}" type="checkbox" value="${row.id_pengguna}" class="el_check filled-in" onclick="elChangeCheck(this)">
                    <label for="checkbox-${row.id_pengguna}"></label>`;
                }
            },
            {
                data: 'username',
            },
            {
                data: 'nm_pengguna',
            },
            {
                data: 'siswa.kelas.nm_kelas',
            },
        ],
        order: [
            [4, 'desc']
        ]
    });

    primary_table.on('draw', function() {
        primary_table.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = i + 1;
        });
    }).draw();

    function elChangeCheck() {
        if ($('.el_check:checked').length > 0) {
            $('#next_process').removeAttr('disabled', 'disabled');
        } else {
            $('#next_process').attr('disabled', 'disabled');
        }
    }

    $('#next_process').click(function() {
        if ($('.el_check:checked').length == 0) {
            alert('Please checklist first');
            return false;
        }

        swal({
            title: 'Apakah anda yakin',
            text: 'Apakah anda yakin ingin melakukan update data sesuai pada kolom dan baris yang anda checklist',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }, function(result) {
            if (result) {
                var data_checked = [];
                $(".el_check:checked").each(function() {
                    data_checked.push($(this).val());
                });
                $.ajax({
                    url: action_url + '/lock',
                    type: 'POST',
                    data: {
                        data_id: data_checked,
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 200) {
                            vex.dialog.alert(response.message);
                        } else if (response.status == 201) {
                            vex.dialog.alert(response.message);
                            window.location.href = response.link;
                        } else if (response.status == 202) {
                            vex.dialog.alert(response.message);
                            loadURI(response.path);
                        } else if (response.status == 203) {
                            vex.dialog.alert(response.message);
                            primary_table.ajax.reload(null, false);
                        } else if (response.status == 204) {
                            loadURI(response.path);
                        } else if (response.status == 300) {
                            vex.dialog.alert(response.message);
                        }

                        $('#next_process').attr('disabled', 'disabled');
                    }
                });
            }
        });
    })
</script>
