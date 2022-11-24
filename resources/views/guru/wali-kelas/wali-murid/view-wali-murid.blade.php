<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        CARI DATA PENGGUNA
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
                                        <th>Username</th>
                                        <th>Nama Wali Murid</th>
                                        <th>Siswa</th>
                                        <th>Kelas</th>
                                        {{-- <th>Multi Role</th> --}}
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
        var modul_url = 'wali-kelas';
        var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'wali-murid/datatables';
        var detail_url = role_url + '#' + modul_url + '/' + 'pencarian/view-detail-pengguna';

        // alert(datatable_url);
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
                    orderable: false
                },
                {
                    data: 'username',
                    name: 'username'
                },
                {
                    data: 'nm_pengguna',
                    name: 'nm_pengguna'
                },
                {
                    data: 'status_join_table',
                    name: 'status_join_table'
                },
                {
                    data: 'nm_role',
                    name: 'nm_role'
                },
                {
                    data: 'action',
                    name: 'action',
                    searchable: false,
                    orderable: false,
                    render: function(data) {
                        return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                            detail_url + '/' + data.id + '/' + data.id_cari + '">' +
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
