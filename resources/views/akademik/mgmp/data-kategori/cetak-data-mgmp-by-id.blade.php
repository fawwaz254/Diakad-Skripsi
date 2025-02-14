<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <main>
        <div>
            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                id="primary_table">
                <thead>
                    <tr>
                        <th rowspan="2" style="vertical-align : middle;text-align:center;">No</th>
                        <th rowspan="2" style="vertical-align : middle;text-align:center;">Tanggal</th>
                        <th colspan="2" style="vertical-align : middle;text-align:center;">Kategori</th>
                        <th rowspan="2" style="vertical-align : middle;text-align:center;">Uraian Kegiatan</th>
                        <th rowspan="2" style="vertical-align : middle;text-align:center;">Tuntas</th>
                        <th rowspan="2" style="vertical-align : middle;text-align:center;">File</th>
                        <th rowspan="2" style="vertical-align : middle;text-align:center;">Nama</th>
                    </tr>
                    <tr>
                        <th style="vertical-align : middle;text-align:center;">Jenis</th>
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
                        {{-- @dd($data) --}}
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
