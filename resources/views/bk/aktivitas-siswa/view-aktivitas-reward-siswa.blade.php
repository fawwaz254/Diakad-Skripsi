<div class="container-fluid">
    <div class="block-header">
        <h2>
            <a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3) . '/add') }}"><i
                    class="material-icons">add</i><span>Tambah Aktivitas Reward Siswa</span></a>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Data Aktivitas Reward Siswa</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="list_data_aktivitas_reward_siswa">
                            <thead>
                                <tr>
                                    <th style="vertical-align : middle;text-align:center;">No</th>
                                    <th style="vertical-align : middle;text-align:center;">Nama Aktivitas Reward</th>
                                    <th style="vertical-align : middle;text-align:center;">Jenis Aktivitas</th>
                                    <th style="vertical-align : middle;text-align:center;">Nilai Aktivitas</th>
                                    <th style="vertical-align : middle;text-align:center;">Status</th>
                                    {{-- <th style="vertical-align : middle;text-align:center;">Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let modul_url = 'aktivitas-siswa';
    let datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'aktivitas-reward-siswa/datatables';
    let delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'aktivitas-reward-siswa/delete';
    let edit_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'aktivitas-reward-siswa/edit';

    $(document).ready(function() {
        let data = $('#list_data_aktivitas_reward_siswa').DataTable({
            processing: true,
            serverside: true,
            ajax: datatable_url,
            columns: [{
                    data: null,
                    name: 'no',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'nm_aktivitas_reward_siswa',
                    name: 'nm_aktivitas_reward_siswa'
                },
                {
                    data: 'jenis_aktivitas',
                    name: 'jenis_aktivitas'
                },
                {
                    data: 'nilai_aktivitas',
                    name: 'nilai_aktivitas'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                // {
                //     data: null,
                //     name: 'action',
                //     orderable: false,
                //     searchable: false,
                //     render: function(data, type, row) {
                //         let editButton = '<a href="' + edit_url + '/' + row
                //             .id_aktivitas_reward_siswa +
                //             '" class="edit btn btn-primary btn-sm">Edit</a>';
                //         let deleteButton = '<a href="' + delete_url + '/' + row
                //             .id_aktivitas_reward_siswa +
                //             '" class="delete btn btn-danger btn-sm">Delete</a>';
                //         return editButton + ' ' + deleteButton;

                //     }
                // }
            ],
            columnDefs: [{
                targets: 0,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            }]
        });

        // $(document).on('click', '.delete', function() {
        //     let id = $(this).data('id');
        //     let url = delete_url + '/' + id;

        //     if (confirm('Yakin data ini akan dihapus?')) {
        //         $.ajax({
        //             url: url,
        //             type: 'POST',
        //             data: {
        //                 _method: 'DELETE',
        //                 _token: csrf_token
        //             },
        //             success: function(response) {
        //                 if (response.success) {
        //                     $('#list_data_aktivitas_reward_siswa').DataTable().ajax.reload();
        //                     alert('Data berhasil dihapus.');
        //                 } else {
        //                     alert('Error: ' + response.message);
        //                 }
        //             },
        //             error: function(xhr) {
        //                 alert('Error: ' + xhr.responseText);
        //             }
        //         });
        //     }
        // });
    });
</script>
