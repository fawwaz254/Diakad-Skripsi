<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('plugins/bootstrap/css/bootstrap.css') }}?v=2" rel="stylesheet">
    <title>{{ 'Rekap Data Kerja Harian ' . $datas[0]->getRelation('pengguna')->nm_pengguna }}</title>

    <style media="print" type="text/css">
        @page {
            /* margin: 125mm 125mm 125mm 125mm;    */
            size: portrait;
            size: auto;

        }
    </style>

</head>

<body>
    <main>
        <div>
            <div>
                <h1 style="margin-bottom: 50px; text-align: center; font-size: 28px; font-weight: bold">
                    {{ 'Rekap Data Kerja Harian ' . $datas[0]->getRelation('pengguna')->nm_pengguna }}</h1>
            </div>
            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                id="primary_table">
                <thead>
                    <tr>
                        <th rowspan="2" style="vertical-align : middle;text-align:center;">No</th>
                        <th rowspan="2" style="vertical-align : middle;text-align:center;">Tanggal</th>
                        <th colspan="2" style="vertical-align : middle;text-align:center;">Kategori</th>
                        <th rowspan="2" style="vertical-align : middle;text-align:center;">Uraian Kegiatan</th>
                        <th rowspan="2" style="vertical-align : middle;text-align:center;">Status</th>
                        <th rowspan="2" style="vertical-align : middle;text-align:center;">File</th>
                        <th rowspan="2" style="vertical-align : middle;text-align:center;">Nama</th>
                    </tr>
                    <tr>
                        <th style="vertical-align : middle;text-align:center; width: 10px">Jenis</th>
                        <th style="vertical-align : middle;text-align:center;">Mapel</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $data)
                        <tr>
                            <td style="vertical-align : middle;text-align:left;">{{ $loop->iteration }}</td>
                            <td style="vertical-align : middle;text-align:left;">{{ $data->tanggal }}</td>
                            <td style="vertical-align : middle;text-align:left;">{{ $data->jenis }}</td>
                            <td style="vertical-align : middle;text-align:left;">
                                {{ $data->getRelation('mapel')->category_file_name }}
                            </td>
                            <td style="vertical-align : middle;text-align:left;">{{ $data->keterangan_progres }}</td>
                            <td style="vertical-align : middle;text-align:left;">
                                {{ $data->status == 1 ? 'selesai' : 'belum selesai' }}</td>
                            <td style="vertical-align : middle;text-align:left;">-</td>
                            <td style="vertical-align : middle;text-align:left;">
                                {{ $data->getRelation('pengguna')->nm_pengguna }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>

    <script>
        window.print();
    </script>
</body>

</html>
