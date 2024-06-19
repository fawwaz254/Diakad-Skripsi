<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <style>
        * {
            font-family: 'Tahoma';
            letter-spacing: 1.5px;
        }

        table,
        th,
        td {
            border: 1px solid #000;
            border-collapse: collapse;
            padding: 10px;
            margin-top: 30px;
        }

        table {
            width: 100%;
            margin: 20px auto;
        }

        th {
            background-color: #f2f2f2;
        }

        td {
            padding: 5px;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            text-align: center;
        }

        h2 {
            margin: 20px 0;
        }

        .container {
            margin-top: 30px;
        }

        @page {
            size: portrait;
            margin: 10mm;
        }

        @media print {
            .break {
                page-break-after: always;
            }
        }
    </style>

    <title>Data Alumni</title>
</head>

<body>
    <div class="container">
        <h2>Print Data Alumni</h2>
        <table style="width: 90%; margin: 20px  auto;">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Tahun Lulus</th>
                    <th>Tahun Masuk</th>
                    <th>Nama Sekolah</th>
                    <th>Alamat Sekolah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($alumni as $key => $item)
                    <tr>
                        <td style="text-align: center">{{ $key + 1 }}</td>
                        <td>{{ $item->calon_siswa->nm_c_siswa }}</td>
                        <td style="text-align: center">{{ $item->kelas->nm_kelas }}</td>
                        <td style="text-align: center">{{ $item->tahun_lulus }}</td>
                        <td style="text-align: center">{{ $item->smp->tahun_masuk_sekolah }}</td>
                        <td>{{ $item->smp->nm_sekolah }}</td>
                        <td>{{ $item->smp->alamat_sekolah }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        window.print();
    </script>
</body>

</html>
