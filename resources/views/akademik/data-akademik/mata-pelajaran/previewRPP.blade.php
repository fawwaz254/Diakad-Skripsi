<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Preview Mapel Desain Pembelajaran</title>

    <style>
        #preview_RPP {
            border-collapse: collapse;
            width: 100%;
        }

        #preview_RPP td,
        #preview_RPP th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #preview_RPP tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #preview_RPP th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: grey;
            color: white;
        }
    </style>
</head>

<body>
    <header>
        <h2>Desain Pembelajaran Mata Pelajaran : {{ $mapel_rpp->mata_pelajaran->nm_mata_pelajaran }}</h2>
        <p>Kode Mapel: {{ $mapel_rpp->mata_pelajaran->kd_mata_pelajaran }}</p>
    </header>
    <table id="preview_RPP">
        <thead>
            <tr>
                <th>Pertemuan Ke</th>
                <th>Deskripsi</th>
                <th>Model Pembelajaran</th>
                <th>Karakter yang ditanamkan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detail_rpp as $row_detail)
                <tr>
                    <td>
                        {{ $row_detail->pertemuan_ke }}
                    </td>
                    <td>
                        {{ $row_detail->deskripsi }}
                    </td>
                    <td>
                        {{ $row_detail->model_pembelajaran }}
                    </td>
                    <td>
                        {{ $row_detail->nilai_karakter }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
