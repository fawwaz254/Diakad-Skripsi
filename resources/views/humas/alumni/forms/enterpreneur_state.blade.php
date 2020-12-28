<h2 class="status-header"> Data Wirausaha </h2>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<h2 class="card-inside-title"> Nama Usaha </h2>
	<input type="text" class="form-control" name="nm_usaha" required="" aria-required="true" aria-invalid="true"
		value="{{(!empty($alumni->usaha))? $alumni->usaha->nm_usaha : ''}}">
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<h2 class="card-inside-title"> Alamat Usaha </h2>
	<textarea class="form-control" name="alamat_usaha" required="" aria-required="true"
		aria-invalid="true"> {{(!empty($alumni->usaha))? $alumni->usaha->alamat_usaha : ''}} </textarea>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<h2 class="card-inside-title"> Nomor Telepon Usaha </h2>
	<input type="text" class="form-control" name="kontak_usaha" required="" aria-required="true" aria-invalid="true"
		value="{{(!empty($alumni->usaha))? $alumni->usaha->kontak_usaha : ''}}">
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<h2 class="card-inside-title"> Bidang Usaha </h2>
	<input type="text" class="form-control" name="bidang_usaha" required="" aria-required="true" aria-invalid="true"
		value="{{(!empty($alumni->usaha))? $alumni->usaha->bidang_usaha : ''}}">
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<h2 class="card-inside-title"> Jumlah Karyawan </h2>
	<input type="text" class="form-control" name="jumlah_karyawan" required="" aria-required="true" aria-invalid="true"
		value="{{(!empty($alumni->usaha))? $alumni->usaha->jumlah_karyawan : ''}}">
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<h2 class="card-inside-title"> Bulan dan Tahun Membuka Usaha </h2>
	<input type="text" class="form-control" name="tahun_rintis" required="" aria-required="true" aria-invalid="true"
		value="{{(!empty($alumni->usaha))? $alumni->usaha->tahun_rintis : ''}}">
</div>