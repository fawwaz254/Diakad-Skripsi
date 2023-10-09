<table class="table table-bordered">
    <thead>
        <tr>
            <td colspan='6'>
                {{ 'Paket Soal: ' . $data['paket_soal']['text'] }}
            </td>
        </tr>
        <tr>
            <td colspan='6'>
                {{ 'Mata Pelajaran : ' . $data['paket_soal']['kategori_soal']['nm_kategori_soal'] }}
            </td>
        </tr>
        <tr>
            <td colspan='6'>
                {{ 'Waktu : ' . \Carbon\Carbon::parse($data['paket_soal']['waktu_mulai'])->format('d M Y,  H:i') }}
            </td>
        </tr>

        <tr>
            <td style="text-align: center;font-weight: bold;">No</td>
            <td style="text-align: center;font-weight: bold;">Kelas</td>
            <td style="text-align: center;font-weight: bold;">NIS</td>
            <td style="text-align: center;font-weight: bold;">Nama Siswa</td>
            <td style="text-align: center;font-weight: bold;">Soal Terjawab</td>
            <td style="text-align: center;font-weight: bold;">Nilai</td>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 0;
        @endphp
        @foreach ($data['paket_soal']['paket_soal_kelas'] as $paket_soal_kelas)
            @foreach ($paket_soal_kelas->kelas->siswa as $siswa)
                <tr>
                    <td style="text-align: center">{{ ++$no }}</td>
                    <td style="text-align: center">{{ $paket_soal_kelas->kelas->nm_kelas }}</td>
                    <td style="text-align: center">{{ $siswa->nis_siswa }}</td>
                    <td>{{ $siswa->pengguna->nm_pengguna }}</td>
                    <td style="text-align: center">
                        {{ $data['soal_terjawab'][$siswa->id_pengguna] ?? '-' }}</td>
                    <td style="text-align: center">
                        {{ $data['nilai_siswa'][$siswa->id_pengguna] ?? '-' }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>

</table>

<style>
    .center {
        text-align: center;
        vertical-align: middle;
        background-color: red;
    }
</style>
