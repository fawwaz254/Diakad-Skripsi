<style>
    .block-header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>
<div class="container-fluid">
    <div class="block-header-container">
        <div class="block-header">
            <h2><a class="btn bg-blue waves-effect target-link"
                    href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3)) }}"><i
                        class="material-icons">backspace</i><span>Kembali</span></a></h2>
        </div>
    </div>

    {{-- BODY --}}
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        TAMBAH AKTIFITAS REWARD SISWA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-upload" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/update-aktivitas-reward-siswa') }}">
                        @csrf
                        <input type="hidden" id="id_aktivitas_reward_siswa" name="id_aktivitas_reward_siswa">

                        <h2 class="card-inside-title">
                            Jenis Aktivitas Reward Siswa
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
                        </div>
                        <h2 class="card-inside-title">
                            Penilai
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="checkbox" id="guru" name="guru" value="1">
                                <label for="guru">Guru</label>
                                <br>
                                <input type="checkbox" id="sekretaris" name="sekretaris" value="1">
                                <label for="sekretaris">Sekretaris Kelas</label>
                            </div>
                        </div>
                        <input type="hidden" id="is_guru" name="is_guru">
                        <input type="hidden" id="is_sekretaris" name="is_sekretaris">
                        <h2 class="card-inside-title">
                            Nama Aktivitas Reward Siswa
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_aktivitas_reward_siswa"
                                    id="nm_aktivitas_reward_siswa" required="" aria-required="true"
                                    aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nilai Aktivitas
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="nilai_aktivitas" id="nilai_aktivitas"
                                    required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Karakter yang ditanamkan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="checkbox" id="disiplin" name="nilai_karakter" value="Disiplin">
                                <label for="disiplin">Disiplin</label>
                                <br>
                                <input type="checkbox" id="religius" name="nilai_karakter" value="Religius">
                                <label for="religius">Religius</label>
                                <br>
                                <input type="checkbox" id="tangguh" name="nilai_karakter"
                                    value="Tangguh dan Tanggung Jawab">
                                <label for="tangguh">Tangguh dan Tanggung Jawab</label>
                                <br>
                                <input type="checkbox" id="peduli" name="nilai_karakter" value="Peduli">
                                <label for="peduli">Peduli</label>
                                <br>
                                <input type="checkbox" id="komunikasi" name="nilai_karakter" value="Komunikasi">
                                <label for="komunikasi">Komunikasi</label>
                                <br>
                                <input type="checkbox" id="kolaborasi" name="nilai_karakter" value="Kolaborasi">
                                <label for="kolaborasi">Kolaborasi</label>
                                <br>
                                <input type="checkbox" id="kritis" name="nilai_karakter"
                                    value="Kritis dan Pemecahan Masalah">
                                <label for="kritis">Kritis dan Pemecahan Masalah</label>
                                <br>
                                <input type="checkbox" id="kreatif" name="nilai_karakter"
                                    value="Kreatif dan Inovatif">
                                <label for="kreatif">Kreatif dan Inovatif</label>
                                <br>
                                <input type="checkbox" id="jujur" name="nilai_karakter" value="Kejujuran">
                                <label for="jujur">Kejujuran</label>
                            </div>
                        </div>
                        <input type="hidden" id="nilai_karakter_checked" name="nilai_karakter" required=""
                            aria-required="true" aria-invalid="true">
                        <h2 class="card-inside-title">
                            Status
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control" id="status" name="status" required=""
                                    aria-required="true" aria-invalid="true">
                                    <option selected disabled>Pilih Status</option>
                                    <option value="1">Aktif</option>
                                    <option value="0">Non Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                        class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('scriptjs')

