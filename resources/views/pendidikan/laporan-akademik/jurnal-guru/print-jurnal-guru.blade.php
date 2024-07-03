<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Jurnal Guru</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            /* margin: 3rem;
            padding: 3rem; */
            background-color: #f8f9fa;

        }

        h1,
        h3 {
            text-align: center;
            color: #343a40;
        }

        h1 {
            margin-bottom: 0.5rem;
        }

        h3 {
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        table,
        th,
        td {
            border: 1px solid #343a40;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #7e7e7e;
            color: #ffffff;
        }

        tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        }

        tbody tr:hover {
            background-color: #e9ecef;
        }
    </style>
</head>

<body>
    <h1>Jurnal Guru</h1>
    <h3>Kelas: {{ $kelas_mp->kelas->nm_kelas }}</h3>
    <h3>Mata Pelajaran: {{ $kelas_mp->mata_pelajaran->nm_mata_pelajaran }}</h3>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Pertemuan</th>
                <th>Hari</th>
                <th>Tanggal</th>
                <th>Jam Pelajaran</th>
                <th>Mata Pelajaran</th>
                <th>Uraian Materi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data_pertemuan as $data)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>Ke : {{ $data['pertemuan_ke'] }}</td>
                    <td>{{ $data['hari'] }}</td>
                    <td>{{ $data['presensi'] ? $data['presensi']->tgl_presensi : '-' }}</td>
                    <td>{{ $data['presensi'] ? $data['presensi']->waktu_mulai . ' - ' . $data['presensi']->waktu_selesai : '-' }}
                    </td>
                    <td>{{ $data['presensi'] ? $data['presensi']->kelas_mp->nm_kelas_mp : '-' }}</td>
                    <td>{{ $data['presensi'] ? $data['presensi']->uraian_materi : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>


<script>
    window.print();
</script>
