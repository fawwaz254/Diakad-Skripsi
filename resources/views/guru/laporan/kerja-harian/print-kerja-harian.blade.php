<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <meta name="viewport" content="width=device-width, initial-scale=1"> --}}

    <!-- Bootstrap CSS -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> --}}

    <title>Laporan Kerja Harian</title>
    <style type="text/css" media="print">
        /* table,
        td,
        th {
          
        } */

        table {
            width: 100%;
            border-collapse: collapse;
            /* border: 5px double; */
        }
        p{
    width: 180px;
    border-bottom: 1px solid black;
} 

        .border > * {
        border: 1px solid;
            padding: 10px;
          /* border-style: none; */
        }

        @page {
            /* margin: 125mm 125mm 125mm 125mm;    */
            size: portrait;
            size: auto;
            margin: 0mm;

        }
    </style>
  </head>
  <body>

    <div class="page">
      <table style="width: 40%; margin-left:5%; border-style: solid; margin-top:5px;  " >
        <tr >
            <td>
              <h3 align="center">
                <span align="center" style="margin-top: 1px; font-family: 'Brush Script MT';">
                    {{ $auth_data->sekolah_data->nm_yayasan_sekolah }}
                </span>
                <br>
                <span align="center" style="margin-top: 1px; font-family: 'Cooper Black';">
                    {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}<br>
                </span></h3>
            </td>
        </tr>
    </table>
    <br>
    <h2 style="margin-top: 40px;text-align: center;font-family: 'Cooper Black';">KEGIATAN HARIAN (TIME SHEET)</h2>

    <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;" >
      <tr >
                     <td style="border-style : hidden;width: 75%;font-weight: bold;">
                         {{-- {{ $pengguna->gelar_depan }} {{ $pengguna->nm_pengguna }} {{ $pengguna->gelar_belakang }} --}}
                     <td style="border-style : hidden;width: 25%;font-weight: bold;">Bulan :
                         {{ $bulan }}
                 </tr>
             </table>
    <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;" style="margin-top:20px;" class="border">
    <thead>
    <tr  class="border">
    <th scope="col" rowspan="2" style="vertical-align:middle;text-align: center;">No</th>
    <th scope="col" rowspan="2" style="vertical-align:middle;text-align: center;">Tanggal</th>
    <th scope="col" colspan="2" style="vertical-align:middle;text-align: center;">Tempat</th>
     <th scope="col" rowspan="2" style="vertical-align:middle;text-align: center;">Uraian Kegiatan</th>
     <th scope="col" rowspan="2" style="vertical-align:middle;text-align: center;">Hasil</th>
     <th scope="col" rowspan="2" style="vertical-align:middle;text-align: center;">Kesesuaian<br>Program 98</th>
    </tr>
    <tr  class="border">
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
      <tr  class="border">
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
        <td>{{$value->hasil}}</td>
        <td style="vertical-align:middle;text-align: center;">{{$value->kesesuaian_program_98}}</td>
      </tr>
      @php
      $tanggal_awal = $value->tanggal;
      @endphp
      @endforeach
    </tbody>
    </table>
    <br>

    <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;border-style: none" style="margin-top:20px;" class="removeB">
      <tr>
      <td></td>
      <td></td>
      <td>{{$alamat}}, {{$tanggal}}</td>
      </tr>
      <tr>
        <td style="padding-buttom: 0">Mengetahui,</td>
      
       
        <td style="padding-buttom: 0">Menyetujui,</td>
      
        <td style="padding-buttom: 0"></td>
      </tr>
      <tr>
        {{-- <td>{{ $auth_data->sekolah_data->nm_sekolah }}</td> --}}
        <td style="padding-top: 0">YPM Taman Sepanjang,</td>
      
       
        <td style="padding-top: 0">Kepala Sekolah,</td>
      
        <td style="padding-top: 0">Penyusun,</td>
      </tr>
     
     <tr style="height:70px">
     </tr>
      <tr>
        <td><p></p></td>
      
        <td><b>{{$kepala_sekolah}}</b></td>
       
      
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


<script>
  window.print();
</script>