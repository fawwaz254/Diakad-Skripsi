<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    {{ strtoupper($title) }}
                </h2>
            </div>
            <div class="body">
                <h4 class="title">Inputan {{$date_input}}</h4>
                @if(!$pengisian_kegiatan_harian)
                <form id="form_input_aktivitas">
                    @csrf
                    <div class="row clearfix">
                        {{-- FORM PERTANYAAN --}}
                        <input id="jenis_aktivitas_reward" type="hidden" name="jenis_aktivitas_reward" value="{{ $jenis }}"  />
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="form_aktivitas" style="display:none">
                            <h2 class="card-inside-title">Anda mengikuti/memenuhi aktivitas berikut</h2>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Aktivitas</th>
                                        <th>Ceklis</th>
                                    </tr>
                                </thead>
                                <tbody id="pertanyaan_aktivitas">
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-primary">Simpan Jawaban</button>
                        </div>
                    </div>
                </form>
            </div>
            @else
            <h4>
                Kamu sudah melakukan pengisian pada {{ $pengisian_kegiatan_harian->created_at->isoFormat('DD MMMM Y HH:mm') }}
            </h4>
            @endif
        </div>
    </div>
</div>
</div>

<script>
    let modul_url = "reward-siswa";
    let ajaxGetAktivitasById_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'input-aktivitas-reward/get-aktivitas-by-jenis';
    let save_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'input-aktivitas-reward/save-input-aktivitas-reward';

    $(document).ready(function() {
        let id_jenis_aktivitas_reward = $("#jenis_aktivitas_reward").val();

        $.ajax({
            url: ajaxGetAktivitasById_url,
            method: 'POST',
            data: {
                id_jenis_aktivitas_reward: id_jenis_aktivitas_reward,
            },
            success: function(response) {
                $('#pertanyaan_aktivitas').html(response.html); // Isi pertanyaan
                $('#form_aktivitas').show(); // Tampilkan form

                if(response.jumlah_pertanyaan == 0){
                    $('#form_aktivitas button').hide();
                }
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
</script>
