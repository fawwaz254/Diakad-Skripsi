<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="header">
                <h2>Approve Reward Siswa</h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                        id="table_approve_reward_siswa">
                        <thead>
                            <tr>
                                <th style="vertical-align : middle;text-align:center;">No</th>
                                <th style="vertical-align : middle;text-align:center;">Kelas</th>
                                <th style="vertical-align : middle;text-align:center;">Nama</th>
                                <th style="vertical-align : middle;text-align:center;">Jenis Aktivitas Reward</th>
                                <th style="vertical-align : middle;text-align:center;">Aktivitas Reward</th>
                                <th style="vertical-align : middle;text-align:center;">Nama Karakter</th>
                                <th style="vertical-align : middle;text-align:center;">Tanggal Pengisian</th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let modul_url = 'reward-siswa';
    let datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'datatableApprovePestasi';
    console.log(datatable_url);

    $(document).ready(function() {
        // console.log('Document ready');
        loadData()
    });

    function loadData() {
        $('#table_approve_reward_siswa').DataTable({
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
                    data: 'Kelas',
                    name: 'Kelas'
                },
                {
                    data: 'Nama',
                    name: 'Nama'
                },
                {
                    data: 'Jenis_Aktivitas',
                    name: 'Jenis_Aktivitas'
                },
                {
                    data: 'Aktivitas_Reward',
                    name: 'Aktivitas_Reward'
                },
                {
                    data: 'nm_reward_siswa',
                    name: 'nm_reward_siswa'
                },
                {
                    data: 'tanggal_pengisian',
                    name: 'tanggal_pengisian'
                }
            ],
            columnDefs: [{
                targets: 0,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            }]
        });
    }
</script>
