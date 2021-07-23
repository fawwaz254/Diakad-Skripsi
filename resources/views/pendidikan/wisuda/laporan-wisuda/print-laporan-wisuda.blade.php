<!doctype html>
<html lang="en">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<title>Laporan Wisuda</title>
</head>
<body>

<div class="container" style="margin-top: 30px;">
<h3 class="text-center">Laporan Wisuda {{$periode_wisuda->nm_periode_wisuda}} ({{$periode_wisuda->semester->tahun_ajaran}} {{$periode_wisuda->semester->nm_semester}}) </h3>

<table class="table table-bordered" style="margin-top:20px;">
  <thead>
    <tr>
      <th scope="col" class="text-center">No</th>
      <th scope="col">Nama</th>
      <th scope="col">Kelas</th>
    </tr>
  </thead>
  <tbody>
    @foreach($siswa_wisuda as $r)
    <tr>
      <td class="text-center">{{$loop->iteration}}</td>
      <td>{{$r->siswa->pengguna->nm_pengguna}}</td>
      <td>{{$r->kelas->nm_kelas}}</td>
    </tr>
    @endforeach
  </tbody>
</table>

</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>