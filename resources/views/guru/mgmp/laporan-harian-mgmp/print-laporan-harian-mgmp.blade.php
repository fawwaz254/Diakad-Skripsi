<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Rekap Laporan Harian</title>

    <style>
        * {
            font-family: 'Tahoma';
        }

        table,
        td,
        th {
            border: 1px solid;
            padding: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        @page { 
            size: auto;
            margin: 20mm 0 10mm 0;
        }
        body {
            margin: 0;
            padding: 0;
        }
    </style>
  </head>
  <body class="my-2">
      <h1 class="mt-2 text-center">Rekap Laporan Harian Guru</h1>
      
      <div class="mx-auto" style="width: 90%">
        <table class="table border border-dark">
            <thead style="background-color: #C2D69B">
            <tr>
                <th scope="col" rowspan="2" style="vertical-align:middle;text-align:center;">No.</th>
                <th scope="col" rowspan="2" style="vertical-align:middle;text-align:center;">Tanggal</th>
                <th scope="col" rowspan="2" style="vertical-align:middle;text-align:center;">Urian Kegiatan</th>
                <th scope="col" colspan="2" style="vertical-align:middle;text-align:center;">Kategori</th>
                <th scope="col" rowspan="2" style="vertical-align:middle;text-align:center;">Status</th>
            </tr>
            <tr>
                <th scope="col" style="vertical-align:middle;text-align:center;">Jenis</th>
                <th scope="col" style="vertical-align:middle;text-align:center;">Mata Pelajaran</th>
            </tr>
            
            </thead>
            <tbody>
            @foreach ($list_data as $item)
            <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                <td>{!! nl2br($item->keterangan_progres) !!}</td>
                <td>{{ $item->jenis }}</td>
                <td>{{ $item->mata_pelajaran->category_file_name }}</td>
                <td>{{ $item->status == 1 ? 'Tuntas' : 'Belum Tuntas' }}</td>
            </tr>
            @endforeach
            </tbody>
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