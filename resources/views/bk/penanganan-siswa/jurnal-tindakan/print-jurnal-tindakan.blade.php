<!doctype html>
<html lang="en">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

<title>Cetak Jurnal Tindakan</title>
</head>
<body>

<div class="container text-center" style="margin-top:20px;">
<div class="row">
    <div class="col-md-2">
        <img src="{{asset('logo/logo-yayasan.png')}}" width="120">
    </div>
    <div class="col-md-8">
        <h2>YAYASAN PENDIDIKAN DAN SOSIAL MA'ARIF <br> TAMAN – SEPANJANG – SIDOARJO</h2>
        <p>Akte Notaris Goesti Djohan Nomor 91 Tanggal 17 September 1965</p>    
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-12">
    <p>Jl.Raya Ngelom 86 Telp. (031) 7874045 Fax. (031) 7884364 Sepanjang 61257 website http.//www.ypm.ac.id- e-mail:ypm.ac.id.yayasan@ypm.ac.id</p>
    </div>
</div>
</div>

<div class="container text-center" style="margin-top:20px;">
    
<h2>LAPORAN PRIBADI SISWA <br> {{strtoupper($sekolah_data->nm_sekolah)}}</h2>
<hr>
<div class="row" style="margin-top: 15px;">
<table class="table table-borderless" style="text-align:left">
<thead>
<tr>
<th scope="col">Nama</th>
<th scope="col" style="font-weight: 400">: {{$siswa->nm_pengguna}}</th>
<th scope="col">Kelas</th>
<th scope="col" style="font-weight: 400">: {{$siswa->nm_kelas}}</th>
</tr>
</thead>
<tbody>
<tr>
<th scope="row">No Induk</th>
<td>: {{$siswa->nis_siswa}}</td>
<td style="font-weight: 700">Semester</td>
<td>: {{$semester->nm_semester}}</td>
</tr>
<tr>
<th scope="row">Jenis Kelamin</th>
<td>: {{$siswa->jenis_kelamin}}</td>
<td  style="font-weight: 700">Tahun Pelajaran</td>
<td>: {{$semester->tahun_ajaran}}</td>
</tr>

</tbody>
</table>
      
<div class="col-md-12"> 
<h6 style="text-align:left">A. Catatan Siswa</h6>         
<table border="1" style="width:100%" cellspacing="0" cellpadding="10">
<tr>
<th>No.</th>
<th>Jenis Pelanggaran</th>
<th>Pelanggaran Tingkat</th>
<th>Poin</th>
<th>Frekuensi</th>
<th>Jumlah</th>
</tr>
@php
$no = 1;
$jumlah = 0;
@endphp
@foreach($list_data as $data)
<tr>
<td>{{$no++}}.</td>
<td>{!!$data->nm_subkategori_pelanggaran!!}</td>
<td>{{$data->nm_kategori_pelanggaran}}</td>
<td>{{$data->poin_subkategori_pelanggaran}}</td>
<td>{{$data->frekuensi}} x</td>
<td>{{$data->poin_subkategori_pelanggaran * $data->frekuensi}}</td>
@php
$jumlah += $data->poin_subkategori_pelanggaran * $data->frekuensi;
@endphp
</tr>
@endforeach
<tr>
<td colspan="5" align="center"><b>TOTAL</b></td>
<td align="center"><b>{{$jumlah}}</b></td>
</tr>
</table>
</div>

<div class="col-md-12">
<h6 style="margin-top:15px;text-align: left;">B. Deskripsi Perilaku Siswa</h6> 
<fieldset style="height: 100px;border:2px solid black">
    <p></p>
</fieldset>
</div>

<div class="col-md-12">
<h6 style="margin-top:15px;text-align: left;">C. Catatan Sekolah</h6> 
<fieldset style="height: 100px;border:2px solid black">
    <p></p>
</fieldset>
</div>

<div class="col-md-3 offset-md-9" style="margin-top:45px;">
  Sidoarjo, {{now('Asia/Jakarta')->format('d M Y')}}  <br> Wali Kelas 
  <div style="margin-top:30px;">.....</div>
</div>
   
</div>

<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>

<!-- Option 2: Separate Popper and Bootstrap JS -->
<!--
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>
-->
</body>
</html>