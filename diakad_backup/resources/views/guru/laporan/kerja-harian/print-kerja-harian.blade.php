<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Laporan Kerja Harian</title>
  </head>
  <body>

    <div class="container">
    <h5 style="margin-top: 40px;text-align: center;">KEGIATAN HARIAN (TIME SHEET)</h5>

    <table class="table table-bordered" style="margin-top:20px;border: 2px solid black;">
    <thead>
    <tr>
    <th scope="col" rowspan="2" style="vertical-align:middle;text-align: center;">No</th>
    <th scope="col" rowspan="2" style="vertical-align:middle;text-align: center;">Tanggal</th>
    <th scope="col" colspan="2" style="vertical-align:middle;text-align: center;">Tempat</th>
     <th scope="col" rowspan="2" style="vertical-align:middle;text-align: center;">Uraian Kegiatan</th>
    </tr>
    <tr>
      <th style="vertical-align:middle;text-align: center;">Knt</th>
      <th style="vertical-align:middle;text-align: center;">Lap</th>
    </tr>
    </thead>
    <tbody>
      @php
      $counter = 1;
      $tanggal_awal = '-';
      @endphp
      @foreach($laporan as $key => $value)
      <tr>
        <td>
          @if($value->tanggal != $tanggal_awal)
          {{$counter}}
          @php
          $counter++;
          @endphp
          @endif
        </td>
        <td>
          @if($value->tanggal != $tanggal_awal)
          {{\Carbon\carbon::parse($value->tanggal)->format('d M Y')}}
          @endif
        </td>
    
        <td style="vertical-align:middle;text-align: center;">{{$value->lokasi == "kantor" ? '√' : ''}}</td>
        <td style="vertical-align:middle;text-align: center;">{{$value->lokasi == "lapangan" ? '√' : ''}}</td>
        <td>{{$value->uraian_kegiatan}}</td>
      </tr>
      @php
      $tanggal_awal = $value->tanggal;
      @endphp
      @endforeach
    </tbody>
    </table>
    <br>

    <table style="width:130%">
      <tr>
      <td></td>
      <td></td>
      <td></td>
      <td>{{$alamat}},{{$tanggal}}</td>
      </tr>
      <tr>
        <td>Mengetahui,</td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td>Kepala Sekolah,</td>
        <td></td>
        <td></td>
        <td>Penyusun,</td>
      </tr>
     
     <tr style="height:70px">
     </tr>
      
      
      
      
     
      <tr>
        <td><b>{{$kepala_sekolah}}</b></td>
        <td></td>
        <td></td>
        <td><b>{{$biodata}}</b></td>
      </tr>
    </table>

    




    </div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
  </body>
</html>