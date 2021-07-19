<!doctype html>
<html lang="en">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<title>Laporan Magang</title>
</head>
<body>

<div class="container text-center" style="margin-top: 30px;">
  
<h3 style="text-decoration:underline;">Laporan Penilaian Magang</h3>
<h5 >{{$data->rekanan->nm_rekanan_magang}} Periode : {{$data->periode->nm_periode_magang}}</h5>

<table class="table table-bordered" style="margin-top: 30px;">
  <thead>
    <tr>
      <th scope="col" rowspan="2" style="text-align:center;vertical-align: middle;">No</th>
      <th scope="col" rowspan="2" style="text-align:center;vertical-align: middle;">Nama</th>
      <th scope="col" rowspan="2" style="text-align:center;vertical-align: middle;">Kelas</th>
      <th scope="col" colspan="{{$list_komponen->count()}}">Komponen Penilaian</th>
      <th scope="col" rowspan="2" style="text-align:center;vertical-align: middle;">Total Nilai</th>
    </tr>
    <tr>
      @foreach($list_komponen as $r)
      <th>{{$r->nm_komponen_magang}} ( {{$r->persentase_komponen_magang}} % )</th>
      @endforeach
    <tr>
  </thead>
  <tbody>
    @foreach($list_siswa as $siswa)
    <tr>
      <td>{{$loop->iteration}}</td>
      <td>{{$siswa->nm_pengguna}}</td>
      <td>{{$siswa->nm_kelas}}</td>
      @foreach($list_komponen as $nilai)
        <td>{{ isset($nilai_magang_siswa[$siswa->id_pengambilan_magang.$nilai->id_komponen_magang]) ? $nilai_magang_siswa[$siswa->id_pengambilan_magang.$nilai->id_komponen_magang] : '-' }}</td>
      @endforeach
      <td>{{isset($siswa->nilai_angka) ? $siswa->nilai_angka : '-'}}</td>
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