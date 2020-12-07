<h2 class="status-header"> Data Instansi Tempat Bekerja </h2>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
  <h2 class="card-inside-title"> Nama Instansi </h2>
  <input type="text" class="form-control" name="nm_instansi" required="" aria-required="true" aria-invalid="true" value="{{(!empty($item))? $item->nm_instansi : ''}}">
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
  <h2 class="card-inside-title"> Alamat instansi </h2>
  <textarea class="form-control" name="alamat" required="" aria-required="true" aria-invalid="true"> {{(!empty($item))? $item->alamat : ''}} </textarea>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
  <h2 class="card-inside-title"> Kontak Instansi </h2>
  <input type="text" class="form-control" name="kontak" required="" aria-required="true" aria-invalid="true" value="{{(!empty($item))? $item->kontak : ''}}">
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
  <h2 class="card-inside-title"> Bidang Usaha </h2>
  <input type="text" class="form-control" name="bidang_usaha" required="" aria-required="true" aria-invalid="true" value="{{(!empty($item))? $item->bidang_usaha : ''}}">
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
  <h2 class="card-inside-title"> Tahun Masuk </h2>
  <input type="text" class="form-control" name="tahun_masuk" required="" aria-required="true" aria-invalid="true" value="{{(!empty($item))? $item->tahun_masuk : ''}}">
</div>