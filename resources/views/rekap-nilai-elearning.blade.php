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
            <td style="text-align: center;font-weight: bold;">No</td>
            <td style="text-align: center;font-weight: bold;">Kelas</td>
            <td style="text-align: center;font-weight: bold;">NIS</td>
            <td style="text-align: center;font-weight: bold;">Nama Siswa</td>
            <td style="text-align: center;font-weight: bold;">Nilai</td>
            <td>Detail : </td>
            @foreach ($data['paket_soal']['detail_paket_soal'] as $detail_paket_soal)
                @if (in_array($detail_paket_soal->soal->id_tipe_soal, [1, 2, 3, 4, 5]))
                    <td>{{ substr($detail_paket_soal->soal->text, 0, 10) }}</td>
                @endif
            @endforeach
            <td>-</td>
            @foreach ($data['paket_soal']['detail_paket_soal'] as $detail_paket_soal)
                @if ($detail_paket_soal->soal->id_tipe_soal == '6')
                    @foreach ($detail_paket_soal->soal->pilihan_pertanyaan as $pilihan_pertanyaan)
                        <td>{{ substr(strip_tags($pilihan_pertanyaan->text), 0, 10) }}</td>
                    @endforeach
                @endif
            @endforeach
            @foreach ($data['paket_soal']['detail_paket_soal'] as $detail_paket_soal)
                @if ($detail_paket_soal->soal->id_tipe_soal == '7')
                    @foreach ($detail_paket_soal->soal->pilihan_soal as $pilihan_soal)
                        <td>{{ substr(strip_tags($pilihan_soal->text), 0, 10) }}</td>
                    @endforeach
                @endif
            @endforeach
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
                        {{ $data['nilai_siswa'][$siswa->id_pengguna] ?? '-' }}</td>

                    <td></td>
                    @foreach ($data['paket_soal']['detail_paket_soal'] as $detail_paket_soal)
                        @if (in_array($detail_paket_soal->soal->id_tipe_soal, [1, 2, 3, 4, 5]))
                            <td @if (isset($data['benar'][$siswa->id_pengguna][$detail_paket_soal->id_soal])) style="background-color: #91ff87" @endif>
                                {{ isset($data['isi'][$siswa->id_pengguna][$detail_paket_soal->id_soal]) ? $data['isi'][$siswa->id_pengguna][$detail_paket_soal->id_soal] : '' }}
                            </td>
                        @endif
                    @endforeach
                    <td>-</td>
                    @foreach ($data['paket_soal']['detail_paket_soal'] as $detail_paket_soal)
                        @if ($detail_paket_soal->soal->id_tipe_soal == '6')
                            @foreach ($detail_paket_soal->soal->pilihan_pertanyaan as $pilihan_pertanyaan)
                                <td
                                    @if (isset($data['benar'][$siswa->id_pengguna][$detail_paket_soal->id_soal][$pilihan_pertanyaan->nomer])) style="background-color: #91ff87; text-align: center" @else  style="text-align: center" @endif>
                                    {{ isset($data['isi'][$siswa->id_pengguna][$detail_paket_soal->id_soal][$pilihan_pertanyaan->nomer]) ? $data['isi'][$siswa->id_pengguna][$detail_paket_soal->id_soal][$pilihan_pertanyaan->nomer] : '' }}
                                </td>
                            @endforeach
                        @endif
                    @endforeach
                    @foreach ($data['paket_soal']['detail_paket_soal'] as $detail_paket_soal)
                        @if ($detail_paket_soal->soal->id_tipe_soal == '7')
                            @foreach ($detail_paket_soal->soal->pilihan_soal as $pilihan_soal)
                                <td @if (isset($data['benar'][$siswa->id_pengguna][$detail_paket_soal->id_soal][$pilihan_soal->number_option])) style="background-color: #91ff87" @endif>
                                    {{ isset($data['isi'][$siswa->id_pengguna][$detail_paket_soal->id_soal][$pilihan_pertanyaan->number_option]) ? $data['isi'][$siswa->id_pengguna][$detail_paket_soal->id_soal][$pilihan_pertanyaan->number_option] : '' }}
                                </td>
                            @endforeach
                        @endif
                    @endforeach
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
