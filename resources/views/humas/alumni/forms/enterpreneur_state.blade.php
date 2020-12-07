<h2 class="status-header"> Data Wirausaha </h2>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
  <h2 class="card-inside-title"> Nama Usaha </h2>
  <input type="text" class="form-control" name="nm_usaha" required="" aria-required="true" aria-invalid="true" value="{{(!empty($item))? $item->nm_usaha : ''}}">
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
  <h2 class="card-inside-title"> Alamat Usaha </h2>
  <textarea class="form-control" name="alamat" required="" aria-required="true" aria-invalid="true"> {{(!empty($item))? $item->alamat : ''}} </textarea>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
  <h2 class="card-inside-title"> Nomor Telepon Usaha </h2>
  <input type="text" class="form-control" name="kontak" required="" aria-required="true" aria-invalid="true" value="{{(!empty($item))? $item->kontak : ''}}">
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
  <h2 class="card-inside-title"> Bidang Usaha </h2>
  <input type="text" class="form-control" name="bidang_usaha" required="" aria-required="true" aria-invalid="true" value="{{(!empty($item))? $item->bidang_usaha : ''}}">
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
  <h2 class="card-inside-title"> Jumlah Karyawan </h2>
  <input type="text" class="form-control" name="jumlah_karyawan" required="" aria-required="true" aria-invalid="true" value="{{(!empty($item))? $item->jumlah_karyawan : ''}}">
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
  <h2 class="card-inside-title"> Bulan dan Tahun Membuka Usaha </h2>
  <input type="text" class="form-control" name="tahun_rintis" required="" aria-required="true" aria-invalid="true" value="{{(!empty($item))? $item->tahun_rintis : ''}}">
</div>