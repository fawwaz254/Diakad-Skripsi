<div class="container-fluid">
    <h2>
        <a class="btn bg-blue waves-effect target-link"
            href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3) . '/' . $id_bulan . '/' . $tahun) }}">
            <i class="material-icons">backspace</i><span>Kembali</span>
        </a>
    </h2>

    <div class="row clearfix card">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="text-center">No.</th>
                    <th>Tanggal Presensi</th>
                    <th>Pertemuan Ke-</th>
                    <th>Jadwal</th>
                    <th>Kelas</th>
                    <th>Uraian Materi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data_presensi as $presensi)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::create($presensi->tgl_presensi)->isoFormat('dddd, D MMMM Y') }}</td>
                        <td>{{ $presensi->pertemuan_ke }}</td>
                        <td>{{ $presensi->waktu_mulai }} - {{ $presensi->waktu_selesai }}</td>
                        <td>{{ $presensi->nm_kelas_mp }}</td>
                        <td>{{ $presensi->uraian_materi }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
