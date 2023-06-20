<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
        integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <title>Laporan Pimpinan</title>
</head>

<body>

    <div class="container" style="margin-top:30px;">

        <center>
            <img src="https://diakad.sgp1.digitaloceanspaces.com/{{ $sekolah->nm_singkat_sekolah }}/global/logo-sekolah"
                alt="Logo Sekolah" style="height:90px;" />
            <br>
            <h3 style="font-family: 'Nunito', sans-serif;">Data Penggunaan {{ strtoupper(env('APP_NAME', 'EDUMATE')) }}
                Untuk Setiap Role Semester {{ $semester_aktif->tahun_ajaran }} {{ $semester_aktif->nm_semester }}</h3>
            <h4 style="font-family: 'Nunito', sans-serif;">Per Tanggal :
                {{ indonesiaDate(\Carbon\Carbon::now()->format('Y-m-d')) }}</h4>
        </center>

        <table class="table table-bordered" style="margin-top:20px;">
            <thead>
                <tr>
                    <th>Role</th>
                    <th>Indikator</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $key => $value)
                    <tr>
                        @foreach ($value['catatan'] as $key2 => $value2)
                            @if ($key2 == 0)
                                <td rowspan="{{ $value['rowspan'] }}"
                                    style="text-align: center;vertical-align: middle;">{{ $value['role'] }}</td>
                                <td>{{ $value2['catatan'] }}</td>
                                @if ($value2['status'] == 0)
                                    <td><i class="fas fa-times" style="color:red;text-align: center;"></i></td>
                                @else
                                    <td><i class="fas fa-check" style="color:green;text-align: center;"></i></td>
                                @endif
                            @endif
                        @endforeach
                    </tr>

                    @if (count($value['catatan']) > 1)
                        @foreach ($value['catatan'] as $key2 => $value2)
                            @if ($key2 > 0)
                                <tr>
                                    <td>{{ $value2['catatan'] }}</td>
                                    @if ($value2['status'] == 0)
                                        <td><i class="fas fa-times" style="color:red;text-align: center;"></i></td>
                                    @else
                                        <td><i class="fas fa-check" style="color:green;text-align: center;"></i></td>
                                    @endif
                                </tr>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </tbody>
        </table>

    </div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous">
    </script>

    <script type="text/javascript">
        window.print();
    </script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"
    integrity="sha384-eMNCOe7tC1doHpGoWe/6oMVemdAVTMs2xqW4mwXrXsW0L84Iytr2wi5v2QjrP/xp" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js"
    integrity="sha384-cn7l7gDp0eyniUwwAZgrzD06kc/tftFf19TOAs2zVinnD/C7E91j9yyk5//jjpt/" crossorigin="anonymous">
</script>
-->
</body>

</html>
