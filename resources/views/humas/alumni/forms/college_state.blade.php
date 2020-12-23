<h2 class="status-header"> Data Universitas </h2>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
  <h2 class="card-inside-title"> Nama Perguruan Tinggi </h2>
  <input type="text" class="form-control" name="nm_perguruan" required="" aria-required="true" aria-invalid="true" value="{{(!empty($alumni->kuliah))? $alumni->kuliah->nm_perguruan : ''}}">
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
  <h2 class="card-inside-title"> Alamat Perguruan Tinggi </h2>
  <textarea class="form-control" name="alamat_perguruan" required="" aria-required="true" aria-invalid="true"> {{(!empty($alumni->kuliah))? $alumni->kuliah->alamat_perguruan : ''}} </textarea>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
  <h2 class="card-inside-title"> Fakultas </h2>
  <input type="text" class="form-control" name="fakultas" required="" aria-required="true" aria-invalid="true" value="{{(!empty($alumni->kuliah))? $alumni->kuliah->fakultas : ''}}">
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
  <h2 class="card-inside-title"> Jurusan / Program Studi </h2>
  <input type="text" class="form-control" name="prodi" required="" aria-required="true" aria-invalid="true" value="{{(!empty($alumni->kuliah))? $alumni->kuliah->prodi : ''}}">
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
  <h2 class="card-inside-title"> Jenjang </h2>
  <select class="form-control show-tick" name="jenjang">
    <option value="d1" {{(!empty($alumni->kuliah) && $alumni->kuliah->jenjang == 'd1')? 'selected' : ''}}> Diploma 1 </option>
    <option value="d2" {{(!empty($alumni->kuliah) && $alumni->kuliah->jenjang == 'd2')? 'selected' : ''}}> Diploma 2 </option>
    <option value="d3" {{(!empty($alumni->kuliah) && $alumni->kuliah->jenjang == 'd3')? 'selected' : ''}}> Diploma 3 </option>
    <option value="d4" {{(!empty($alumni->kuliah) && $alumni->kuliah->jenjang == 'd4')? 'selected' : ''}}> Diploma 4 </option>
    <option value="s1" {{(!empty($alumni->kuliah) && $alumni->kuliah->jenjang == 's1')? 'selected' : ''}}> Sarjana </option>
    <option value="s2" {{(!empty($alumni->kuliah) && $alumni->kuliah->jenjang == 's2')? 'selected' : ''}}> Magister </option>
  </select>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
  <h2 class="card-inside-title"> Tahun Masuk </h2>
  <input type="text" class="form-control" name="tahun_masuk_perguruan" required="" aria-required="true" aria-invalid="true" value="{{(!empty($alumni->kuliah))? $alumni->kuliah->tahun_masuk_perguruan : ''}}">
</div>