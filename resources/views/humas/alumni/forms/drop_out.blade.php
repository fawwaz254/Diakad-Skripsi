<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h2 class="h4"> Data Instansi Tempat Bekerja </h2>
    <h2 class="card-inside-title"> Nama Instansi </h2>
    <input type="text" class="form-control" name="nm_instansi" required="" aria-required="true" aria-invalid="true"
        value="{{ !empty($alumni->bekerja) ? $alumni->bekerja->nm_instansi : '' }}">
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h2 class="card-inside-title"> Alamat instansi </h2>
    <textarea class="form-control" name="alamat_instansi" required="" aria-required="true"
        aria-invalid="true"> {{ !empty($alumni->bekerja) ? $alumni->bekerja->alamat_instansi : '' }} </textarea>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h2 class="card-inside-title"> Kontak Instansi </h2>
    <input type="text" class="form-control" name="kontak_instansi" required="" aria-required="true" aria-invalid="true"
        value="{{ !empty($alumni->bekerja) ? $alumni->bekerja->kontak_instansi : '' }}">
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h2 class="card-inside-title"> Bidang Usaha </h2>
    <input type="text" class="form-control" name="bidang_usaha_instansi" required="" aria-required="true"
        aria-invalid="true" value="{{ !empty($alumni->bekerja) ? $alumni->bekerja->bidang_usaha_instansi : '' }}">
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h2 class="card-inside-title"> Tahun Masuk </h2>
    <input type="number" class="form-control" name="tahun_masuk_instansi" required="" aria-required="true"
        aria-invalid="true" value="{{ !empty($alumni->bekerja) ? $alumni->bekerja->tahun_masuk_instansi : '' }}">
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h2 class="card-inside-title"> Kapan Mulai Bekerja </h2>
    <div class="demo-radio-button">
        <input name="kapan_mulai_bekerja" value="Sebelum Terima Ijazah" type="radio" class="with-gap"
            @if (!empty($alumni->bekerja)) @if ($alumni->bekerja->kapan_mulai_bekerja == 'Sebelum Terima Ijazah') checked @endif @endif id="radio_1" />
        <label for="radio_1">Sebelum Terima Ijazah</label>
    </div>
    <div class="demo-radio-button">
        <input name="kapan_mulai_bekerja" value="Setelah Terima Ijazah"
            @if (!empty($alumni->bekerja)) @if ($alumni->bekerja->kapan_mulai_bekerja == 'Setelah Terima Ijazah') checked @endif @endif type="radio"
            class="with-gap" id="radio_2" />
        <label for="radio_2">Setelah Terima Ijazah</label>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h2 class="card-inside-title"> Lama Bekerja (Dalam Bulan) </h2>
    <input type="number" class="form-control" name="lama_bekerja" required="" aria-required="true" aria-invalid="true"
        value="{{ !empty($alumni->bekerja) ? $alumni->bekerja->lama_bekerja : '' }}">
</div>
