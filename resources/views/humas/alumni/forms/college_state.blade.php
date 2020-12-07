<h2 class="status-header"> Data Universitas </h2>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
  <h2 class="card-inside-title"> Nama Perguruan Tinggi </h2>
  <input type="text" class="form-control" name="nm_perguruan" required="" aria-required="true" aria-invalid="true" value="{{(!empty($item))? $item->nm_perguruan : ''}}">
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
  <h2 class="card-inside-title"> Alamat Perguruan Tinggi </h2>
  <textarea class="form-control" name="alamat" required="" aria-required="true" aria-invalid="true"> {{(!empty($item))? $item->alamat : ''}} </textarea>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
  <h2 class="card-inside-title"> Fakultas </h2>
  <input type="text" class="form-control" name="fakultas" required="" aria-required="true" aria-invalid="true" value="{{(!empty($item))? $item->fakultas : ''}}">
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
  <h2 class="card-inside-title"> Jurusan / Program Studi </h2>
  <input type="text" class="form-control" name="prodi" required="" aria-required="true" aria-invalid="true" value="{{(!empty($item))? $item->prodi : ''}}">
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
  <h2 class="card-inside-title"> Jenjang </h2>
  <select class="form-control show-tick" name="jenjang">
    <option value="d1" {{(!empty($item) && $item->d1 == 0)? 'selected' : ''}}> Diploma 1 </option>
    <option value="d2" {{(!empty($item) && $item->d2 == 1)? 'selected' : ''}}> Diploma 2 </option>
    <option value="d3" {{(!empty($item) && $item->d3 == 1)? 'selected' : ''}}> Diploma 3 </option>
    <option value="d4" {{(!empty($item) && $item->d4 == 1)? 'selected' : ''}}> Diploma 4 </option>
    <option value="s1" {{(!empty($item) && $item->s1 == 1)? 'selected' : ''}}> Sarjana </option>
    <option value="s2" {{(!empty($item) && $item->s2 == 1)? 'selected' : ''}}> Magister </option>
  </select>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
  <h2 class="card-inside-title"> Tahun Masuk </h2>
  <input type="text" class="form-control" name="tahun_masuk" required="" aria-required="true" aria-invalid="true" value="{{(!empty($item))? $item->tahun_masuk : ''}}">
</div>