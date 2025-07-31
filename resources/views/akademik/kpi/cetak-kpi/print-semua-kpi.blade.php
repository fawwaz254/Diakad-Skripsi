<!DOCTYPE html>
<html>
<head>
    <title>Cetak KPI - Kelas {{ $kelas->nm_kelas }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #eee; text-align: center; }
    </style>
</head>
<body>
    <h3>Rekap KPI Siswa</h3>
    <p><strong>Kelas:</strong> {{ $kelas->nm_kelas }}</p>
    <p><strong>Semester:</strong> {{ $semester_aktif->semester ?? '-' }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Jumlah Point</th>
                <th>Point Terisi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($siswaList as $index => $siswa)
                <tr>
                    <td style="text-align: center">{{ $index + 1 }}</td>
                    <td>{{ $siswa->nis_siswa }}</td>
                    <td>{{ $siswa->pengguna->nm_pengguna }}</td>
                    <td style="text-align: center">{{ $jumlah_point }}</td>
                    <td style="text-align: center">{{ $predikat_kpi->where('id_siswa', $siswa->id_siswa)->count() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        // Buka dialog cetak otomatis
        window.onload = function () {
            window.print();
        };
    </script>
</body>
</html>
