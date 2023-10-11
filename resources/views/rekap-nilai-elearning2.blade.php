<table class="table table-bordered">
    <thead>
        <tr>
            <td colspan='5'>
                {{ 'Paket Soal: ' . $data['paket_soal']['text'] }}
            </td>
        </tr>
        <tr>
            <td colspan='5'>
                {{ 'Mata Pelajaran : ' . $data['paket_soal']['kategori_soal']['nm_kategori_soal'] }}
            </td>
        </tr>
        <tr>
            <td colspan='5'>
                {{ 'Waktu : ' . \Carbon\Carbon::parse($data['paket_soal']['waktu_mulai'])->format('d M Y,  H:i') }}
            </td>
        </tr>
        <tr>
            <td colspan='5'>
                {{ 'Pont Tiap Soal : ' . $data['point'] }}
            </td>
        </tr>

        <tr>
            <td style="text-align: center;font-weight: bold;">No</td>
            <td style="text-align: center;font-weight: bold;">Kelas</td>
            <td style="text-align: center;font-weight: bold;">NIS</td>
            <td style="text-align: center;font-weight: bold;">Nama Siswa</td>
            <td style="text-align: center;font-weight: bold;">Nilai</td>
            <td style="text-align: center;font-weight: bold;">Jumlah Soal Betul Pilihan Ganda / Essay</td>
            <td style="text-align: center;font-weight: bold;">Jumlah Sub-Soal Betul Penjodohan / True False</td>

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
                        {{ isset($data['nilai_siswa'][$siswa->id_pengguna]) ? number_format($data['nilai_siswa'][$siswa->id_pengguna]) : '-' }}
                    </td>
                    <td style="text-align: center">
                        {{ isset($data['benar'][$siswa->id_pengguna]['type1']) ? $data['benar'][$siswa->id_pengguna]['type1'] : '-' }}
                    </td>
                    <td style="text-align: center">
                        {{ isset($data['benar'][$siswa->id_pengguna]['type2']) ? $data['benar'][$siswa->id_pengguna]['type2'] : '-' }}
                    </td>
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
