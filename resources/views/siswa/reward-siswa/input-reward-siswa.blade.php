<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    INPUT AKTIVITAS REWARD SISWA
                </h2>
            </div>
            <div class="body">
                <form id="form_input_aktivitas">
                    @csrf
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Jenis Aktivitas Reward Siswa
                            </h2>
                            <select class="form-control" id="jenis_aktivitas_reward" name="jenis_aktivitas_reward">
                                <option value="" selected disabled>-- Pilih Jenis Aktivitas Reward Siswa --
                                </option>
                                @forelse ($data_jenis_aktivitas as $row)
                                    <option value="{{ $row->id_jenis_aktivitas_reward }}">
                                        {{ $row->nm_jenis_aktivitas_reward }}</option>
                                @empty
                                    <option value="">Tidak ada data</option>
                                @endforelse
                            </select>
                        </div>
                        {{-- FORM PERTANYAAN --}}
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="form_aktivitas" style="display:none">
                            <h2 class="card-inside-title">Isi Aktivitas Reward</h2>
                            <div id="pertanyaan_aktivitas"></div>
                            <button type="submit" class="btn btn-primary">Simpan Jawaban</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    let modul_url = "reward-siswa";
    let ajaxGetAktivitasById_url = base_url + '/' + role_url + '/' + modul_url + '/' +
        'input-aktivitas-reward/get-aktivitas-by-jenis';
    let save_url = base_url + '/' + role_url + '/' + modul_url + '/' +
        'input-aktivitas-reward/save-input-aktivitas-reward';

    $(document).ready(function() {
        $("#jenis_aktivitas_reward").change(function() {
            let id_jenis_aktivitas_reward = $("#jenis_aktivitas_reward").val();
            console.log(id_jenis_aktivitas_reward);

            $.ajax({
                url: ajaxGetAktivitasById_url,
                method: 'POST',
                data: {
                    id_jenis_aktivitas_reward: id_jenis_aktivitas_reward,
                },
                success: function(response) {
                    $('#pertanyaan_aktivitas').html(response); // Isi pertanyaan
                    $('#form_aktivitas').show(); // Tampilkan form
                },
                error: function(error) {
                    console.log(error);
                }
            });

            $('#form_input_aktivitas').on('submit', function(e) {
                e.preventDefault();

                let formData = $(this).serialize();
                let submitButton = $(this).find('button[type="submit"]');
                submitButton.prop('disabled', true).text('Saving...');

                $.ajax({
                    url: save_url,
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        alert('Jawaban berhasil disimpan!');
                        location.reload();
                    },
                    error: function(jqXHR) {
                        console.log('Error:', jqXHR);
                        let errorMessage = jqXHR.responseJSON?.error ||
                            'Gagal menyimpan jawaban !!';
                        alert(errorMessage);
                        submitButton.prop('disabled', false).text('Submit');
                    }
                });
            });
        });
    });
</script>
