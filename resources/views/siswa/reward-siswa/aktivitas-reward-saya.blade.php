<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="header">
                <h2>Aktivitas Reward Saya</h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table
                        class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                        id="table_aktivitas_siswa">
                        <thead>
                            <tr>
                                <th style="vertical-align : middle;text-align:center;">Jenis Aktivitas</th>
                                <th style="vertical-align : middle;text-align:center;">Nama Aktivitas</th>
                                <th style="vertical-align : middle;text-align:center;">Nilai Karakter</th>
                                <th style="vertical-align : middle;text-align:center;">Tanggal Pengisian</th>
                                <th style="vertical-align : middle;text-align:center;">Aproval</th>
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
    let datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'aktivitas-reward-saya/datatable';
    // let delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'aktivitas-reward-siswa/delete';
    // let edit_url = base_url + '/' + role_url + '#' + modul_url + '/' + 'aktivitas-reward-siswa/edit';

    function loadDataAktivitasRewardSaya() {
        $('#table_aktivitas_siswa').DataTable({
            processing: true,
            serverside: true,
            ajax: datatable_url,
            columns: [
                {
                    data: 'nm_jenis_aktivitas_reward',
                    name: 'nm_jenis_aktivitas_reward'
                },
                {
                    data: 'nm_aktivitas_reward_siswa',
                    name: 'nm_aktivitas_reward_siswa'
                },
                {
                    data: 'nm_reward_siswa',
                    name: 'nm_reward_siswa'
                },
                {
                    data: 'tanggal_pengisian',
                    name: 'tanggal_pengisian'
                },
                {
                    data: 'approval',
                    name: 'approval'
                },
            ],
        });
    }

    $(document).ready(function() {
        loadDataAktivitasRewardSaya()
    });
</script>
