<div class="container"
    style="margin-bottom: 10px; background-color: white; color: black; padding: 20px; border-radius: 5px;">
    <h2 class="mb-4">Input Calon Siswa</h2>

    <form id="form-calon-siswa" method="POST">
        @csrf
        <div class="mb-3" style="margin-bottom: 10px;">
            <label for="nm_c_siswa" class="form-label">Nama</label>
            <input type="text" class="form-control" placeholder="John Doe" id="nm_c_siswa" name="nm_c_siswa" required>
        </div>
        <div class="mb-3" style="margin-bottom: 10px;">
            <label for="nik_siswa" class="form-label">NIK</label>
            <input type="text" class="form-control" placeholder="3515************" id="nik_siswa" name="nik_siswa"
                required>
        </div>
        <div class="mb-3" style="margin-bottom: 10px;">
            <label class="form-label">Jenis Kelamin</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="jenis_kelamin" id="laki-laki" value="1">
                <label class="form-check-label" for="laki-laki">
                    Laki-laki
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="jenis_kelamin" id="perempuan" value="2">
                <label class="form-check-label" for="perempuan">
                    Perempuan
                </label>
            </div>
        </div>
        <div class="mb-3" style="margin-bottom: 10px;">
            <label for="asal_sekolah" class="form-label">Asal Sekolah</label>
            <input type="text" placeholder="Contoh: SMPN 2 Sukodono" class="form-control" id="asal_sekolah"
                name="asal_sekolah" required>
        </div>
        <div class="mb-3" style="margin-bottom: 20px;">
            <label for="nomor_hp" class="form-label">No Telp Ortu</label>
            <input type="tel" class="form-control" placeholder="08**********" id="nomor_hp" name="nomor_hp"
                required>
        </div>
        <h4>Alamat</h4>
        <div class="row" style="margin-bottom: 15px;">
            <div class="mb-3 col-md-4">
                <label for="provinsi" class="form-label">Provinsi</label>
                <select class="form-control" id="provinsi" name="alamat_provinsi" required>
                    <option selected>Pilih provinsi</option>
                    @foreach ($data_provinsi as $prov)
                        <option value="{{ $prov->id_provinsi }}">{{ $prov->nm_provinsi }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3 col-md-4">
                <label for="kota" class="form-label">Kota/Kabupaten</label>
                <select class="form-control" id="kota" name="alamat_kota" disabled>
                    <option selected>Pilih Kota/Kabupaten</option>
                </select>
            </div>
            <div class="mb-3 col-md-4" style="margin-bottom: 10px;">
                <label for="alamat_kecamatan" class="form-label">Kecamatan</label>
                <input type="text" class="form-control" id="alamat_kecamatan" placeholder="Contoh: Sukodono"
                    name="alamat_kecamatan" required>
            </div>
            <div class="mb-3 col-md-4" style="margin-bottom: 10px;">
                <label for="alamat_kelurahan" class="form-label">Kelurahan</label>
                <input type="text" class="form-control" id="alamat_kelurahan" placeholder="Contoh: Pekarungan"
                    name="alamat_kelurahan" required>
            </div>
            <div class="mb-3 col-md-4" style="margin-bottom: 10px;">
                <label for="alamat_dusun" class="form-label">Dusun</label>
                <input type="text" placeholder="Contoh: Karangnongko" class="form-control" id="alamat_dusun"
                    name="alamat_dusun" required>
            </div>

            <div class="mb-3 col-md-4" style="margin-bottom: 10px;">
                <label for="alamat_rt" class="form-label">RT</label>
                <input type="text" placeholder="Contoh: 02" class="form-control" id="alamat_rt" name="alamat_rt"
                    required>
            </div>
            <div class="mb-3 col-md-4" style="margin-bottom: 10px;">
                <label for="alamat_rw" class="form-label">RW</label>
                <input type="text" placeholder="Contoh: 02" class="form-control" id="alamat_rw" name="alamat_rw"
                    required>
            </div>
            <div class="mb-3 col-md-4" style="margin-bottom: 10px;">
                <label for="alamat_jalan" class="form-label">Jalan</label>
                <input type="text" class="form-control" placeholder="Contoh : Jl. Gubuk" id="alamat_jalan"
                    name="alamat_jalan" required>
            </div>
            <div class="mb-3 col-md-4" style="margin-bottom: 10px;">
                <label for="alamat_kodepos" class="form-label">Kode Pos</label>
                <input type="text" class="form-control" placeholder="Contoh: 61258" id="alamat_kodepos"
                    name="alamat_kodepos" required>
            </div>
        </div>

        <h4>Pilihan Jurusan</h4>
        <div class="row" style="margin-bottom: 20px;">
            <div class="mb-3 col-md-4">
                <label for="jurusan_pilihan" class="form-label">Jurusan Pilihan 1</label>
                <select class="form-control" name="id_pilihan_jurusan_1" required>
                    <option selected>Pilih pilihan jurusan 1</option>
                    @foreach ($data_jurusan as $jurusan)
                        <option value="{{ $jurusan->id_jurusan }}">{{ $jurusan->nm_jurusan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3 col-md-4">
                <label for="jurusan_pilihan" class="form-label">Jurusan Pilihan 2</label>
                <select class="form-control" required name="id_pilihan_jurusan_2">
                    <option selected>Pilih pilihan jurusan 2</option>
                    @foreach ($data_jurusan as $jurusan)
                        <option value="{{ $jurusan->id_jurusan }}">{{ $jurusan->nm_jurusan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3 col-md-4">
                <label for="jurusan_pilihan" class="form-label">Jurusan Pilihan 3</label>
                <select class="form-control" required name="id_pilihan_jurusan_3">
                    <option selected>Pilih pilihan jurusan 3</option>
                    @foreach ($data_jurusan as $jurusan)
                        <option value="{{ $jurusan->id_jurusan }}">{{ $jurusan->nm_jurusan }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div id="alert-message" style="display: none; padding: 10px; border-radius: 5px; margin-bottom: 10px;"></div>
        <div style="margin-bottom: 100px;">
            <button type="submit" class="btn btn-primary" style="margin-right: 2px">Submit</button>
            <a class="btn btn-secondary"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3) . '/' . $id) }}">Batal</a>
        </div>
    </form>
</div>


<script>
    function onlyNumber(id) {
        document.getElementById(id).addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
        })
    }

    $(document).ready(function() {

        onlyNumber("alamat_kodepos");
        onlyNumber("alamat_rt");
        onlyNumber("alamat_rw");
        onlyNumber("nik_siswa");
        onlyNumber("nomor_hp");

        $('#provinsi').change(function() {
            var provinsi_id = $(this).val();



            if (provinsi_id) {
                $('#kota').prop('disabled', false);
                $.ajax({
                    url: "{{ url('/ppdb/peserta/proses-penetapan/input-calon-siswa/get-kota') }}/" +
                        provinsi_id, // Sesuaikan dengan route backend
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#kota').empty().append(
                            '<option selected>Pilih Kota/Kabupaten</option>');
                        $.each(data, function(key, value) {
                            $('#kota').append('<option value="' + value.id_kota +
                                '">' + value.nm_kota + '</option>');
                        });
                    }
                });
            } else {
                $('#kota').empty().append('<option selected>Pilih Kota/Kabupaten</option>');
            }
        });

        $('#form-calon-siswa').submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('proses-penetapan.input-calon-siswa.create', $id) }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    $('#alert-message').css('background-color', '#d4edda').css('color',
                            '#155724')
                        .css('border', '1px solid #c3e6cb').html(
                            "<strong>Sukses!</strong> " + response.message).fadeIn();

                    $('#form-calon-siswa')[0].reset();
                    setTimeout(function() {
                        window.location.href =
                            "{{ url('ppdb#peserta/proses-penetapan/' . $id) }}";
                    }, 1000);
                },
                error: function(xhr) {
                    let errorMessage = xhr.responseJSON.message || "Terjadi kesalahan!";
                    $('#alert-message').css('background-color', '#f8d7da').css('color',
                            '#721c24')
                        .css('border', '1px solid #f5c6cb').html(
                            "<strong>Error!</strong> " + errorMessage).fadeIn();
                }
            });
        });
    });
</script>