<script>
    $('#form-upload').submit(function(e) {
        e.preventDefault();
    }).validate({
        highlight: function(input) {
            $(input).addClass('is-danger');
        },
        unhighlight: function(input) {
            $(input).removeClass('is-danger');
        },
        errorPlacement: function(error, element) {
            $(element).parents('.control').addClass('help').addClass('is-danger').append(error);
        },
        submitHandler: function(form) {
            // handle input penilai
            if ($('#guru').is(':checked')) {
                $('#is_guru').val(1);
            } else {
                $('#is_guru').val(0);
            }

            if ($('#sekretaris').is(':checked')) {
                $('#is_sekretaris').val(1);
            } else {
                $('#is_sekretaris').val(0);
            }

            // handle input nilai karakter
            var karakter = [];

            $('input[name="nilai_karakter"]:checked').each(function() {
                karakter.push($(this).val());
            });

            $('#nilai_karakter_checked').val(karakter.join('#'));

            // Disable button and send the form via AJAX
            $('button').attr('disabled', 'disabled');

            setTimeout(() => {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    enctype: 'multipart/form-data',
                    data: new FormData($('#form-upload')[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status == 200) {
                            vex.dialog.alert(response.message);
                            console.log("Redirecting to:", response.path);
                            loadURI(response.path);
                        } else if (response.status == 201) {
                            vex.dialog.alert(response.message);
                            window.location.href = response.link;
                        } else if (response.status == 202) {
                            vex.dialog.alert(response.message);
                            console.log("Redirecting to:", response.path);
                            loadURI(response.path);
                        } else if (response.status == 203) {
                            vex.dialog.alert(response.message);
                            primary_table.ajax.reload(null, false);
                        } else if (response.status == 204) {
                            loadURI(response.path);
                        } else if (response.status == 300) {
                            vex.dialog.alert(response.message);
                        }
                        console.log('Server response:', response);
                    },
                    complete: function() {
                        $('button').removeAttr('disabled');
                    }
                });

            }, 1000);
        }
    });


    function setData() {
        let id_jenis_aktivitas_reward = '{{ $aktifitas_reward->id_jenis_aktivitas_reward }}'
        let is_guru = '{{ $aktifitas_reward->is_guru }}'
        let is_sekretaris = '{{ $aktifitas_reward->is_sekretaris }}'
        let nm_aktivitas_reward_siswa = '{{ $aktifitas_reward->nm_aktivitas_reward_siswa }}'
        let nilai_aktivitas = '{{ $aktifitas_reward->nilai_aktivitas }}'
        let dataKarakter = JSON.parse(@json($dataKarakter));
        let status = '{{ $aktifitas_reward->is_aktif }}'
        let id_aktivitas_reward_siswa = '{{ $aktifitas_reward->id_aktivitas_reward_siswa }}'

        $('#jenis_aktivitas_reward').val(id_jenis_aktivitas_reward)
        if (is_guru == 1) {
            $('#guru').prop('checked', true)
        }
        if (is_sekretaris == 1) {
            $('#sekretaris').prop('checked', true)
        }
        $('#nm_aktivitas_reward_siswa').val(nm_aktivitas_reward_siswa)
        $('#nilai_aktivitas').val(nilai_aktivitas)
        $('#status').val(status)
        $('#id_aktivitas_reward_siswa').val(id_aktivitas_reward_siswa)

        dataKarakter.forEach(function(item) {
            if (item == 'Disiplin') {
                $('#disiplin').prop('checked', true)
            }
            if (item == 'Religius') {
                $('#religius').prop('checked', true)
            }
            if (item == 'Tangguh dan Tanggung Jawab') {
                $('#tangguh').prop('checked', true)
            }
            if (item == 'Peduli') {
                $('#peduli').prop('checked', true)
            }
            if (item == 'Komunikasi') {
                $('#komunikasi').prop('checked', true)
            }
            if (item == 'Kolaborasi') {
                $('#kolaborasi').prop('checked', true)
            }
            if (item == 'Kritis dan Pemecahan Masalah') {
                $('#kritis').prop('checked', true)
            }
            if (item == 'Kreatif dan Inovatif') {
                $('#kreatif').prop('checked', true)
            }
            if (item == 'Kejujuran') {
                $('#jujur').prop('checked', true)
            }
        })
    }

    $(document).ready(function() {
        setData()
    });
</script>
