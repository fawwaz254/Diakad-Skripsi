<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="header">
                <h2>Rekap Reward Siswa</h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table
                        class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                        id="table_rekap_reward">
                        <thead>
                            <tr>
                                <th style="vertical-align : middle;text-align:center;">No</th>
                                <th style="vertical-align : middle;text-align:center;">Nama Siswa</th>
                                <th style="vertical-align : middle;text-align:center;">Kelas</th>
                                <th style="vertical-align : middle;text-align:center;">Disiplin</th>
                                <th style="vertical-align : middle;text-align:center;">Religius</th>
                                <th style="vertical-align : middle;text-align:center;">Tangguh dan Tanggung Jawab</th>
                                <th style="vertical-align : middle;text-align:center;">Peduli</th>
                                <th style="vertical-align : middle;text-align:center;">Komunikasi</th>
                                <th style="vertical-align : middle;text-align:center;">Kritis dan Pemecahan Masalah</th>
                                <th style="vertical-align : middle;text-align:center;">Kreatif dan Inovatif</th>
                                <th style="vertical-align : middle;text-align:center;">Kejujuran</th>
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

<script>
    let modul_url = 'reward-siswa';
    let datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + '';
    // let delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'aktivitas-reward-siswa/delete';
    // let edit_url = base_url + '/' + role_url + '#' + modul_url + '/' + 'aktivitas-reward-siswa/edit';

    function loadDataRekap() {
        $('#table_rekap_reward').DataTable({
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
            ],
            columnDefs: [{
                targets: 0,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            }]
        });
    }

    $(document).ready(function() {
        loadDataRekap()
    });
</script>
