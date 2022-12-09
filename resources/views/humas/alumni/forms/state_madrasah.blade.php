<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h2 class="h4"> Data Siswa Tidak Bekerja / Kuliah </h2>
    <h2 class="card-inside-title"> Kegiatan Apa Yang Sedang Dilakukan </h2>
    <input class="with-gap radio-col-light-green form-control validate" type="radio" name="status_menunggu"
        {{ !empty($alumni->menunggu) && $alumni->menunggu->status_menunggu == 'mencari_kerja' ? 'checked' : '' }}
        value="mencari_kerja" id="mencari_kerja" required="required" data-error="Error msg here">
    <label for="mencari_kerja"> Mencari Kerja </label>
    <input class="with-gap radio-col-light-green form-control validate" type="radio" name="status_menunggu"
        {{ !empty($alumni->menunggu) && $alumni->menunggu->status_menunggu == 'mencari_univ' ? 'checked' : '' }}
        value="mencari_univ" id="mencari_univ" required="required" data-error="Error msg here">
    <label for="mencari_univ"> Mempersiapkan Diri Masuk Perguruan Tinggi </label>
</div>
