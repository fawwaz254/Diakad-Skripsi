<!doctype html>
<html lang="en">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<style type="text/css">
table , td, th {
  border: 1px solid black;
  padding: 8px;
}

table {
  width: 100%;
  border-collapse: collapse;
}

.biodata tr td{
	border: 1px solid white;
}
</style>

<title>SKPI Siswa</title>
</head>
<body>

<h4 class="text-center" style="margin-top: 30px;text-decoration: underline;">SURAT KETERANGAN PENDAMPING IJAZAH</h4>

<div class="container" style="margin-top: 40px;">

<table class="biodata">
	<tr>
		<td style="width: 10%;">Nama </td>
		<td>: {{$siswa->nm_c_siswa}}</td>
	</tr>
	<tr>
		<td style="width: 10%;">NIS </td>
		<td>: {{$siswa->nis_siswa}}</td>
	</tr>
	<tr>
		<td style="width: 10%;">Kelas </td>
		<td>: {{$siswa->nm_kelas}}</td>
	</tr>
</table>

@if($prestasi->count()>0)
<h6  style="margin-top: 30px;">Prestasi</h6>
<table>
  <thead>
    <tr>
        <th>No</th>
        <th>Nama Prestasi</th>
        <th>Tingkat Prestasi</th>
        <th>Jenis Prestasi</th>
        <th>Peringkat</th>
        <th>Lokasi</th>
        <th>Penyelenggara</th>
        <th>Tanggal</th>
    </tr>
  </thead>
  <tbody>
  	@foreach($prestasi as $r)
  		<tr>
  			<td>{{$loop->iteration}}</td>
  			<td>{{$r->nm_prestasi_siswa}}</td>
  			<td>{{$r->nm_tingkat_prestasi_siswa}}</td>
  			<td>
  			@if($r->jenis_prestasi_siswa == 1)
  			Sains
            @elseif ($r->jenis_prestasi_siswa == 2)
            Seni
            @elseif ($r->jenis_prestasi_siswa == 3)
            Olahraga
            @else
            Lain lain
            @endif 
            </td>
            <td>{{$r->peringkat_prestasi_siswa}}</td>
            <td>{{$r->lokasi_prestasi_siswa}}</td>
            <td>{{$r->penyelenggara_prestasi_siswa}}</td>
            <td>{{date("d F Y", strtotime($r->tgl_prestasi_siswa))}}</td>
  		</tr>
  	@endforeach
  </tbody>
</table>
@endif

@if($kegiatan->count() > 0)
<h6 style="margin-top: 30px;">Kegiatan</h6>
<table >
  <thead>
    <tr>
       <th>No</th>
        <th>Nama Kegiatan</th>
        <th>Tingkat Kegiatan</th>
        <th>Tanggal</th>
    </tr>
  </thead>
  <tbody>
  	@foreach($kegiatan as $r)
  		<tr>
  			<td>{{$loop->iteration}}</td>
  			<td>{{$r->nm_kegiatan_siswa}}</td>
  			<td>{{$r->nm_tingkat_prestasi_siswa}}</td>
            <td>{{date("d F Y", strtotime($r->tgl_kegiatan_siswa))}}</td>
  		</tr>
  	@endforeach
  </tbody>
</table>
@endif

</div>

<script>
        window.print();
</script>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>

